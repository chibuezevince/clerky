<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, InfiniteScroll, Link, router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { computed, ref } from 'vue'
import { Search, Puzzle, BadgeCheck, ArrowRight } from '@lucide/vue'
import { useDebounceFn } from '@vueuse/core'
import { contribute } from '@/routes'
import { show } from '@/routes/contribute'

defineOptions({ layout: AppLayout })

type Template = {
    id: number
    name: string
    slug: string
    is_verified: boolean
    created_at: string
    questions_count?: number
}

const props = defineProps<{
    complaintTemplates: { data: Template[] }
}>()

const search = ref(
    new URLSearchParams(window.location.search).get('search') ?? '',
)

const groupedTemplates = computed(() => {
    const groups = new Map<string, Template[]>()
    for (const t of props.complaintTemplates.data) {
        const letter = t.name.charAt(0).toUpperCase()
        if (!groups.has(letter)) groups.set(letter, [])
        groups.get(letter)!.push(t)
    }
    return [...groups.entries()].sort(([a], [b]) => a.localeCompare(b))
})

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(
        contribute(),
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
    <Head title="Contribute" />
    <div class="col-span-1 lg:col-span-2">
        <div class="col-span-2 flex w-full flex-col gap-5 py-8">
            <div class="flex items-center justify-between">
                <h1
                    class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
                >
                    Contribute
                </h1>
                <span
                    v-if="complaintTemplates.data.length"
                    class="rounded-full bg-white/10 px-3 py-0.5 text-xs font-semibold text-white/50"
                >
                    {{ complaintTemplates.data.length }} templates
                </span>
            </div>

            <p class="-mt-3 text-[13px] leading-relaxed text-brand-gray">
                Browse and contribute to complaint templates. Add, reorder, or
                remove questions to refine each symptom's clerking flow.
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
                    placeholder="Search by symptom or complaint name..."
                    class="w-full rounded-3xl bg-transparent py-3 pr-4 pl-11 text-sm text-white placeholder-brand-gray/60 outline-none"
                />
            </div>

            <InfiniteScroll
                data="complaintTemplates"
                class="flex flex-col"
            >
                <template v-if="!complaintTemplates.data.length">
                    <div
                        class="flex flex-col items-center gap-3 py-16 text-center"
                    >
                        <div
                            class="grid h-12 w-12 place-items-center rounded-full border border-white/10 bg-white/5"
                        >
                            <Puzzle
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
                                    : 'No complaint templates available yet.'
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
                                    view-transition
                                    class="group flex w-full cursor-pointer items-center gap-5 rounded-2xl border border-white/8 bg-brand-surface/60 px-6 py-4 backdrop-blur-xl transition-all hover:border-white/12 hover:bg-brand-surface/80"
                                >
                                    <div
                                        class="grid h-10 w-10 shrink-0 place-items-center rounded-xl"
                                        :class="
                                            template.is_verified
                                                ? 'bg-teal-400/10'
                                                : 'bg-white/5'
                                        "
                                    >
                                        <BadgeCheck
                                            v-if="template.is_verified"
                                            :size="18"
                                            class="text-teal-400"
                                        />
                                        <Puzzle
                                            v-else
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
                                            <span
                                                v-if="template.is_verified"
                                                class="shrink-0 rounded bg-teal-400/10 px-1.5 py-0.5 text-[10px] font-bold text-teal-300"
                                            >
                                                Verified
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
                                        </div>
                                    </div>

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/10 transition-colors group-hover:border-brand-yellow/30 group-hover:bg-brand-yellow/10"
                                    >
                                        <ArrowRight
                                            :size="14"
                                            class="text-white/30 transition-colors group-hover:text-brand-yellow"
                                        />
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </InfiniteScroll>
        </div>
    </div>
</template>
