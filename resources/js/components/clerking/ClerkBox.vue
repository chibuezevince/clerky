<script setup lang="ts">
import { glass } from '@/data/dashboard.js'
import { Answer, ClerkingSection, UnitQuestion } from '@/types/dashboard'
import { Check, ChevronLeft, ChevronRight } from '@lucide/vue'
import Input from '../form/Input.vue'
import type { Component } from 'vue'
import { computed, ref, watch } from 'vue'
import Select from '../form/Select.vue'
import Textarea from '../form/Textarea.vue'
import MultiSelect from '../form/MultiSelect.vue'
import Checkbox from '../form/Checkbox.vue'
import { Motion } from 'motion-v'
import type { Clerking } from '@/types/dashboard'
import NumberInput from '../form/NumberInput.vue'
import KeyValueInput from '../form/KeyValueInput.vue'

const props = defineProps<{
    clerking: Clerking
    questions: UnitQuestion[]
    currentSection: ClerkingSection
    currentQuestionIndex: number
    answers: Answer[]
    isLastQuestion?: boolean
    isPaused?: boolean
}>()

const emit = defineEmits(['next', 'previous'])

const componentMap = {
    text: Textarea,
    select: Select,
    scale: Input,
    textarea: Textarea,
    boolean: Checkbox,
    multi_select: MultiSelect,
    number: NumberInput,
    key_value: KeyValueInput,
}

const inputComponent = computed<Component>(
    () =>
        componentMap[
            props.questions[props.currentQuestionIndex - 1].input_type
        ],
)

const answer = ref<string[] | string>('')
const options = computed(() => {
    const question = props.questions[props.currentQuestionIndex - 1]
    if (!question.options) return null

    return Object.entries(question.options).map(([value, label]) => ({
        value,
        label,
    }))
})

const question = computed(() => props.questions[props.currentQuestionIndex - 1])
const inputProps = computed(() => {
    const q = question.value
    const extra: Record<string, unknown> = {}

    if (q.input_type === 'key_value') {
        extra.placeholder = { key: 'Complaint', value: 'When it started' }
        extra.labels = { key: 'Complaint', value: 'When it started' }
    }

    if (q.max_char > 0) {
        if (q.input_type === 'number') {
            extra.max = q.max_char
            extra.maxlength = q.max_char
        }

        if (['text', 'textarea', 'scale'].includes(q.input_type)) {
            extra.maxlength = q.max_char
        }
    }

    return extra
})

const isOptional = computed(
    () => question.value.pivot && !question.value.pivot.is_required,
)

watch(
    () => [props.currentQuestionIndex, props.questions],
    () => {
        if (props.currentQuestionIndex < 1) return
        const currentQuestion = props.questions[props.currentQuestionIndex - 1]
        const isComplaintQuestion =
            currentQuestion.complaint_template_id !== undefined
        const currentAnswer = props.answers.find(
            (answer) =>
                answer.clerking_section_id === props.currentSection.id &&
                (isComplaintQuestion
                    ? answer.complaint_question_id === currentQuestion.id
                    : answer.section_question_id === currentQuestion.id),
        )
        answer.value = currentAnswer ? currentAnswer.value : ''
    },
    { immediate: true, deep: true },
)
</script>

<template>
    <div
        :class="[
            glass,
            'flex min-h-110 flex-col gap-8 p-6 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] md:p-8 xl:min-h-125 xl:gap-10 xl:p-10',
        ]"
    >
        <div class="flex items-center justify-between">
            <div
                class="flex flex-col items-start gap-3 md:flex-row md:items-center md:gap-5"
            >
                <span
                    class="text-sm font-bold tracking-tight whitespace-nowrap text-brand-gray max-md:text-xs"
                    >Question {{ currentQuestionIndex }} of
                    {{ questions.length }}</span
                >
                <div
                    class="h-2 w-32 shrink-0 overflow-hidden rounded-full border border-white/5 bg-white/3 md:w-40"
                >
                    <Motion
                        class="h-full bg-linear-to-r from-brand-yellow/80 to-brand-yellow shadow-[0_0_15px_rgba(244,253,59,0.4)]"
                        :initial="{ width: 0 }"
                        :animate="{
                            width: `${(currentQuestionIndex / questions.length) * 100}%`,
                        }"
                        :transition="{
                            type: 'spring',
                            stiffness: 100,
                            damping: 15,
                        }"
                    />
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-2.5">
                <Motion
                    :while-hover="{ scale: 1.05 }"
                    class="flex max-w-35 items-center gap-2 rounded-xl border border-brand-yellow/20 bg-brand-yellow/10 px-3 py-1.5 md:max-w-none md:border-white/5 md:bg-white/3"
                >
                    <span
                        class="truncate text-xs font-extrabold tracking-wider text-brand-yellow uppercase md:text-white"
                        >{{ currentSection.name }}</span
                    >
                </Motion>
            </div>
        </div>

        <Motion
            :initial="{ opacity: 0, y: 20, filter: 'blur(10px)' }"
            :animate="{ opacity: 1, y: 0, filter: 'blur(0px)' }"
            :transition="{ delay: 0.1, duration: 0.5 }"
            class="flex flex-col gap-3"
        >
            <h1
                class="text-2xl leading-[1.1] font-extrabold tracking-tight text-white md:text-3xl xl:text-4xl"
            >
                {{ questions[currentQuestionIndex - 1].question }}
            </h1>
            <span
                v-if="isOptional"
                class="text-xs font-bold tracking-wider text-brand-gray/60 uppercase"
                >(optional)</span
            >
        </Motion>

        <Motion
            :initial="{ opacity: 0, scale: 0.95, filter: 'blur(10px)' }"
            :animate="{ opacity: 1, scale: 1, filter: 'blur(0px)' }"
            :transition="{ delay: 0.2, duration: 0.5 }"
            class="group/input relative flex-1"
        >
            <component
                :is="inputComponent"
                v-model="answer"
                :options="options"
                v-bind="inputProps"
                required
            />
        </Motion>

        <Motion
            :initial="{ opacity: 0, y: 20 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{ delay: 0.3, duration: 0.4 }"
            class="flex items-center justify-between gap-4 border-t border-white/5 pt-4"
        >
            <button
                @click="emit('previous')"
                :disabled="isPaused"
                class="group/prev flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-6 py-3.5 text-xs font-bold text-white transition-all hover:border-white/20 hover:bg-white/10 active:scale-95 disabled:cursor-not-allowed disabled:opacity-40"
            >
                <ChevronLeft
                    :size="16"
                    class="transition-transform group-hover/prev:-translate-x-1"
                />
                Previous
            </button>
            <button
                @click="emit('next', answer)"
                :disabled="isPaused"
                class="group/next flex items-center gap-3 rounded-2xl bg-brand-yellow px-8 py-3.5 text-xs font-black text-black shadow-[0_12px_24px_rgba(244,253,59,0.15)] transition-all hover:scale-[1.02] hover:opacity-90 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-40"
            >
                {{ isLastQuestion ? 'Conclude' : 'Next' }}
                <ChevronRight
                    v-if="!isLastQuestion"
                    :size="18"
                    class="transition-transform group-hover/next:translate-x-1"
                />
                <Check
                    v-else
                    :size="18"
                    class="transition-transform group-hover/next:scale-110"
                />
            </button>
        </Motion>
    </div>
</template>
