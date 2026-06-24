<template>
    <section
        id="ai"
        class="relative z-10 overflow-hidden py-32"
    >
        <div class="mx-auto mb-20 max-w-7xl px-8 md:px-16">
            <h2
                class="mb-6 text-5xl font-extrabold tracking-tighter text-white md:text-7xl"
            >
                AI-Powered
                <span
                    class="bg-linear-to-r from-brand-yellow to-yellow-200 bg-clip-text text-transparent"
                    >Clerking</span
                >
            </h2>
            <p
                class="max-w-3xl text-xl leading-relaxed font-light text-brand-gray-light md:text-2xl"
            >
                Two purpose-built AI agents work together to guide your clerking
                and generate structured clinical summaries.
            </p>
        </div>

        <div class="reveal-stagger border-t border-brand-border">
            <div
                class="group reveal-up cursor-pointer border-b border-brand-border transition-colors duration-500 hover:bg-white/2"
            >
                <div
                    class="mx-auto flex max-w-7xl flex-col gap-6 px-8 py-12 md:flex-row md:items-center md:gap-20 md:px-16 md:py-16"
                >
                    <div
                        class="font-mono text-2xl font-bold text-brand-yellow opacity-30 transition-opacity duration-500 group-hover:opacity-100 md:text-4xl"
                    >
                        01
                    </div>
                    <div class="flex-1">
                        <h3
                            class="mb-4 text-3xl font-bold text-white transition-transform duration-500 group-hover:translate-x-6 md:text-5xl"
                        >
                            Dynamic Question Generation
                        </h3>
                        <p
                            class="max-w-2xl text-lg text-brand-gray transition-colors duration-500 group-hover:text-brand-yellow/80"
                        >
                            The ClerkingAssistant generates relevant follow-up
                            questions based on each answer you provide during
                            the consultation.
                        </p>
                    </div>
                    <div
                        class="hidden h-20 w-20 items-center justify-center rounded-full border border-brand-border transition-all duration-500 group-hover:-rotate-45 group-hover:border-brand-yellow md:flex"
                    >
                        <svg
                            class="h-8 w-8 text-brand-gray transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group reveal-up cursor-pointer border-b border-brand-border transition-colors duration-500 hover:bg-white/2"
            >
                <div
                    class="mx-auto flex max-w-7xl flex-col gap-6 px-8 py-12 md:flex-row md:items-center md:gap-20 md:px-16 md:py-16"
                >
                    <div
                        class="font-mono text-2xl font-bold text-brand-yellow opacity-30 transition-opacity duration-500 group-hover:opacity-100 md:text-4xl"
                    >
                        02
                    </div>
                    <div class="flex-1">
                        <h3
                            class="mb-4 text-3xl font-bold text-white transition-transform duration-500 group-hover:translate-x-6 md:text-5xl"
                        >
                            Discipline-Aware Summaries
                        </h3>
                        <p
                            class="max-w-2xl text-lg text-brand-gray transition-colors duration-500 group-hover:text-brand-yellow/80"
                        >
                            The SummaryGenerator structures each summary by
                            specialty: O&G summaries include gravida/para, LMP,
                            EGA, and EDD by default.
                        </p>
                    </div>
                    <div
                        class="hidden h-20 w-20 items-center justify-center rounded-full border border-brand-border transition-all duration-500 group-hover:-rotate-45 group-hover:border-brand-yellow md:flex"
                    >
                        <svg
                            class="h-8 w-8 text-brand-gray transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group reveal-up cursor-pointer border-b border-brand-border transition-colors duration-500 hover:bg-white/2"
            >
                <div
                    class="mx-auto flex max-w-7xl flex-col gap-6 px-8 py-12 md:flex-row md:items-center md:gap-20 md:px-16 md:py-16"
                >
                    <div
                        class="font-mono text-2xl font-bold text-brand-yellow opacity-30 transition-opacity duration-500 group-hover:opacity-100 md:text-4xl"
                    >
                        03
                    </div>
                    <div class="flex-1">
                        <h3
                            class="mb-4 text-3xl font-bold text-white transition-transform duration-500 group-hover:translate-x-6 md:text-5xl"
                        >
                            Age & Sex Filtering
                        </h3>
                        <p
                            class="max-w-2xl text-lg text-brand-gray transition-colors duration-500 group-hover:text-brand-yellow/80"
                        >
                            Questions are filtered by patient demographics:
                            menstrual history won't be asked for a 10-year-old,
                            and paediatric milestones won't appear for adults.
                        </p>
                    </div>
                    <div
                        class="hidden h-20 w-20 items-center justify-center rounded-full border border-brand-border transition-all duration-500 group-hover:-rotate-45 group-hover:border-brand-yellow md:flex"
                    >
                        <svg
                            class="h-8 w-8 text-brand-gray transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="group reveal-up cursor-pointer border-b border-brand-border transition-colors duration-500 hover:bg-white/2"
            >
                <div
                    class="mx-auto flex max-w-7xl flex-col gap-6 px-8 py-12 md:flex-row md:items-center md:gap-20 md:px-16 md:py-16"
                >
                    <div
                        class="font-mono text-2xl font-bold text-brand-yellow opacity-30 transition-opacity duration-500 group-hover:opacity-100 md:text-4xl"
                    >
                        04
                    </div>
                    <div class="flex-1">
                        <h3
                            class="mb-4 text-3xl font-bold text-white transition-transform duration-500 group-hover:translate-x-6 md:text-5xl"
                        >
                            Provider Failover
                        </h3>
                        <p
                            class="max-w-2xl text-lg text-brand-gray transition-colors duration-500 group-hover:text-brand-yellow/80"
                        >
                            When one AI provider is unavailable, Clerky
                            automatically falls back to another, ensuring
                            clerking never stalls mid-session.
                        </p>
                    </div>
                    <div
                        class="hidden h-20 w-20 items-center justify-center rounded-full border border-brand-border transition-all duration-500 group-hover:-rotate-45 group-hover:border-brand-yellow md:flex"
                    >
                        <svg
                            class="h-8 w-8 text-brand-gray transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
