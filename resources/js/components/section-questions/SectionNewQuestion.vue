<script lang="ts" setup>
import { store, update } from '@/routes/section-questions/question'
import type {
    ClerkingSection,
    InputType,
    UnitQuestion,
} from '@/types/dashboard'
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { Check, X } from '@lucide/vue'
import Select from '../form/Select.vue'
import Input from '../form/Input.vue'
import { INPUT_TYPES, sexOptions } from '@/data/constants.js'
import QuestionPreview from '../contribute/QuestionPreview.vue'
import DependsOnAnswer from '../contribute/DependsOnAnswer.vue'
import { toast } from 'vue-sonner'

const props = defineProps<{
    template: { id: number; name: string; slug: string }
    clerkingSections: ClerkingSection[]
    sections: Record<string, UnitQuestion[]>
    editingQuestion: UnitQuestion | null
}>()

const emit = defineEmits<{
    (e: 'close-edit'): void
}>()

const sectionId = ref<string>()
const questionText = ref('')
const inputType = ref<InputType | ''>('')
const dependsOnQuestionId = ref<string>('')
const dependsOnAnswer = ref('')
const sex = ref<'male' | 'female' | 'both'>('both')
const minimumAge = ref<number | null>()
const maximumAge = ref<number | null>()

const questions = computed(() => {
    if (!sectionId.value) return []

    const currentSection = props.clerkingSections.find(
        (section) => String(section.id) == sectionId.value,
    )
    if (!currentSection) return []
    return props.sections[currentSection.name] ?? []
})

const dependentQuestion = computed(() =>
    questions.value.find(
        (question) => String(question.id) === dependsOnQuestionId.value,
    ),
)

const selectOptions = ref<string[]>([])
const scaleMin = ref(0)
const scaleMax = ref(10)
const scaleMinLabel = ref('')
const scaleMaxLabel = ref('')
const boolTrueLabel = ref('Yes')
const boolFalseLabel = ref('No')
const kvPairs = ref<{ key: string; value: string }[]>([{ key: '', value: '' }])

const needsOptions = computed(() =>
    ['select', 'multi_select'].includes(inputType.value),
)
const isScale = computed(() => inputType.value === 'scale')

const hasTypeConfig = computed(() => needsOptions.value || isScale.value)

const isEditing = computed(() => props.editingQuestion !== null)

const canSave = computed(
    () => questionText.value.trim() && sectionId.value && inputType.value,
)

const addOption = () => selectOptions.value.push('')
const removeOption = (i: number) => selectOptions.value.splice(i, 1)

const dependsOnQuestionOrder = computed(() => {
    if (!dependsOnQuestionId.value) return null
    const dep = questions.value.find(
        (q) => String(q.id) === dependsOnQuestionId.value,
    )
    return dep?.order ?? null
})

const suggestedOrder = computed(() => {
    if (!isEditing.value || !dependsOnQuestionOrder.value) return null
    const editingOrder = props.editingQuestion!.order
    const depOrder = dependsOnQuestionOrder.value
    if (depOrder < editingOrder) {
        return depOrder + 1
    }
    return editingOrder
})

const buildTypeConfig = () => {
    if (needsOptions.value)
        return {
            options: selectOptions.value.filter(Boolean),
        }

    if (isScale.value) return [scaleMin.value, scaleMax.value]

    return null
}

watch(
    () => props.editingQuestion,
    (question) => {
        if (!question) return

        sectionId.value = String(question.clerking_section_id)
        questionText.value = question.question
        inputType.value = question.input_type
        sex.value = question.sex ?? 'both'
        minimumAge.value = question.minimum_age ?? null
        maximumAge.value = question.maximum_age ?? null
        dependsOnQuestionId.value = question.depends_on_section_question_id
            ? String(question.depends_on_section_question_id)
            : ''
        dependsOnAnswer.value = question.depends_on_answer ?? ''

        if (question.options) {
            selectOptions.value = Object.values(question.options)
        } else {
            selectOptions.value = []
        }
    },
    { immediate: true },
)

const saveQuestion = () => {
    if (!canSave.value) return

    const payload = {
        template_id: props.template.id,
        section_id: sectionId.value,
        question: questionText.value.trim(),
        input_type: inputType.value,
        depends_on_question_id: dependsOnQuestionId.value || null,
        depends_on_answer: dependsOnAnswer.value || null,
        order: isEditing.value
            ? (suggestedOrder.value ?? props.editingQuestion!.order)
            : 1,
        config: buildTypeConfig(),
        sex: sex.value,
        minimum_age: minimumAge.value,
        maximum_age: maximumAge.value,
    }

    if (isEditing.value) {
        router.patch(update(props.editingQuestion!.id), payload, {
            preserveScroll: true,
            onSuccess: ({ props }) => {
                emit('close-edit')
                cancelAdd()
                if (props.canContribute)
                    toast.success('Question updated successfully.')
            },
        })
    } else {
        router.post(store(props.template.id), payload, {
            preserveScroll: true,
            onSuccess: () => {
                cancelAdd()
                toast.success('Question added successfully.')
            },
        })
    }
}

const cancelAdd = () => {
    sectionId.value = ''
    questionText.value = ''
    inputType.value = ''
    dependsOnQuestionId.value = ''
    dependsOnAnswer.value = ''
    selectOptions.value = []
    scaleMin.value = 0
    scaleMax.value = 10
    scaleMinLabel.value = ''
    scaleMaxLabel.value = ''
    boolTrueLabel.value = 'Yes'
    boolFalseLabel.value = 'No'
    kvPairs.value = [{ key: '', value: '' }]
    minimumAge.value = null
    maximumAge.value = null
    sex.value = 'both'
    if (isEditing.value) emit('close-edit')
}
</script>

