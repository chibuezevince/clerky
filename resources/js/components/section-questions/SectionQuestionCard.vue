<script setup lang="ts">
import { reorder, updateDependency } from '@/routes/section-questions/question'
import {
    ClerkingSection,
    ComplaintTemplate,
    UnitQuestion,
} from '@/types/dashboard'
import { router } from '@inertiajs/vue3'
import { computed, nextTick, ref } from 'vue'
import { inputTypeLabels } from '@/data/constants'
import { ChevronDown, GripVertical, Pencil, Trash2 } from '@lucide/vue'

const props = defineProps<{
    sectionName: string
    sections: Record<string, UnitQuestion[]>
    questions: UnitQuestion[]
    template: { id: number; name: string; slug: string }
    clerkingSections: ClerkingSection[]
}>()

defineEmits<{
    (e: 'delete-question', question: UnitQuestion): void
    (e: 'edit-question', question: UnitQuestion): void
}>()

const toggleSection = (name: string) => {
    expandedSections.value.has(name)
        ? expandedSections.value.delete(name)
        : expandedSections.value.add(name)
}

const expandedSections = ref<Set<string>>(new Set(Object.keys(props.sections)))

const draggedQuestion = ref<UnitQuestion | null>(null)
const dragOverId = ref<number | null>(null)
const dragStartX = ref(0)
const currentX = ref(0)
const dragMode = ref<'reorder' | 'depend' | 'undepend' | null>(null)

const DEPEND_THRESHOLD = 80

const editingAnswerId = ref<number | null>(null)
const answerValue = ref<string>('')

const questionById = computed(() =>
    props.questions.reduce(
        (acc, q) => {
            acc[q.id] = q
            return acc
        },
        {} as Record<number, UnitQuestion>,
    ),
)

const startAnswerEdit = (question: UnitQuestion) => {
    editingAnswerId.value = question.id
    answerValue.value = question.depends_on_answer ?? ''
}

const cancelAnswerEdit = () => {
    editingAnswerId.value = null
    answerValue.value = ''
}

const saveAnswer = (question: UnitQuestion) => {
    router.patch(
        updateDependency(question.id),
        { depends_on_answer: answerValue.value || null },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['sections'],
            onSuccess: () => {
                const section = props.sections[props.sectionName]
                const q = section.find((q) => q.id === question.id)
                if (q) q.depends_on_answer = answerValue.value || null
                cancelAnswerEdit()
            },
        },
    )
}

const selectAnswer = (question: UnitQuestion, value: string) => {
    answerValue.value = value
    saveAnswer(question)
}

const onMouseMove = (e: MouseEvent) => {
    currentX.value = e.clientX
    const delta = currentX.value - dragStartX.value
    if (delta > DEPEND_THRESHOLD) dragMode.value = 'depend'
    else if (delta < -DEPEND_THRESHOLD) dragMode.value = 'undepend'
    else dragMode.value = 'reorder'
}

const onDragStart = (e: DragEvent, question: UnitQuestion) => {
    draggedQuestion.value = question
    dragStartX.value = e.clientX
    dragMode.value = null
}

const onDragOver = (e: DragEvent, q: UnitQuestion) => {
    e.preventDefault()
    dragOverId.value = q.id

    const delta = e.clientX - dragStartX.value
    if (delta > DEPEND_THRESHOLD) dragMode.value = 'depend'
    else if (delta < -DEPEND_THRESHOLD) dragMode.value = 'undepend'
    else dragMode.value = 'reorder'
}

const onDrop = (
    e: DragEvent,
    targetSection: string,
    targetQuestion: UnitQuestion,
) => {
    e.preventDefault()
    if (!draggedQuestion.value) return

    const section = props.sections[targetSection]
    const draggedIdx = section.findIndex(
        (q) => q.id === draggedQuestion.value!.id,
    )

    if (dragMode.value === 'depend') {
        const precedingQuestion = section[draggedIdx - 1]
        if (!precedingQuestion) return

        router.patch(
            updateDependency(draggedQuestion.value.id),
            { depends_on_section_question_id: precedingQuestion.id },
            { preserveState: true, preserveScroll: true },
        )

        const q = section.find((q) => q.id === draggedQuestion.value!.id)
        if (q) {
            q.depends_on_section_question_id = precedingQuestion.id
            nextTick(() => startAnswerEdit(q))
        }
    } else if (dragMode.value === 'undepend') {
        router.patch(
            updateDependency(draggedQuestion.value.id),
            { depends_on_section_question_id: null, depends_on_answer: null },
            { preserveState: true, preserveScroll: true },
        )

        const q = section.find((q) => q.id === draggedQuestion.value!.id)
        if (q) {
            q.depends_on_section_question_id = null
            q.depends_on_answer = null
        }
    } else {
        if (draggedQuestion.value.id === targetQuestion.id) return

        const targetIdx = section.findIndex((q) => q.id === targetQuestion.id)
        if (draggedIdx < 0 || targetIdx < 0) return

        const updated = [...section]
        const [moved] = updated.splice(draggedIdx, 1)
        updated.splice(targetIdx, 0, moved)
        props.sections[targetSection] = updated

        const orderedIds = updated.map((q, i) => ({ id: q.id, order: i + 1 }))
        router.patch(
            reorder(),
            { template_id: props.template.id, questions: orderedIds },
            { preserveState: true, preserveScroll: true },
        )
    }

    draggedQuestion.value = null
    dragOverId.value = null
    dragMode.value = null
}

