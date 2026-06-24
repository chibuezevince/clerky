<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { computed, ref } from 'vue'
import { Search, Layers, ArrowRight } from '@lucide/vue'
import { useDebounceFn } from '@vueuse/core'
import {
    index as sectionQuestionsIndex,
    show,
} from '@/routes/section-questions'

defineOptions({ layout: AppLayout })

type ClerkingTemplate = {
    id: number
    name: string
    slug: string
    description: string | null
    questions_count: number
}

const props = defineProps<{
    templates: ClerkingTemplate[]
}>()

const search = ref(
    new URLSearchParams(window.location.search).get('search') ?? '',
)

const filteredTemplates = computed(() => {
    if (!search.value) return props.templates
    const q = search.value.toLowerCase()
    return props.templates.filter(
        (t) =>
            t.name.toLowerCase().includes(q) ||
            (t.description?.toLowerCase().includes(q) ?? false),
    )
})

const groupedTemplates = computed(() => {
    const groups = new Map<string, ClerkingTemplate[]>()
    for (const t of filteredTemplates.value) {
        const letter = t.name.charAt(0).toUpperCase()
        if (!groups.has(letter)) groups.set(letter, [])
        groups.get(letter)!.push(t)
    }
    return [...groups.entries()].sort(([a], [b]) => a.localeCompare(b))
})

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(
        sectionQuestionsIndex(),
        { search: value || undefined },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}, 300)

function onSearchInput(e: Event) {
    const value = (e.target as HTMLInputElement).value
    search.value = value
    debouncedSearch(value)
}
</script>

<template>
    <Head title="Section Questions" />
    <div class="col-span-1 lg:col-span-2">
        <div class="col-span-2 flex w-full flex-col gap-5 py-8">
            <div class="flex items-center justify-between">
                <h1
                    class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
                >
                    Section Questions
                </h1>
                <span
                    v-if="templates.length"
                    class="rounded-full bg-white/10 px-3 py-0.5 text-xs font-semibold text-white/50"
                >
                    {{ templates.length }} templates
                </span>
            </div>

            <p class="-mt-3 text-[13px] leading-relaxed text-brand-gray">
                Manage reusable section questions across clerking templates.
                Select a template to add, reorder, or refine its questions.
            </p>

            <div :class="[glass, 'relative']">
                <Search
                    class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-brand-gray"
                    :size="16"
                />
                <input
                    v-model="search"
                    @input="onSearchInput"
                    type="text"
                    placeholder="Search by template name..."
                    class="w-full rounded-3xl bg-transparent py-3 pr-4 pl-11 text-sm text-white placeholder-brand-gray/60 outline-none"
                />
            </div>

            <template v-if="!filteredTemplates.length">
                <div class="flex flex-col items-center gap-3 py-16 text-center">
                    <div
                        class="grid h-12 w-12 place-items-center rounded-full border border-white/10 bg-white/5"
                    >
                        <Layers
                            :size="20"
                            class="text-brand-gray"
                        />
                    </div>
                    <div class="text-sm font-semibold text-white/60">
                        No templates found
                    </div>
                    <div class="text-xs text-brand-gray">
                        {{
                            search
                                ? 'Try a different search term.'
                                : 'No clerking templates available yet.'
                        }}
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="flex flex-col gap-6">
                    <div
                        v-for="[letter, templates] in groupedTemplates"
                        :key="letter"
                    >
                        <div class="mb-2 flex items-center gap-2 px-1">
                            <span
                                class="grid h-6 w-6 place-items-center rounded-lg bg-white/10 text-[12px] font-extrabold text-white/60"
                            >
                                {{ letter }}
                            </span>
                            <div class="h-px flex-1 bg-white/6" />
                        </div>

                        <div
                            class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3"
                        >
                            <Link
                                v-for="template in templates"
                                :key="template.id"
                                :href="show(template.slug)"
                                prefetch
                                class="group flex w-full cursor-pointer items-center gap-5 rounded-2xl border border-white/8 bg-brand-surface/60 px-6 py-4 backdrop-blur-xl transition-all hover:border-white/12 hover:bg-brand-surface/80"
                            >
                                <div
                                    class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/5"
                                >
                                    <Layers
                                        :size="16"
                                        class="text-brand-gray"
                                    />
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="truncate text-[15px] font-extrabold text-white"
                                        >
                                            {{ template.name }}
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 flex items-center gap-2 text-[12px] text-brand-gray"
                                    >
                                        <span>{{ template.slug }}</span>
                                        <span
                                            v-if="template.questions_count"
                                            class="text-white/30"
                                        >
                                            &middot;
                                            {{ template.questions_count }}
                                            questions
                                        </span>
                                        <span
                                            v-else
                                            class="text-white/20"
                                        >
                                            No questions yet
                                        </span>
                                    </div>
                                    <p
                                        v-if="template.description"
                                        class="mt-1 line-clamp-1 text-[12px] text-white/30"
                                    >
                                        {{ template.description }}
                                    </p>
                                </div>

                                <div
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/10 transition-colors group-hover:border-brand-yellow/30 group-hover:bg-brand-yellow/10"
                                >
                                    <ArrowRight
                                        :size="14"
                                        class="text-white/20 transition-colors group-hover:text-brand-yellow"
                                    />
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
