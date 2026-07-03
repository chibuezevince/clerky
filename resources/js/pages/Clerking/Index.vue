<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3'
import { useEcho } from '@laravel/echo-vue'
import { Layout } from '@lucide/vue'
import { Motion, AnimatePresence } from 'motion-v'
import { computed, provide, ref, toRef } from 'vue'
import { useRefHistory } from '@vueuse/core'

import ClerkBox from '@/components/clerking/ClerkBox.vue'
import ProcessingBox from '@/components/clerking/ProcessingBox.vue'
import NextQuestions from '@/components/clerking/NextQuestions.vue'
import TopBar from '@/components/clerking/TopBar.vue'
import AppLayout from '@/layouts/AppLayout.vue'

import type {
    Answer,
    Clerking,
    ClerkingSection,
    ClerkingUnit,
    SectionQuestions,
    UnitQuestion,
} from '@/types/dashboard'
import { useSyncClerking } from '@/composables/useSyncClerking'
import { toast } from 'vue-sonner'
import {
    getNextSection,
    getPreviousSection,
    recordAnswerLocally,
    transitionToSection,
} from '@/data/functions'

import { complete } from '@/routes/clerking'
import { noQuestionError } from '@/data/constants'

defineOptions({
    layout: AppLayout,
})

const page = usePage()

const clerking = ref<Clerking>(page.props.clerking as Clerking)
const unit = page.props.unit as ClerkingUnit

const questionHistory = ref<number[]>(clerking.value.question_history ?? [])

const sections = ref(page.props.sections as ClerkingSection[])
const currentSection = ref<ClerkingSection>(
    sections.value.find(
        (section) => section.id === clerking.value.current_section_id,
    ) as ClerkingSection,
)

const processing = ref<boolean>(clerking.value.is_processing)

const allQuestions = ref<SectionQuestions>(clerking.value.all_questions)
const { undo: undoAllQuestions } = useRefHistory(allQuestions, { deep: true })
const questions = ref<UnitQuestion[]>(
    allQuestions.value[`sn-${currentSection.value.id}`],
)

const currentQuestionIndex = ref<number>(clerking.value.current_question_index)
const currentQuestion = computed<UnitQuestion>(
    () => questions.value[currentQuestionIndex.value - 1],
)
const direction = ref(1)
const nextQuestions = computed<UnitQuestion[]>(() =>
    Object.values(allQuestions.value)
        .flat()
        .filter(
            (question) =>
                question.sort_index > currentQuestion.value.sort_index,
        )
        .slice(0, 5),
)

const nextSection = computed<ClerkingSection>(
    () =>
        sections.value.find(
            (section) => section.pivot.order > currentSection.value.pivot.order,
        ) as ClerkingSection,
)

const previousSection = computed<ClerkingSection>(
    () =>
        sections.value.find(
            (section) =>
                section.pivot.order === currentSection.value.pivot.order - 1,
        ) as ClerkingSection,
)
const nextSectionQuestions = computed<UnitQuestion[]>(
    () => allQuestions.value[`sn-${nextSection.value?.id}`] ?? [],
)
const previousSectionQuestions = computed(
    () => allQuestions.value[`sn-${previousSection.value?.id}`] ?? [],
)
const answers = ref<Answer[]>(page.props.answeredQuestions as Answer[])
const isEmpty = (value: string | string[] | null): boolean =>
    value === null ||
    (typeof value === 'string' ? value.trim() === '' : value.length === 0)

const isPaused = ref(clerking.value.started_at === null)
provide('isPaused', isPaused)

const { syncClerking } = useSyncClerking(clerking)

const handleNext = (answer: string | string[]) => {
    if (isPaused.value) return
    if (
        (!currentQuestion.value.pivot ||
            currentQuestion.value.pivot.is_required) &&
        isEmpty(answer)
    ) {
        toast.error('Please, answer the question.')
        return
    }

    const answeredQuestion = currentQuestion.value
    const answerPayload = recordAnswerLocally(
        answer,
        answers,
        currentSection,
        currentQuestion,
    )

    const nextSection = getNextSection(sections.value, currentSection.value)
    const isLastQuestion = currentQuestionIndex.value === questions.value.length

    if (isLastQuestion && nextSectionQuestions.value.length > 0) {
        transitionToSection(nextSection, nextSectionQuestions.value, {
            direction,
            currentQuestionIndex,
            questions,
            currentSection,
            nextSectionQuestions,
            previousSectionQuestions,
            processing,
            isPaused,
        })

        syncClerking({
            ...answerPayload,
            current_question_index: 1,
            question_history: [],
            current_section_id: nextSection.id,
        })
        return
    }

    if (isLastQuestion && nextSectionQuestions.value.length === 0) {
        syncClerking(answerPayload)
        router.post(complete(clerking.value.session_id))
        return
    }

    if (currentQuestionIndex.value < questions.value.length) {
        direction.value = 1

        const answeredQuestionId = answeredQuestion.id
        questionHistory.value.push(currentQuestionIndex.value)

        let stop = false
        while (currentQuestionIndex.value <= questions.value.length) {
            const nextQuestion = questions.value[currentQuestionIndex.value]
            if (!nextQuestion) break
            if (
                (nextQuestion.depends_on_section_question_id ===
                    answeredQuestionId ||
                    nextQuestion.depends_on_complaint_question_id ===
                        answeredQuestionId) &&
                answer != nextQuestion.depends_on_answer
            ) {
                const nextIndex = currentQuestionIndex.value + 1
                if (nextIndex === questions.value.length) {
                    stop = true
                    break
                } else {
                    currentQuestionIndex.value++
                }
            } else {
                break
            }
        }

        if (stop) {
            if (nextSectionQuestions.value.length > 0) {
                transitionToSection(nextSection, nextSectionQuestions.value, {
                    direction,
                    currentQuestionIndex,
                    questions,
                    currentSection,
                    nextSectionQuestions,
                    previousSectionQuestions,
                    processing,
                    isPaused,
                })

                syncClerking({
                    ...answerPayload,
                    current_question_index: 1,
                    question_history: [],
                    current_section_id: nextSection.id,
                })
            } else {
                syncClerking(answerPayload)
                router.post(complete(clerking.value.session_id))
            }
            return
        }

        currentQuestionIndex.value++
        syncClerking({
            ...answerPayload,
            current_question_index: currentQuestionIndex.value,
            question_history: questionHistory.value,
        })
    }
}