<template>
    <div class="rounded-xl border border-brand-border p-5">
        <div class="flex flex-col gap-4">
            <Select
                v-model="sectionId"
                label="Section"
                placeholder="Select section"
                :options="
                    clerkingSections.map((s) => ({
                        label: s.name,
                        value: String(s.id),
                    }))
                "
            />

            <Select
                v-model="inputType"
                label="Input type"
                placeholder="Select type"
                :options="INPUT_TYPES"
            />

            <Input
                v-model="questionText"
                label="Question text"
                placeholder="e.g. Where exactly is the pain located?"
                @keydown.escape="cancelAdd()"
            />

            <Select
                v-model="dependsOnQuestionId"
                label="Depends on question"
                placeholder="None (always shown)"
                :options="
                    (questions ?? []).map((q) => ({
                        label: q.question,
                        value: String(q.id),
                    }))
                "
            />

            <QuestionPreview
                v-if="dependentQuestion"
                :dependent-question="dependentQuestion"
                :depends-on-answer="dependsOnAnswer"
            />

            <DependsOnAnswer
                v-if="dependsOnQuestionId && dependentQuestion"
                v-model="dependsOnAnswer"
                :question="dependentQuestion"
            />

            <template v-if="hasTypeConfig">
                <div class="border-t border-brand-border" />

                <template v-if="needsOptions">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-[11px] font-semibold tracking-widest text-brand-yellow/50 uppercase"
                            >Options</span
                        >
                        <span
                            class="rounded-full border border-brand-yellow/20 bg-brand-yellow/8 px-2.5 py-0.5 text-[11px] font-semibold text-brand-yellow"
                            >{{ inputType }}</span
                        >
                    </div>
                    <div class="flex flex-col gap-2">
                        <div
                            v-for="(_, index) in selectOptions"
                            :key="index"
                            class="flex items-center gap-2"
                        >
                            <input
                                v-model="selectOptions[index]"
                                placeholder="e.g. Mild"
                                class="min-w-0 flex-1 rounded-lg border border-brand-border bg-brand-bg px-4 py-3 font-mono text-sm text-white outline-none placeholder:text-white/20 focus:border-brand-yellow focus:ring-1 focus:ring-brand-yellow"
                            />
                            <button
                                @click="removeOption(index)"
                                :disabled="selectOptions.length === 1"
                                class="rounded-lg border border-red-500/15 bg-red-500/8 p-2.5 text-red-400 transition-colors hover:bg-red-500/15 disabled:opacity-20"
                            >
                                <X :size="14" />
                            </button>
                        </div>
                    </div>
                    <button
                        @click="addOption"
                        class="w-full rounded-lg border border-dashed border-white/12 py-2 text-xs text-brand-gray-light transition-colors hover:border-white/20 hover:bg-white/4"
                    >
                        + Add option
                    </button>
                </template>

                <template v-else-if="isScale">
                    <div class="flex items-center justify-between">
                        <span
                            class="text-[11px] font-semibold tracking-widest text-brand-yellow/50 uppercase"
                            >Scale range</span
                        >
                        <span
                            class="rounded-full border border-brand-yellow/20 bg-brand-yellow/8 px-2.5 py-0.5 text-[11px] font-semibold text-brand-yellow"
                            >scale</span
                        >
                    </div>
                    <div class="flex justify-between gap-2">
                        <Input
                            v-model.number="scaleMin"
                            label="Min value"
                            placeholder="0"
                            type="number"
                        />
                        <Input
                            v-model.number="scaleMax"
                            label="Max value"
                            placeholder="10"
                            type="number"
                        />
                    </div>
                </template>
            </template>

            <Select
                label="Sex"
                :options="sexOptions"
                v-model="sex"
                placeholder="Sex-specific question"
            />
            <p class="text-[11px]">
                Questions can be restricted by sex.
                <code
                    class="bg-muted text-muted-foreground rounded px-1.5 py-0.5 font-mono text-xs"
                    >Both</code
                >
                indicates the question is relevant for all patients regardless
                of sex.
            </p>

            <hr class="border-gray-700" />
            <div class="flex justify-between gap-2">
                <div>
                    <Input
                        v-model="minimumAge"
                        label="Minimum age"
                        placeholder="Optional"
                    />
                    <p class="text-[11px]">
                        Leave empty for questions that apply for all ages
                    </p>
                </div>
                <div>
                    <Input
                        v-model="maximumAge"
                        label="Maximum age"
                        placeholder="Optional"
                    />
                    <p class="text-[11px]">
                        Leave empty for questions that apply for all ages
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    @click="cancelAdd()"
                    class="rounded-lg border border-brand-border px-4 py-2 text-sm text-brand-gray-light transition-colors hover:bg-white/4"
                >
                    {{ isEditing ? 'Cancel' : 'Cancel' }}
                </button>
                <button
                    @click="saveQuestion()"
                    :disabled="!canSave"
                    class="flex items-center gap-1.5 rounded-lg bg-brand-yellow px-4 py-2 text-sm font-bold text-brand-bg transition-opacity hover:opacity-85 disabled:opacity-30"
                >
                    <Check :size="14" />
                    {{ isEditing ? 'Update question' : 'Save question' }}
                </button>
            </div>
        </div>
    </div>
</template>
