<script setup lang="ts">
import { glass, pillBtn, convertText } from '@/data/dashboard.js'
import { all } from '@/routes/cases'
import { start } from '@/routes/clerk'
import { type Clerking } from '@/types/dashboard'
import { Link } from '@inertiajs/vue3'
import { Baby, HeartPulse, Scissors, Stethoscope, Venus } from '@lucide/vue'
import { computed, type Component } from 'vue'

const props = defineProps<{
    latestClerkings: Clerking[]
}>()

const iconMap: Record<string, Component> = {
    Baby,
    Venus,
    Stethoscope,
    Scissors,
    HeartPulse,
}

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '—'
    return new Date(dateStr).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
    })
}

function statusColourClass(status: string): string {
    switch (status) {
        case 'complete':
            return 'text-teal-300'
        case 'in_progress':
            return 'text-brand-yellow'
        case 'archived':
            return 'text-brand-gray'
        default:
            return ''
    }
}

function ageSex(clerking: Clerking): string {
    const parts: string[] = []
    if (clerking.patient?.age) parts.push(String(clerking.patient.age))
    if (clerking.patient?.sex)
        parts.push(clerking.patient.sex.charAt(0).toUpperCase())
    return parts.join('')
}

const clerkings = computed(() =>
    props.latestClerkings.map((clerking: Clerking) => ({
        ...clerking,
        icon: clerking.unit.icon
            ? (iconMap[clerking.unit.icon as string] ?? null)
            : null,
    })),
)
</script>

<template>
    <div :class="[glass, 'mt-5 p-5 max-[1100px]:flex-1']">
        <div class="mb-4 flex items-center justify-between">
            <div class="text-[15px] font-bold">Recent Clerkings</div>
            <Link
                :href="all()"
                prefetch
                view-transition
                class="cursor-pointer text-[12px] text-brand-gray hover:text-brand-yellow"
            >
                View all →
            </Link>
        </div>

        <template v-if="latestClerkings.length">
            <Link
                v-for="clerking in clerkings"
                :key="clerking.id"
                :href="`/dashboard/clerking/${clerking.session_id}`"
                view-transition
                prefetch
                class="group flex items-center gap-3 border-b border-white/4 py-3 last:border-0"
            >
                <div
                    class="grid h-9 w-9 shrink-0 place-items-center rounded-[10px] border border-brand-yellow/20 bg-brand-yellow/10"
                >
                    <component
                        :is="clerking.icon"
                        class="h-5 w-5 text-brand-yellow/70 transition-colors group-hover:text-brand-yellow"
                        stroke-width="1.5"
                    />
                </div>

                <div class="min-w-0 flex-1">
                    <div class="truncate text-[13px] font-semibold">
                        {{ clerking.unit.name }}
                    </div>

                    <div
                        class="mt-0.5 flex flex-wrap items-center gap-1.5 text-[11px] text-brand-gray"
                    >
                        <span
                            >CASE #{{ clerking.id }} -
                            {{ clerking.case_number }}</span
                        >

                        <template v-if="ageSex(clerking)">
                            <span class="opacity-30">·</span>
                            <span>{{ ageSex(clerking) }}</span>
                        </template>

                        <template v-if="clerking.complaint_template?.name">
                            <span class="opacity-30">·</span>
                            <span class="truncate">
                                {{ clerking.complaint_template.name }}
                            </span>
                        </template>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col items-end gap-0.5">
                    <span
                        :class="[
                            'text-[11px] font-semibold capitalize',
                            statusColourClass(clerking.status),
                        ]"
                    >
                        {{ convertText(clerking.status) }}
                    </span>

                    <span class="font-mono text-[10px] text-brand-gray">
                        {{ formatDate(clerking.created_at) }}
                    </span>
                </div>

                <div
                    class="ml-1 shrink-0 text-brand-gray opacity-0 transition-opacity group-hover:opacity-100 hover:text-brand-yellow"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-4 w-4"
                    >
                        <path d="M7 17 17 7 M8 7h9v9" />
                    </svg>
                </div>
            </Link>

            <div
                class="mt-4"
                v-if="!latestClerkings.length"
            >
                <Link
                    :href="start()"
                    view-transition
                    prefetch
                >
                    <button
                        :class="[
                            pillBtn,
                            'w-full border border-brand-yellow bg-brand-yellow text-brand-bg shadow-[0_0_24px_rgba(244,253,59,0.35)]',
                        ]"
                    >
                        Start Clerking
                    </button>
                </Link>
            </div>
        </template>

        <template v-else>
            <div
                class="flex h-full flex-col items-center justify-center gap-4 py-6 text-center"
            >
                <div
                    class="grid h-14 w-14 place-items-center rounded-2xl border border-white/6 bg-white/3"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="rgba(255,255,255,0.2)"
                        stroke-width="1.4"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-7 w-7"
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
                        <path d="M9 12h6 M9 16h4" />
                    </svg>
                </div>
                <div>
                    <div class="text-[14px] font-semibold text-white/60">
                        No clerkings yet
                    </div>
                    <div
                        class="mt-1 text-[12px] leading-relaxed text-brand-gray"
                    >
                        Your recent clerkings<br />will appear here.
                    </div>
                </div>
                <button
                    :class="[
                        pillBtn,
                        'border border-brand-yellow bg-brand-yellow px-6 text-brand-bg shadow-[0_0_24px_rgba(244,253,59,0.25)]',
                    ]"
                >
                    Start Clerking
                </button>
            </div>
        </template>
    </div>
</template>
