<script setup lang="ts">
import { ref } from 'vue'

const activeStep = ref(1)

const steps = [
    {
        id: 1,
        name: 'Start a Case',
        desc: 'Choose your specialty template (O&G, Paediatrics, or Gen Med/Surg) and enter the presenting complaint to begin.',
    },
    {
        id: 2,
        name: 'Work Through Sections',
        desc: 'Move through structured clerking sections from biodata and HPC to specialty-specific histories and physical examination.',
    },
    {
        id: 3,
        name: 'AI-Guided Questions',
        desc: 'The clerking assistant dynamically generates follow-up questions based on your answers, ensuring no detail is missed.',
    },
    {
        id: 4,
        name: 'Generate Summary',
        desc: 'Once clerking is complete, the AI produces a structured clinical summary with key findings in SOAP format.',
    },
    {
        id: 5,
        name: 'Review & Archive',
        desc: 'Pending summaries appear on your dashboard. Completed summaries are archived for future reference.',
    },
]
</script>

<template>
    <section
        id="how"
        class="relative z-10 px-8 py-28 md:px-16"
    >
        <div class="mx-auto flex max-w-7xl flex-col gap-20 lg:flex-row">
            <div class="reveal-left lg:w-1/2">
                <h2
                    class="mb-4 text-4xl leading-tight font-extrabold tracking-tight text-white md:text-5xl"
                >
                    How <em class="text-brand-yellow italic">Clerky</em>
                    <br />Works
                </h2>
                <p
                    class="mb-14 max-w-md text-sm leading-relaxed text-brand-gray-light"
                >
                    A structured clerking workflow that guides you from patient
                    intake to AI-generated clinical summary.
                </p>

                <div class="space-y-2">
                    <div
                        v-for="step in steps"
                        :key="step.id"
                        class="cursor-pointer"
                        @click="activeStep = step.id"
                    >
                        <div class="group flex items-center gap-5 py-3">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold transition-all duration-300"
                                :class="
                                    activeStep === step.id
                                        ? 'scale-110 bg-brand-yellow text-black'
                                        : 'border border-brand-border bg-brand-surface text-brand-gray group-hover:border-white/20 group-hover:text-white'
                                "
                            >
                                {{ step.id }}
                            </span>
                            <span
                                class="text-base font-bold transition-colors duration-300"
                                :class="
                                    activeStep === step.id
                                        ? 'text-white'
                                        : 'text-brand-gray group-hover:text-white'
                                "
                            >
                                {{ step.name }}
                            </span>
                        </div>

                        <transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-40 opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-40 opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div
                                v-if="activeStep === step.id"
                                class="overflow-hidden pb-4 pl-14"
                            >
                                <p
                                    class="border-l-2 border-brand-yellow/40 pl-4 text-[13px] leading-relaxed text-brand-gray-light"
                                >
                                    {{ step.desc }}
                                </p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>

            <div class="reveal-right flex items-center justify-center lg:w-1/2">
                <div class="relative w-full max-w-md">
                    <div
                        class="relative overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-8"
                    >
                        <div class="mb-6 flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-yellow/10"
                            >
                                <svg
                                    class="h-5 w-5 text-brand-yellow"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">
                                    Obstetrics & Gynaecology
                                </p>
                                <p class="text-[10px] text-brand-gray">
                                    Section 5 of 14
                                </p>
                            </div>
                            <div class="ml-auto">
                                <span
                                    class="rounded-full bg-brand-yellow/10 px-2.5 py-0.5 text-[10px] font-bold text-brand-yellow"
                                >
                                    In Progress
                                </span>
                            </div>
                        </div>

                        <div class="mb-6 space-y-2">
                            <div class="flex items-center gap-2">
                                <svg
                                    class="h-3.5 w-3.5 shrink-0 text-emerald-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M5 13l4 4L19 7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <p class="text-[11px] text-white/60">
                                    1. Biodata
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg
                                    class="h-3.5 w-3.5 shrink-0 text-emerald-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M5 13l4 4L19 7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <p class="text-[11px] text-white/60">
                                    2. Presenting Complaint
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg
                                    class="h-3.5 w-3.5 shrink-0 text-emerald-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M5 13l4 4L19 7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <p class="text-[11px] text-white/60">
                                    3. History of Presenting Complaint
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg
                                    class="h-3.5 w-3.5 shrink-0 text-emerald-400"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M5 13l4 4L19 7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <p class="text-[11px] text-white/60">
                                    4. Obstetric History
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-3.5 w-3.5 shrink-0 items-center justify-center rounded-full bg-brand-yellow/20"
                                >
                                    <div
                                        class="h-1.5 w-1.5 animate-pulse rounded-full bg-brand-yellow"
                                    ></div>
                                </div>
                                <p
                                    class="text-[11px] font-semibold text-brand-yellow"
                                >
                                    5. Menstrual History
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="h-3.5 w-3.5 shrink-0 rounded-full border border-white/10"
                                ></div>
                                <p class="text-[11px] text-white/20">
                                    6. Contraceptive History
                                </p>
                            </div>
                        </div>

                        <div
                            class="rounded-xl border border-brand-yellow/20 bg-brand-yellow/3 p-4"
                        >
                            <p
                                class="mb-2 text-[10px] font-bold tracking-wider text-brand-gray uppercase"
                            >
                                Current Question
                            </p>
                            <p class="text-[13px] leading-relaxed text-white">
                                What was the date of your last menstrual period?
                            </p>
                            <div class="mt-3 flex items-center gap-2">
                                <span
                                    class="rounded-full bg-brand-yellow/10 px-2.5 py-1 text-[10px] font-bold text-brand-yellow"
                                >
                                    AI Suggested
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="absolute -top-4 -right-4 rounded-xl border border-brand-border bg-brand-surface px-4 py-2.5 shadow-xl"
                    >
                        <p class="text-[10px] font-bold text-brand-gray-light">
                            <span class="text-brand-yellow">●</span> AI-Guided
                            Clerking
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
