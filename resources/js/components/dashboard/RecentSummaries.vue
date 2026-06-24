<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { Eye, FileText, Sparkles } from '@lucide/vue'
import { edit } from '@/routes/clerking/summary'
import { computed } from 'vue'
import { Summary } from '@/types/dashboard'

const props = defineProps<{
    recentSummaries: Summary[]
}>()

const list = computed(() => props.recentSummaries)
</script>

<template>
    <div :class="[glass, 'flex flex-col gap-3 p-5 max-[1100px]:flex-1']">
        <div class="flex items-center justify-between">
            <div class="text-[15px] font-bold">Recent Summaries</div>
            <div
                v-if="list.length"
                class="rounded-full bg-brand-yellow/15 px-2.5 py-0.5 text-[11px] font-bold text-brand-yellow"
            >
                {{ list.length }}
            </div>
        </div>

        <template v-if="!list.length">
            <div class="flex flex-col items-center gap-2 py-6">
                <div
                    class="grid h-9 w-9 place-items-center rounded-full border border-white/10 bg-white/5"
                >
                    <FileText
                        :size="16"
                        class="text-brand-gray"
                    />
                </div>
                <div
                    class="text-center text-[11px] leading-relaxed text-brand-gray"
                >
                    No AI summaries generated yet.<br />Complete a clerking and
                    generate one.
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex flex-col divide-y divide-white/5">
                <div
                    v-for="summary in list"
                    :key="summary.id"
                    class="flex flex-col gap-2 py-3 first:pt-0 last:pb-0"
                >
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex min-w-0 items-center gap-2">
                            <button
                                @click="router.visit(edit(summary.session_id))"
                                class="truncate text-[13px] font-bold text-white hover:text-brand-yellow"
                            >
                                {{ summary.case_number }}
                            </button>
                            <span
                                v-if="summary.is_confirmed"
                                class="shrink-0 rounded-full bg-teal-400/10 px-1.5 py-0.5 text-[9px] font-bold text-teal-300"
                            >
                                Confirmed
                            </span>
                        </div>
                        <span class="shrink-0 text-[10px] text-brand-gray">
                            {{ summary.unit_name }}
                        </span>
                    </div>

                    <p
                        class="line-clamp-2 text-[12px] leading-relaxed text-brand-gray/80"
                    >
                        {{ summary.preview }}
                    </p>

                    <div class="flex items-center gap-2">
                        <button
                            @click="router.visit(edit(summary.session_id))"
                            class="flex items-center gap-1 text-[11px] font-bold text-brand-yellow hover:underline"
                        >
                            <Eye :size="12" />
                            View
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
