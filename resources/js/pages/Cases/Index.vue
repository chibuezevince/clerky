<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, InfiniteScroll, Link, router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { ref } from 'vue'
import { Search, Plus, Stethoscope } from '@lucide/vue'
import { useDebounceFn } from '@vueuse/core'
import type { Clerking } from '@/types/dashboard'
import CaseCard from '@/components/cases/CaseCard.vue'
import ConfirmDialog from '@/components/dashboard/ConfirmDialog.vue'
import { start } from '@/routes/clerk'
import { toast } from 'vue-sonner'
import { all } from '@/routes/cases'
import { clerk } from '@/routes'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    cases: { data: Clerking[] }
    questionCount: Record<string, { count: number }>
}>()

const search = ref('')
const confirmDelete = ref<string | null>(null)

const promptDelete = (sessionId: string) => {
    confirmDelete.value = sessionId
}

const confirmDeleteCase = () => {
    if (!confirmDelete.value) return

    router.delete(`/dashboard/cases/${confirmDelete.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            confirmDelete.value = null
            toast.success('Case deleted.')
        },
        onFinish: () => {
            confirmDelete.value = null
        },
    })
}

const debouncedSearch = useDebounceFn((value: string) => {
    router.get(
        all(),
        { search: value || undefined },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            viewTransition: false,
        },
    )
}, 300)

const onSearchInput = (e: Event) => {
    const value = (e.target as HTMLInputElement).value
    search.value = value
    debouncedSearch(value)
}
</script>

<template>
    <Head title="Clerking Cases" />

    <div
        class="relative col-span-2 flex w-full flex-col gap-5 py-5 max-[1100px]:col-span-1"
    >
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <h1
                    class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
                >
                    Cases
                </h1>
                <span
                    v-if="cases.data.length"
                    class="rounded-full bg-white/10 px-3 py-0.5 text-xs font-semibold text-white/50"
                >
                    {{ cases.data.length }} cases
                </span>
            </div>

            <div :class="[glass, 'relative w-full sm:w-100']">
                <Search
                    class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-brand-gray"
                    :size="16"
                />
                <input
                    v-model="search"
                    @input="onSearchInput"
                    type="text"
                    placeholder="Search by case number or patient name..."
                    class="w-full rounded-3xl bg-transparent py-3 pr-4 pl-11 text-sm text-white placeholder-brand-gray/60 outline-none"
                />
            </div>
        </div>

        <InfiniteScroll
            data="cases"
            class="flex flex-col"
        >
            <template v-if="!cases.data.length">
                <div
                    class="flex flex-col items-center px-6 py-18 font-['Plus_Jakarta_Sans']"
                >
                    <div
                        class="mb-7 inline-flex items-center gap-1.25 rounded-full border border-brand-border bg-brand-surface px-2.5 py-0.75 text-[11px] tracking-widest text-white/30"
                    >
                        <span
                            class="h-1.25 w-1.25 rounded-full bg-brand-yellow opacity-50"
                        ></span>
                        0 cases
                    </div>

                    <div class="relative mx-auto h-14 w-14">
                        <div
                            class="absolute inset-0 animate-[pulse-ring_3s_ease-in-out_infinite] rounded-full border border-brand-yellow/20"
                        ></div>
                        <div
                            class="absolute -inset-2.5 animate-[pulse-ring_3s_ease-in-out_.6s_infinite] rounded-full border border-brand-yellow/20"
                        ></div>
                        <div
                            class="absolute -inset-5 animate-[pulse-ring_3s_ease-in-out_1.2s_infinite] rounded-full border border-brand-yellow/20"
                        ></div>
                        <div
                            class="relative z-10 flex h-full w-full items-center justify-center rounded-full border border-brand-border bg-brand-surface"
                        >
                            <Stethoscope
                                :size="22"
                                class="text-brand-yellow opacity-80"
                            />
                        </div>
                    </div>

                    <h3
                        class="mt-5 mb-2 text-[18px] font-semibold tracking-tight text-white/85"
                    >
                        No clerkings yet
                    </h3>
                    <p
                        class="mb-7 max-w-55 text-center text-[13px] leading-relaxed text-white/30"
                    >
                        {{
                            search
                                ? 'No cases match that search.'
                                : 'Your case history will appear here once you start clerking.'
                        }}
                    </p>

                    <Link
                        :href="start()"
                        prefetch
                        view-transition
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-yellow px-5 py-2.5 text-[13px] font-semibold text-brand-bg transition-opacity hover:opacity-85"
                    >
                        <Plus :size="15" />
                        Start a clerking
                    </Link>

                    <div class="mt-8 flex gap-2">
                        <div
                            v-for="(op, i) in [1, 0.6, 0.3]"
                            :key="i"
                            :style="`opacity:${op}`"
                            class="relative h-14 w-30 overflow-hidden rounded-lg border border-brand-border bg-[#111]"
                        >
                            <div
                                class="absolute top-3.5 right-10 left-3 h-1.5 rounded-full bg-brand-border"
                            ></div>
                            <div
                                class="absolute top-7 right-6 left-3 h-1.5 rounded-full bg-brand-border opacity-50"
                            ></div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <div
                    v-for="clerking in cases.data"
                    :key="clerking.id"
                    :href="`/dashboard/clerking/${clerking.session_id}`"
                    class="mt-2 cursor-pointer"
                >
                    <CaseCard
                        :clerking="clerking"
                        :current-questions-count="questionCount[clerking.id]"
                        @delete="promptDelete"
                        @click="
                            router.get(
                                clerk(clerking.session_id),
                                {},
                                { viewTransition: true },
                            )
                        "
                    />
                </div>
            </template>
        </InfiniteScroll>

        <ConfirmDialog
            :open="confirmDelete !== null"
            title="Delete case"
            message="Are you sure you want to delete this case? This action cannot be undone."
            @confirm="confirmDeleteCase"
            @cancel="confirmDelete = null"
        />

        <Link
            v-if="cases.data.length"
            :href="start()"
            prefetch
            view-transition
            class="fixed right-6 bottom-21 z-20 flex h-14 items-center gap-2 rounded-full bg-brand-yellow pr-5 pl-4 font-bold text-black shadow-lg shadow-black/40 transition-transform hover:scale-105 active:scale-95 md:right-10 md:bottom-10"
        >
            <Plus :size="20" />
            <span class="text-sm">Start clerking</span>
        </Link>
    </div>
</template>
