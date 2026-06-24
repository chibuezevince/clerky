<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { ArrowLeft, BadgeCheck, Clock, FileText } from '@lucide/vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { Markdown } from '@tiptap/markdown'
import Underline from '@tiptap/extension-underline'
import { computed, ref } from 'vue'
import type { Clerking, ClerkingUnit, Summary } from '@/types/dashboard'
import { update } from '@/routes/clerking/summary'
import { useDebounceFn } from '@vueuse/core'
import { Link } from '@inertiajs/vue3'
import { dashboard } from '@/routes'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    clerking: Clerking
    unit: ClerkingUnit
    summary: Summary
}>()

const saveStatus = ref<'saved' | 'saving' | 'unsaved'>('saved')
const editor = useEditor({
    content: props.summary.edited_content ?? props.summary.content ?? '',
    extensions: [StarterKit, Underline, Markdown],
    contentType: 'markdown',
    onUpdate: () => {
        saveStatus.value = 'unsaved'
        debouncedSave()
    },
})

const hasEdits = computed(() => props.summary.edited_content !== null)

const toolbarButtons = computed(() => [
    {
        label: 'Bold',
        isActive: isActive('bold'),
        action: () => editor.value?.chain().focus().toggleBold().run(),
    },
    {
        label: 'Italic',
        isActive: isActive('italic'),
        action: () => editor.value?.chain().focus().toggleItalic().run(),
    },
    {
        label: 'Underline',
        isActive: isActive('underline'),
        action: () => editor.value?.chain().focus().toggleUnderline().run(),
    },
    { label: '|', isSeparator: true },
    {
        label: 'H2',
        isActive: isActive('heading', { level: 2 }),
        action: () =>
            editor.value?.chain().focus().toggleHeading({ level: 2 }).run(),
    },
    {
        label: 'H3',
        isActive: isActive('heading', { level: 3 }),
        action: () =>
            editor.value?.chain().focus().toggleHeading({ level: 3 }).run(),
    },
    { label: '|', isSeparator: true },
    {
        label: 'Bullet',
        isActive: isActive('bulletList'),
        action: () => editor.value?.chain().focus().toggleBulletList().run(),
    },
    {
        label: 'Ordered',
        isActive: isActive('orderedList'),
        action: () => editor.value?.chain().focus().toggleOrderedList().run(),
    },
    { label: '|', isSeparator: true },
    {
        label: 'Code',
        isActive: isActive('code'),
        action: () => editor.value?.chain().focus().toggleCode().run(),
    },
    {
        label: 'Quote',
        isActive: isActive('blockquote'),
        action: () => editor.value?.chain().focus().toggleBlockquote().run(),
    },
])

const debouncedSave = useDebounceFn(() => {
    if (!editor.value) return

    saveStatus.value = 'saving'
    router.patch(
        update(props.clerking.session_id),
        { edited_content: editor.value.getMarkdown() },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                saveStatus.value = 'saved'
            },
            onError: () => {
                saveStatus.value = 'unsaved'
            },
        },
    )
}, 2000)

const isActive = (name: string, attrs?: Record<string, unknown>) =>
    editor.value?.isActive(name, attrs) ?? false
</script>

