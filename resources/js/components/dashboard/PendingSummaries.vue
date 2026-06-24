<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { FileText, Sparkles } from '@lucide/vue'
import { summary } from '@/routes/clerking'
import { generate } from '@/routes/clerking/summary'
import { onMounted, onUnmounted, ref, watch } from 'vue'
import { Summary } from '@/types/dashboard'
import { STORAGE_KEY } from '@/data/constants'
import { loadGeneratingIds } from '@/data/functions'
import { useEventListener } from '@vueuse/core'

const saveGeneratingIds = (ids: Set<number>) =>
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify([...ids]))

const props = withDefaults(
    defineProps<{
        pendingSummaries?: Summary[]
        totalCompleted?: number
    }>(),
    {
        pendingSummaries: () => [],
        totalCompleted: 0,
    },
)

let generatingIds = ref<Set<number>>(new Set())
onMounted(() => (generatingIds.value = loadGeneratingIds()))

watch(
    () => props.pendingSummaries,
    (pending) => {
        const pendingIds = new Set(pending.map((c) => c.id))
        const stale = [...generatingIds.value].filter(
            (id) => !pendingIds.has(id),
        )
        if (stale.length) {
            stale.forEach((id) => generatingIds.value.delete(id))
            saveGeneratingIds(generatingIds.value)
        }
    },
    { immediate: true },
)

watch(generatingIds, (ids) => saveGeneratingIds(ids), { deep: true })

const handleGenerate = (clerking: { id: number; session_id: string }) => {
    generatingIds.value.add(clerking.id)
    saveGeneratingIds(generatingIds.value)
    router.post(
        generate(clerking.session_id),
        {},
        {
            onError: () => {
                generatingIds.value.delete(clerking.id)
                saveGeneratingIds(generatingIds.value)
            },
        },
    )
}

if (typeof window !== 'undefined') {
    useEventListener(
        window,
        'session-storage',
        (e) => (generatingIds.value = loadGeneratingIds()),
    )
}
</script>

<template>
    <div :class="[glass, 'flex flex-col gap-3 p-5 max-[1100px]:flex-1']">
        <div class="flex items-center justify-between">
            <div class="text-[15px] font-bold">Pending Summaries</div>
            <div
                v-if="pendingSummaries.length"
                class="rounded-full bg-brand-yellow/15 px-2.5 py-0.5 text-[11px] font-bold text-brand-yellow"
            >
                {{ pendingSummaries.length }}
            </div>
        </div>

        <template v-if="!pendingSummaries.length">
            <div class="flex flex-col items-center gap-2 py-6">
                <div
                    class="grid h-9 w-9 place-items-center rounded-full border border-teal-400/20 bg-teal-400/10"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#2dd4bf"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-4.5 w-4.5"
                    >
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                </div>
                <div class="text-[11px] text-teal-300">All clear</div>
                <div
                    class="text-center text-[11px] leading-relaxed text-brand-gray"
                >
                    All completed clerkings have summaries.
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex flex-col divide-y divide-white/5">
                <div
                    v-for="clerking in pendingSummaries"
                    :key="clerking.id"
                    class="flex items-center justify-between gap-3 py-2.5"
                >
                    <div class="flex min-w-0 flex-col gap-0.5">
                        <button
                            @click="router.visit(summary(clerking.session_id))"
                            class="truncate text-left text-[13px] font-bold text-white hover:text-brand-yellow"
                        >
                            {{ clerking.case_number }}
                        </button>
                        <div
                            class="flex items-center gap-2 text-[11px] text-brand-gray"
                        >
                            <FileText :size="11" />
                            {{ clerking.unit.name }}
                        </div>
                    </div>
                    <button
                        @click="handleGenerate(clerking)"
                        :disabled="generatingIds.has(clerking.id)"
                        :class="[
                            'flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-1.5 text-[11px] font-bold transition-colors active:scale-95',
                            generatingIds.has(clerking.id)
                                ? 'cursor-not-allowed bg-brand-yellow/10 text-brand-yellow/50'
                                : 'bg-brand-yellow/15 text-brand-yellow hover:bg-brand-yellow/25',
                        ]"
                    >
                        <Sparkles
                            :size="12"
                            :class="
                                generatingIds.has(clerking.id)
                                    ? 'animate-pulse'
                                    : ''
                            "
                        />
                        {{
                            generatingIds.has(clerking.id)
                                ? 'Generating...'
                                : 'Generate'
                        }}
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>
