<script setup lang="ts">
import { Sparkles } from '@lucide/vue'
import { Motion, AnimatePresence } from 'motion-v'
import { glass } from '@/data/dashboard.js'
import { UnitQuestion } from '@/types/dashboard'

const props = defineProps<{
    nextQuestions: UnitQuestion[]
    processing: boolean
}>()
</script>

<template>
    <div
        :class="[
            glass,
            'flex flex-col gap-6 p-6 transition-all duration-500 hover:border-brand-yellow/20 md:gap-8 md:p-8',
        ]"
    >
        <div class="flex items-center gap-4">
            <div
                class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-brand-yellow/20 bg-brand-yellow/10 text-brand-yellow"
            >
                <Sparkles :size="20" />
                <div
                    class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-brand-yellow opacity-20"
                ></div>
            </div>
            <h3
                class="text-sm font-black tracking-[0.15em] text-brand-yellow uppercase"
            >
                Next Questions
            </h3>
        </div>

        <div class="flex flex-col gap-6">
            <div class="h-px bg-white/5"></div>

            <div class="flex flex-col gap-5">
                <ul
                    v-if="nextQuestions.length > 0 && !processing"
                    class="flex flex-col gap-4"
                >
                    <AnimatePresence mode="popLayout">
                        <Motion
                            v-for="(question, i) in nextQuestions"
                            :key="i"
                            :initial="{ opacity: 0, x: 20, scale: 0.95 }"
                            :animate="{ opacity: 1, x: 0, scale: 1 }"
                            :exit="{ opacity: 0, x: -20, scale: 0.95 }"
                            :transition="{
                                x: {
                                    type: 'spring',
                                    stiffness: 300,
                                    damping: 30,
                                },
                                opacity: { duration: 0.2 },
                                scale: { duration: 0.2 },
                            }"
                        >
                            <li
                                class="group/item flex cursor-pointer items-start gap-4 rounded-2xl border border-transparent bg-white/2 p-4 transition-all hover:border-brand-yellow/20 hover:bg-brand-yellow/3"
                            >
                                <span
                                    class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-gray/40 transition-all group-hover/item:bg-brand-yellow group-hover/item:shadow-[0_0_8px_rgba(244,253,59,0.6)]"
                                ></span>
                                <div class="flex min-w-0 flex-col gap-1">
                                    <span
                                        class="text-sm font-bold text-brand-gray-light transition-colors group-hover/item:text-white"
                                        >{{ question.question }}</span
                                    >
                                    <span
                                        class="text-[10px] font-semibold tracking-wider text-brand-gray/40 uppercase"
                                        >{{ question.section?.name }}</span
                                    >
                                </div>
                            </li>
                        </Motion>
                    </AnimatePresence>
                </ul>

                <div
                    v-else
                    class="flex flex-col gap-4"
                >
                    <div
                        v-if="processing"
                        class="flex flex-col gap-4"
                    >
                        <div
                            v-for="i in 5"
                            :key="i"
                            class="flex items-start gap-4 rounded-2xl bg-white/2 p-4"
                        >
                            <div
                                class="mt-2 h-1.5 w-1.5 shrink-0 animate-pulse rounded-full bg-white/10"
                            ></div>
                            <div class="flex w-full flex-col gap-2">
                                <div
                                    class="h-3 w-full animate-pulse rounded-full bg-white/10"
                                ></div>
                                <div
                                    class="h-3 w-2/3 animate-pulse rounded-full bg-white/10"
                                    :style="{ animationDelay: '0.2s' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center py-8 text-center"
                    >
                        <span
                            class="text-xs font-medium text-brand-gray opacity-50"
                        >
                            No upcoming questions
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