<template>
    <Head title="Edit Summary" />

    <div class="col-span-1 lg:col-span-2">
        <div class="mx-auto max-w-4xl">
            <div
                :class="[
                    glass,
                    'mb-6 flex flex-col items-start gap-4 p-6 md:flex-row md:items-center md:p-8',
                ]"
            >
                <div
                    class="hidden h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white/5 md:flex"
                >
                    <FileText class="h-7 w-7 text-white/40" />
                </div>
                <div class="flex w-full flex-1 flex-col gap-1 md:w-auto">
                    <div
                        class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:gap-3"
                    >
                        <Link
                            :href="dashboard()"
                            prefetch
                            class="flex items-center gap-1 text-xs font-bold text-brand-gray hover:text-neutral-50 md:hidden"
                        >
                            <ArrowLeft :size="14" />
                            Back
                        </Link>
                        <h1
                            class="text-lg font-extrabold tracking-tight text-white md:text-xl"
                        >
                            {{ unit.name }} : {{ clerking.case_number }}
                        </h1>
                        <div class="flex items-center gap-2">
                            <span
                                class="rounded-full bg-white/10 px-3 py-0.5 text-xs font-semibold text-white/50"
                            >
                                AI Summary
                            </span>
                            <span
                                v-if="hasEdits"
                                class="rounded-full bg-brand-yellow/10 px-3 py-0.5 text-xs font-semibold text-brand-yellow"
                            >
                                Edited
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-brand-gray">
                        <Clock class="mr-1 inline h-3.5 w-3.5" />
                        {{ clerking.completed_at }}
                    </p>
                </div>

                <div class="flex items-center gap-3 self-end md:self-center">
                    <span
                        v-if="saveStatus === 'saving'"
                        class="text-xs font-semibold text-brand-yellow"
                    >
                        Saving...
                    </span>
                    <span
                        v-else-if="saveStatus === 'saved'"
                        class="flex items-center gap-1 text-xs font-semibold text-green-400"
                    >
                        <BadgeCheck :size="12" />
                        Saved
                    </span>
                </div>
            </div>

            <div :class="[glass, 'overflow-clip']">
                <div
                    class="sticky top-0 z-10 flex flex-wrap items-center gap-1 rounded-t-3xl border-b border-white/5 bg-brand-surface px-4 py-2 backdrop-blur-2xl backdrop-saturate-150 md:px-6"
                >
                    <template
                        v-for="btn in toolbarButtons"
                        :key="btn.label"
                    >
                        <span
                            v-if="btn.isSeparator"
                            class="mx-1 h-5 w-px bg-white/10"
                        />
                        <button
                            v-else
                            @click="btn.action"
                            :class="[
                                'rounded-lg px-2.5 py-1.5 text-xs font-bold transition-colors',
                                btn.isActive
                                    ? 'bg-brand-yellow/20 text-brand-yellow'
                                    : 'text-brand-gray hover:bg-white/10 hover:text-neutral-50',
                            ]"
                            type="button"
                        >
                            {{ btn.label }}
                        </button>
                    </template>
                </div>

                <div class="rounded-b-3xl px-4 py-6 md:px-6">
                    <EditorContent :editor="editor" />
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.ProseMirror {
    outline: none;
    min-height: 400px;
    color: #f5f5f5;
    font-size: 0.9375rem;
    line-height: 1.75;
}

.ProseMirror h2 {
    font-size: 1.25rem;
    font-weight: 800;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    color: #ffffff;
    letter-spacing: -0.01em;
}

.ProseMirror h3 {
    font-size: 1.0625rem;
    font-weight: 700;
    margin-top: 1.25em;
    margin-bottom: 0.4em;
    color: #f0f0f0;
}

.ProseMirror p {
    margin-bottom: 0.75em;
}

.ProseMirror ul,
.ProseMirror ol {
    padding-left: 1.5em;
    margin-bottom: 0.75em;
}

.ProseMirror li {
    margin-bottom: 0.25em;
}

.ProseMirror strong {
    font-weight: 700;
    color: #ffffff;
}

.ProseMirror code {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 4px;
    padding: 0.15em 0.4em;
    font-size: 0.9em;
    color: #fbbf24;
}

.ProseMirror blockquote {
    border-left: 3px solid rgba(244, 253, 59, 0.3);
    padding-left: 1em;
    margin-left: 0;
    margin-right: 0;
    color: rgba(255, 255, 255, 0.65);
    font-style: italic;
}

.ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: rgba(255, 255, 255, 0.2);
    pointer-events: none;
    height: 0;
}
</style>
