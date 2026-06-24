<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { register } from '@/routes'
</script>

<template>
    <section
        id="support"
        class="relative z-10 overflow-hidden px-8 py-28 md:px-16"
    >
        <div
            class="mx-auto flex max-w-7xl flex-col items-center gap-16 lg:flex-row"
        >
            <div
                class="reveal-left relative w-full overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-12 lg:w-1/2 lg:p-16"
            >
                <div
                    class="pointer-events-none absolute -top-20 -right-20 h-64 w-64 rounded-full bg-brand-yellow/5 blur-[100px]"
                ></div>

                <div class="relative z-10">
                    <div
                        class="mb-8 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-yellow"
                    >
                        <svg
                            class="h-6 w-6 text-black"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h2
                        class="mb-4 text-3xl leading-tight font-extrabold tracking-tight text-white md:text-4xl"
                    >
                        Built by a <br /><em class="text-brand-yellow italic"
                            >Clinician</em
                        >, for Clinicians
                    </h2>
                    <p
                        class="mb-10 text-sm leading-relaxed text-brand-gray-light"
                    >
                        Clerky is free and open-source. No trials, no pricing
                        tiers; just structured clerking tools designed by a
                        medical student who understands the bedside.
                    </p>

                    <div class="flex flex-col gap-4 sm:flex-row">
                        <Link
                            :href="register()"
                            view-transition
                            prefetch
                            class="rounded-full bg-brand-yellow px-8 py-3.5 text-center text-sm font-bold text-black transition-all hover:brightness-110"
                        >
                            Get Started
                        </Link>
                        <a
                            href="https://paystack.com/pay/clerky"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-full border border-brand-border bg-transparent px-8 py-3.5 text-center text-sm font-bold text-white transition-all hover:border-white/30"
                        >
                            Support the Project
                        </a>
                    </div>
                </div>
            </div>

            <div class="reveal-right w-full lg:w-1/2 lg:pl-8">
                <h2
                    class="mb-4 text-5xl leading-none font-extrabold tracking-tight text-white md:text-7xl"
                >
                    Structured Clerking.
                </h2>
                <h2
                    class="mb-12 text-5xl leading-none font-extrabold tracking-tight text-brand-gray md:text-7xl"
                >
                    No Gimmicks.
                </h2>
                <div class="grid grid-cols-2 gap-10">
                    <div>
                        <p
                            class="mb-1 text-4xl font-extrabold text-brand-yellow md:text-5xl"
                        >
                            4
                        </p>
                        <p
                            class="text-[10px] font-bold tracking-widest text-brand-gray uppercase"
                        >
                            Specialty Templates
                        </p>
                    </div>
                    <div>
                        <p
                            class="mb-1 text-4xl font-extrabold text-white md:text-5xl"
                        >
                            20+
                        </p>
                        <p
                            class="text-[10px] font-bold tracking-widest text-brand-gray uppercase"
                        >
                            Clerking Sections
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
