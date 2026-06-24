<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { ref } from 'vue'
import { ArrowLeftCircle } from '@lucide/vue'
import {
    ClerkingSection,
    ComplaintTemplate,
    UnitQuestion,
} from '@/types/dashboard'
import { index as sectionQuestionsIndex } from '@/routes/section-questions'
import { destroy } from '@/routes/section-questions/question'
import ConfirmDialog from '@/components/dashboard/ConfirmDialog.vue'
import Alert from '@/components/Alert.vue'
import Sidebar from '@/components/section-questions/SectionSidebar.vue'
import QuestionCard from '@/components/section-questions/SectionQuestionCard.vue'
import { toast } from 'vue-sonner'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    template: { id: number; name: string; slug: string }
    sections: Record<string, UnitQuestion[]>
    canContribute: boolean
    clerkingSections: ClerkingSection[]
}>()

const confirmDelete = ref<UnitQuestion | null>(null)

const deleteQuestion = (question: UnitQuestion) =>
    (confirmDelete.value = question)

const confirmDeleteQuestion = () => {
    if (!confirmDelete.value) return

    router.delete(
        destroy({
            template: props.template.id,
            question: confirmDelete.value.id,
        }),
        {
            preserveScroll: true,
            onFinish: () => {
                confirmDelete.value = null
                toast.success('Question removed from template.')
            },
        },
    )
}

const editingQuestion = ref<UnitQuestion | null>(null)

const startEdit = (question: UnitQuestion) => {
    editingQuestion.value = question
}

const closeEdit = () => {
    editingQuestion.value = null
}
</script>

<template>
    <Head :title="`Edit: ${template.name}`" />

    <div class="col-span-1 lg:col-span-2">
        <div class="col-span-2 flex w-full flex-col gap-5 py-8">
            <div class="flex items-center justify-between">
                <Link
                    :href="sectionQuestionsIndex()"
                    prefetch
                    class="flex w-fit items-center gap-1.5 font-semibold text-brand-gray transition-colors hover:text-neutral-50"
                >
                    <ArrowLeftCircle :size="25" />
                    Back to templates
                </Link>
            </div>
            <div :class="[glass, 'p-6']">
                <div class="flex items-center justify-between">
                    <h1
                        class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
                    >
                        Refining {{ template.name }}
                    </h1>
                </div>

                <p class="mt-1 text-[13px] leading-relaxed text-brand-gray">
                    Manage reusable section questions for this template. Add,
                    reorder, or refine questions to shape the clerking flow.
                </p>
            </div>

            <div
                class="relative grid grid-cols-1 gap-6 lg:grid-cols-4 lg:items-start"
            >
                <div class="lg:col-span-2">
                    <Alert
                        v-if="!canContribute"
                        message="You cannot make contributions at this time."
                        type="warning"
                    />

                    <div class="flex flex-col gap-3">
                        <div
                            v-for="(questions, sectionName) in sections"
                            :key="sectionName"
                            :class="[glass, 'overflow-hidden']"
                        >
                            <QuestionCard
                                @delete-question="deleteQuestion($event)"
                                @edit-question="startEdit($event)"
                                :section-name="sectionName"
                                :questions="questions"
                                :template="template"
                                :sections="sections"
                                :clerking-sections="clerkingSections"
                            />
                        </div>
                    </div>
                </div>

                <Sidebar
                    :sections="sections"
                    :template="template"
                    :clerking-sections="clerkingSections"
                    :editing-question="editingQuestion"
                    @close-edit="closeEdit"
                />
            </div>
        </div>

        <ConfirmDialog
            :open="confirmDelete !== null"
            title="Remove question"
            :message="`Are you sure you want to remove &quot;${confirmDelete?.question}&quot; from this template?`"
            @confirm="confirmDeleteQuestion"
            @cancel="confirmDelete = null"
        />
    </div>
</template>