const onContainerDrop = (e: DragEvent) => {
    e.preventDefault()
    if (!draggedQuestion.value) return

    if (dragMode.value === 'undepend') {
        const section = props.sections[props.sectionName]
        router.patch(
            updateDependency(draggedQuestion.value.id),
            { depends_on_section_question_id: null, depends_on_answer: null },
            { preserveState: true, preserveScroll: true, only: ['sections'] },
        )
        const q = section.find((q) => q.id === draggedQuestion.value!.id)
        if (q) {
            q.depends_on_section_question_id = null
            q.depends_on_answer = null
        }

        draggedQuestion.value = null
        dragOverId.value = null
        dragMode.value = null
    }
}

const onDragEnd = () => {
    draggedQuestion.value = null
    dragOverId.value = null
    dragMode.value = null
    window.removeEventListener('mousemove', onMouseMove)
}
</script>

<template>
    <button
        @click="toggleSection(sectionName)"
        class="flex w-full items-center justify-between px-5 py-4 text-left transition-colors hover:bg-white/2"
    >
        <div class="flex items-center gap-3">
            <ChevronDown
                :size="16"
                class="text-brand-gray transition-transform"
                :class="
                    expandedSections.has(sectionName)
                        ? 'rotate-0'
                        : '-rotate-90'
                "
            />
            <span class="text-[14px] font-bold text-white">{{
                sectionName
            }}</span>
            <span
                class="rounded-full bg-white/5 px-2 py-0.5 text-[10px] font-semibold text-white/40"
            >
                {{ questions.length }}
            </span>
        </div>
    </button>

    <div
        v-if="expandedSections.has(sectionName)"
        class="divide-y divide-white/5 border-t border-white/5"
        @dragover.prevent
        @drop="(e: DragEvent) => onContainerDrop(e)"
    >
        <div
            v-if="draggedQuestion"
            class="flex items-center justify-center gap-6 px-5 py-2 text-[11px] transition-all"
        >
            <span
                :class="
                    dragMode === 'undepend' ? 'text-red-400' : 'text-white/20'
                "
            >
                ← drag left to remove dependency
            </span>
            <span
                :class="
                    dragMode === 'reorder' ? 'text-white/60' : 'text-white/20'
                "
            >
                ↕ reorder
            </span>
            <span
                :class="
                    dragMode === 'depend'
                        ? 'text-brand-yellow'
                        : 'text-white/20'
                "
            >
                drag right to make child →
            </span>
        </div>

        <div
            v-for="(question, index) in questions"
            :key="question.id"
            class="flex cursor-grab flex-col gap-3 py-3 pr-5 transition-all duration-150 active:cursor-grabbing sm:flex-row sm:items-start sm:gap-4"
            :class="[
                question.depends_on_section_question_id
                    ? 'ml-6 border-l-2 border-brand-yellow/20 pl-4'
                    : 'pl-5',
                draggedQuestion?.id === question.id
                    ? 'scale-[0.98] opacity-40'
                    : dragOverId === question.id && dragMode === 'depend'
                      ? 'border-t-2 border-brand-yellow/40 bg-brand-yellow/8'
                      : dragOverId === question.id && dragMode === 'undepend'
                        ? 'border-t-2 border-red-500/20 bg-red-500/5'
                        : dragOverId === question.id
                          ? 'border-t-2 border-white/10 bg-white/4'
                          : 'hover:bg-white/2',
            ]"
            draggable="true"
            @dragstart="(e: DragEvent) => onDragStart(e, question)"
            @dragover="(e: DragEvent) => onDragOver(e, question)"
            @drop="(e: DragEvent) => onDrop(e, sectionName, question)"
            @dragend="onDragEnd"
        >
            <div class="flex items-center gap-4 pt-0.5">
                <GripVertical
                    :size="14"
                    class="shrink-0 text-white/15"
                />
                <span
                    class="w-6 text-center text-[11px] font-semibold text-white/30"
                >
                    {{ index + 1 }}
                </span>
            </div>

            <div class="min-w-0 flex-1">
                <p class="truncate font-medium text-white/80">
                    {{ question.question }}
                </p>

                <div
                    v-if="question.depends_on_section_question_id"
                    class="mt-1 flex flex-col gap-1.5"
                >
                    <span
                        class="flex items-center gap-1 text-[10px] text-brand-yellow/50"
                    >
                        <span class="text-brand-yellow/30">↳</span>
                        if
                        <span
                            class="max-w-40 truncate font-medium text-brand-yellow/70"
                        >
                            {{
                                questionById[
                                    question.depends_on_section_question_id
                                ]?.question ?? '—'
                            }}
                        </span>
                    </span>

                    <div
                        v-if="editingAnswerId === question.id"
                        class="flex flex-wrap items-center gap-1.5"
                    >
                        <template
                            v-if="
                                questionById[
                                    question.depends_on_section_question_id
                                ]?.input_type === 'boolean'
                            "
                        >
                            <button
                                v-for="opt in ['Yes', 'No']"
                                :key="opt"
                                @click.stop="selectAnswer(question, opt)"
                                class="rounded-full border px-3 py-0.5 text-[11px] transition-all"
                                :class="
                                    answerValue === opt
                                        ? 'border-brand-yellow/50 bg-brand-yellow/15 text-brand-yellow'
                                        : 'border-white/10 text-white/40 hover:border-white/20 hover:text-white/60'
                                "
                            >
                                {{ opt }}
                            </button>
                        </template>

                        <template
                            v-else-if="
                                ['select', 'multi_select'].includes(
                                    questionById[
                                        question.depends_on_section_question_id
                                    ]?.input_type ?? '',
                                )
                            "
                        >
                            <button
                                v-for="(label, key) in questionById[
                                    question.depends_on_section_question_id
                                ]?.options ?? {}"
                                :key="key"
                                @click.stop="selectAnswer(question, key)"
                                class="rounded-full border px-3 py-0.5 text-[11px] transition-all"
                                :class="
                                    answerValue === key
                                        ? 'border-brand-yellow/50 bg-brand-yellow/15 text-brand-yellow'
                                        : 'border-white/10 text-white/40 hover:border-white/20 hover:text-white/60'
                                "
                            >
                                {{ label }}
                            </button>
                        </template>

                        <template v-else>
                            <input
                                v-model="answerValue"
                                :type="
                                    questionById[
                                        question.depends_on_section_question_id
                                    ]?.input_type === 'number'
                                        ? 'number'
                                        : 'text'
                                "
                                placeholder="answer value..."
                                @keydown.enter.stop="saveAnswer(question)"
                                @keydown.escape.stop="cancelAnswerEdit"
                                class="w-48 rounded-lg border border-brand-yellow/20 bg-white/5 px-2.5 py-1 text-[11px] text-white outline-none focus:border-brand-yellow/40"
                            />
                            <button
                                @click.stop="saveAnswer(question)"
                                class="rounded p-1 text-green-400 transition-colors hover:bg-green-500/10"
                            >
                                <Check :size="12" />
                            </button>
                            <button
                                @click.stop="cancelAnswerEdit"
                                class="text-[11px] text-white/20 hover:text-white/40"
                            >
                                cancel
                            </button>
                        </template>
                    </div>

                    <div
                        v-else
                        class="flex items-center gap-1.5"
                    >
                        <button
                            @click.stop="startAnswerEdit(question)"
                            class="flex items-center gap-1 text-[10px] transition-all"
                            :class="
                                question.depends_on_answer
                                    ? 'text-brand-yellow/60 hover:text-brand-yellow'
                                    : 'text-white/20 hover:text-white/40'
                            "
                        >
                            <span v-if="question.depends_on_answer">
                                when answer is
                                <span
                                    class="font-medium text-brand-yellow/80"
                                    >{{ question.depends_on_answer }}</span
                                >
                            </span>
                            <span v-else>+ set answer condition</span>
                        </button>
                    </div>
                </div>

                <p
                    v-if="
                        question.field_key &&
                        !question.depends_on_section_question_id
                    "
                    class="text-[11px] text-white/25"
                >
                    {{ question.field_key }}
                </p>
            </div>

            <div class="flex items-center gap-2 pt-0.5 sm:gap-4">
                <span
                    class="shrink-0 rounded bg-white/5 px-2 py-0.5 text-[10px] font-medium text-white/40"
                >
                    {{
                        inputTypeLabels[question.input_type] ??
                        question.input_type
                    }}
                </span>
                <button
                    @click="$emit('edit-question', question)"
                    class="shrink-0 rounded p-1 text-white/20 transition-colors hover:bg-white/10 hover:text-white/60"
                >
                    <Pencil :size="14" />
                </button>
                <button
                    @click="$emit('delete-question', question)"
                    class="shrink-0 rounded p-1 text-white/20 transition-colors hover:bg-red-500/10 hover:text-red-400"
                >
                    <Trash2 :size="14" />
                </button>
            </div>
        </div>
    </div>
</template>
