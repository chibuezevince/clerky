<script setup lang="ts">
import type { Clerking } from '@/types/dashboard'
import { ChevronRight } from '@lucide/vue'

const props = defineProps<{
    latestClerkings: Clerking[]
}>()

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '—'
    return new Date(dateStr).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}

function getProgress(clerking: Clerking): number {
    if (clerking.status === 'complete') return 100
    return Math.floor(Math.random() * 60) + 20
}
</script>

<template>
    <div class="mt-4 flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold tracking-tight text-white">
                Recent Cases
            </h2>
            <a
                href="#"
                class="text-xs font-medium text-brand-gray transition-colors hover:text-white"
                >View all →</a
            >
        </div>

        <div
            class="moverflow-hidden rounded-2xl border border-white/5 bg-brand-surface/40 backdrop-blur-md"
        >
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="border-b border-white/5 text-[11px] font-semibold tracking-wider text-brand-gray uppercase"
                    >
                        <th class="px-6 py-4">Patient Name</th>
                        <th class="px-6 py-4">Case ID</th>
                        <th class="px-6 py-4">Section</th>
                        <th class="px-6 py-4">Progress</th>
                        <th class="px-6 py-4">Time Elapsed</th>
                        <th class="px-6 py-4">Last Updated</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    <tr
                        v-for="clerking in latestClerkings"
                        :key="clerking.id"
                        class="group cursor-pointer transition-colors hover:bg-white/2"
                    >
                        <td class="px-6 py-4">
                            <div class="text-[13px] font-semibold text-white">
                                {{ clerking.unit.name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="text-[12px] font-medium text-brand-gray"
                            >
                                CLRKY-2026-{{ clerking.case_number }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="inline-flex rounded-md bg-white/5 px-2 py-1 text-[11px] font-medium text-brand-gray"
                            >
                                {{
                                    clerking.complaint_template?.name ||
                                    'General'
                                }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-1.5 w-24 overflow-hidden rounded-full bg-white/10"
                                >
                                    <div
                                        class="h-full bg-brand-yellow transition-all"
                                        :style="{
                                            width: getProgress(clerking) + '%',
                                        }"
                                    ></div>
                                </div>
                                <span
                                    class="text-[11px] font-medium text-brand-gray"
                                >
                                    {{ getProgress(clerking) }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[12px] text-brand-gray">01:23</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[12px] text-brand-gray">
                                {{ formatDate(clerking.created_at) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <ChevronRight
                                class="ml-auto h-4 w-4 text-brand-gray opacity-0 transition-all group-hover:translate-x-1 group-hover:text-white group-hover:opacity-100"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="!latestClerkings.length"
                class="flex flex-col items-center justify-center py-20 text-center"
            >
                <div class="mb-4 rounded-2xl bg-white/5 p-4">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        class="h-8 w-8 text-brand-gray/50"
                    >
                        <path
                            d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"
                        />
                        <rect
                            x="9"
                            y="3"
                            width="6"
                            height="4"
                            rx="1"
                        />
                    </svg>
                </div>
                <p class="text-sm font-medium text-brand-gray">
                    No recent cases found.
                </p>
            </div>
        </div>
    </div>
</template>
