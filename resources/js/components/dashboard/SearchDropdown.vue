<script setup lang="ts">
import type { Clerking } from '@/types/dashboard'

defineProps<{
    results: Clerking[]
}>()

const emit = defineEmits<{
    select: [sessionId: string]
}>()
</script>

<template>
    <div
        v-if="results.length"
        class="absolute top-full right-0 left-0 z-100 mt-2 overflow-hidden rounded-2xl border border-white/6 bg-brand-surface/95 shadow-xl backdrop-blur-2xl"
    >
        <div
            class="flex max-h-80 flex-col divide-y divide-white/5 overflow-y-auto"
        >
            <div
                v-for="clerking in results"
                :key="clerking.id"
                @click="emit('select', clerking.session_id)"
                class="flex cursor-pointer items-center gap-3 px-4 py-3 transition-colors hover:bg-white/4"
            >
                <div
                    class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/5"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        class="h-4 w-4 text-brand-gray"
                    >
                        <path
                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        />
                        <polyline points="14 2 14 8 20 8" />
                        <line
                            x1="16"
                            y1="13"
                            x2="8"
                            y2="13"
                        />
                        <line
                            x1="16"
                            y1="17"
                            x2="8"
                            y2="17"
                        />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-[13px] font-bold text-white">
                        {{ clerking.case_number }}
                    </div>
                    <div
                        class="flex items-center gap-1.5 text-[11px] text-brand-gray"
                    >
                        <template v-if="clerking.patient?.name">
                            <span>{{ clerking.patient.name }}</span>
                            <span
                                v-if="clerking.patient.age"
                                class="text-white/30"
                                >&middot; {{ clerking.patient.age }}y</span
                            >
                        </template>
                    </div>
                </div>
                <div class="shrink-0 text-[10px] font-semibold text-white/40">
                    {{ clerking.unit.name }}
                </div>
                <span
                    :class="[
                        'shrink-0 rounded px-1.5 py-0.5 text-[10px] font-bold',
                        clerking.status === 'complete'
                            ? 'bg-teal-400/10 text-teal-300'
                            : clerking.status === 'in_progress'
                              ? 'bg-brand-yellow/10 text-brand-yellow'
                              : 'bg-white/10 text-white/50',
                    ]"
                >
                    {{
                        clerking.status === 'in_progress'
                            ? 'In Progress'
                            : clerking.status === 'complete'
                              ? 'Complete'
                              : 'Draft'
                    }}
                </span>
            </div>
        </div>
    </div>
</template>
