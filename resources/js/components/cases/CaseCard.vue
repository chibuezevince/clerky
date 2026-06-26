<script setup lang="ts">
import { statusConfig } from '@/data/constants'
import { Clerking } from '@/types/dashboard'
import { formatDate } from '@vueuse/core'
import { Clock, Trash2, User, CheckCircle2, ArrowRight } from '@lucide/vue'

defineProps<{
    clerking: Clerking
    currentQuestionsCount: { count: number }
}>()

const emit = defineEmits<{
    delete: [sessionId: string]
}>()
</script>

<template>
    <div class="flex flex-col gap-2">
        <div
            class="group flex w-full flex-col gap-3 rounded-2xl border border-white/6 bg-brand-surface/60 p-4 backdrop-blur-xl transition-all hover:border-white/10 hover:bg-brand-surface/80 sm:flex-row sm:items-center sm:gap-4 sm:px-5 sm:py-4 lg:gap-6 lg:px-6 lg:py-5"
        >
            <div
                class="flex min-w-0 flex-1 flex-col gap-2 sm:flex-row sm:items-center sm:gap-4 lg:gap-6"
            >
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span
                            :class="[
                                'inline-block h-2 w-2 shrink-0 rounded-full',
                                clerking.status === 'complete'
                                    ? 'bg-teal-400'
                                    : clerking.status === 'in_progress'
                                      ? 'bg-brand-yellow'
                                      : 'bg-white/20',
                            ]"
                        />
                        <span
                            class="truncate text-[15px] font-extrabold text-white sm:text-[16px]"
                        >
                            {{ clerking.case_number }}
                        </span>
                    </div>

                    <div
                        class="mt-1.5 flex flex-wrap items-center gap-x-1.5 gap-y-1 text-[13px] text-brand-gray"
                    >
                        <template v-if="clerking.patient?.name">
                            <User
                                :size="13"
                                class="shrink-0"
                            />
                            <span class="truncate">{{
                                clerking.patient.name
                            }}</span>
                            <span
                                v-if="clerking.patient.age"
                                class="text-white/30"
                            >
                                &middot; {{ clerking.patient.age }}y
                            </span>
                        </template>
                        <span
                            v-if="clerking.current_section?.name"
                            class="truncate rounded bg-white/5 px-1.5 py-0.5 text-[11px] font-medium text-white/60"
                        >
                            {{ clerking.current_section.name }}
                        </span>
                        <span
                            class="shrink-0 rounded bg-white/5 px-1.5 py-0.5 text-[11px] font-medium text-white/40"
                        >
                            Question {{ clerking.current_question_index }} of
                            {{ currentQuestionsCount.count }}
                        </span>
                        <span
                            v-if="
                                clerking.presenting_complaint_response?.answer
                            "
                            class="truncate rounded bg-white/5 px-1.5 py-0.5 text-[11px] font-medium text-white/50"
                        >
                            {{
                                clerking.presenting_complaint_response.answer
                                    .map((c) => c.key)
                                    .join(', ')
                            }}
                        </span>
                    </div>
                </div>

                <div
                    class="flex flex-wrap items-center gap-2 text-[11px] text-brand-gray sm:flex-nowrap sm:gap-3 sm:text-[12px]"
                >
                    <span
                        class="shrink-0 truncate rounded-lg bg-white/5 px-2.5 py-1 font-semibold text-white/50 sm:px-3 sm:py-1.5"
                    >
                        {{ clerking.unit.name }}
                    </span>
                    <span class="flex shrink-0 items-center gap-1">
                        <Clock :size="12" />
                        {{
                            formatDate(
                                new Date(clerking.created_at),
                                'DD MMM YYYY',
                            )
                        }}
                    </span>
                    <CheckCircle2
                        v-if="clerking.summary_count"
                        :size="16"
                        class="shrink-0 text-teal-400"
                    />
                </div>
            </div>

            <div
                class="flex shrink-0 items-center justify-between gap-2 sm:justify-end sm:gap-3"
            >
                <span
                    :class="[
                        'rounded-lg px-2.5 py-1 text-[11px] font-bold sm:px-3 sm:py-1.5 sm:text-[12px]',
                        statusConfig[clerking.status]?.classes ??
                            'bg-white/10 text-white/60',
                    ]"
                >
                    {{
                        statusConfig[clerking.status]?.label ?? clerking.status
                    }}
                </span>

                <div class="flex gap-3">
                    <button
                        @click.stop="emit('delete', clerking.session_id)"
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/10 text-red-400/50 transition-colors hover:border-red-500/20 hover:bg-red-500/10 hover:text-red-400 sm:h-9 sm:w-9"
                    >
                        <Trash2 :size="14" />
                    </button>

                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/10 transition-colors group-hover:border-brand-yellow/30 group-hover:bg-brand-yellow/10 sm:h-9 sm:w-9"
                    >
                        <ArrowRight
                            :size="16"
                            class="text-white/30 transition-colors group-hover:text-brand-yellow"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