const handlePrevious = () => {
    if (isPaused.value) return
    if (currentSection.value.id === 1 && currentQuestionIndex.value === 1)
        return

    if (currentQuestionIndex.value === 1) {
        const prevSection = getPreviousSection(
            sections.value,
            currentSection.value,
        )
        questions.value = previousSectionQuestions.value
        currentSection.value = prevSection
        const historyIndex = questionHistory.value.pop()
        currentQuestionIndex.value =
            historyIndex !== undefined && historyIndex <= questions.value.length
                ? historyIndex
                : questions.value.length

        syncClerking({
            current_question_index: currentQuestionIndex.value,
            question_history: questionHistory.value,
            current_section_id: prevSection.id,
        })
        return
    }

    if (currentQuestionIndex.value > 1) {
        direction.value = -1
        const prevIndex = questionHistory.value.pop() ?? 1
        currentQuestionIndex.value = prevIndex

        syncClerking({
            current_question_index: prevIndex,
            question_history: questionHistory.value,
        })
        return
    }
}

if (typeof window !== 'undefined') {
    useEcho(
        `clerking.${clerking.value.session_id}`,
        '.section.questions.ready',
        ({ valid, previousSent }) => {
            isPaused.value = false
            if (!valid && !previousSent) {
                undoAllQuestions()
                handlePrevious()
                processing.value = false
                toast.error(noQuestionError)
                return
            }

            router.reload({
                viewTransition: false,
                onSuccess: ({ props }) => {
                    const updatedClerking = props.clerking as Clerking
                    clerking.value = updatedClerking
                    allQuestions.value = clerking.value.all_questions
                    questions.value =
                        updatedClerking.all_questions[
                            `sn-${currentSection.value.id}`
                        ]
                    processing.value = false
                },
            })
        },
    )
}

if (typeof window !== 'undefined') {
    window.addEventListener('questions-exists', (event: Event) => {
        allQuestions.value = (event as CustomEvent<SectionQuestions>).detail
        questions.value = allQuestions.value[`sn-${currentSection.value.id}`]
        processing.value = false
        isPaused.value = false
    })
}
</script>

<template>
    <Head :title="`${unit.name} Clerking`" />

    <div class="col-span-1 lg:col-span-2">
        <div
            class="grid grid-cols-1 items-start gap-5 md:gap-6 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_340px] xl:gap-8"
        >
            <div class="flex flex-col gap-6 max-md:gap-4">
                <TopBar
                    :clerking="clerking"
                    :processing="processing"
                />

                <div class="relative overflow-hidden">
                    <AnimatePresence
                        mode="popLayout"
                        :initial="false"
                    >
                        <Motion
                            :key="`${currentSection.id}-${currentQuestionIndex}`"
                            :initial="{
                                x: direction > 0 ? 40 : -40,
                                opacity: 0,
                            }"
                            :animate="{
                                x: 0,
                                opacity: 1,
                            }"
                            :exit="{
                                x: direction > 0 ? -20 : 20,
                                opacity: 0,
                            }"
                            :transition="{
                                x: {
                                    type: 'spring',
                                    stiffness: 600,
                                    damping: 40,
                                },
                                opacity: { duration: 0.15 },
                            }"
                        >
                            <ProcessingBox v-if="processing" />

                            <ClerkBox
                                v-else
                                :clerking="clerking"
                                :questions="questions"
                                :current-section="currentSection"
                                :current-question-index="currentQuestionIndex"
                                :is-last-question="
                                    currentQuestionIndex === questions.length &&
                                    !nextSectionQuestions.length
                                "
                                :is-paused="isPaused"
                                @next="handleNext"
                                @previous="handlePrevious"
                                :answers="answers"
                            />
                        </Motion>
                    </AnimatePresence>
                </div>

                <div
                    class="flex items-center gap-4 border-t border-white/3 px-3 pt-4"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 bg-white/3 text-brand-gray"
                    >
                        <Layout :size="18" />
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="text-[10px] font-bold tracking-[0.25em] text-brand-gray uppercase"
                            >Current Unit</span
                        >
                        <div
                            class="rounded-full border border-white/5 bg-white/3 px-3 py-1"
                        >
                            <span
                                class="text-xs font-extrabold tracking-wide text-white"
                                >{{ unit.name }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <aside class="lg:sticky lg:top-6">
                <NextQuestions
                    :next-questions="nextQuestions"
                    :processing="processing"
                />
            </aside>
        </div>
    </div>
</template>
