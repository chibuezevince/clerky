<script setup lang="ts">
import { ref } from 'vue'
import { register } from '@/routes'
import { Link } from '@inertiajs/vue3'

const hoveredCard = ref<number | null>(null)
const cursorPos = ref({ x: 50, y: 50 })

const onCardMouseMove = (e: MouseEvent, index: number) => {
    const rect = (e.currentTarget as HTMLElement).getBoundingClientRect()
    cursorPos.value = {
        x: ((e.clientX - rect.left) / rect.width) * 100,
        y: ((e.clientY - rect.top) / rect.height) * 100,
    }
    hoveredCard.value = index
}

const onCardMouseLeave = () => (hoveredCard.value = null)
</script>

<template>
    <section
        id="features"
        class="noise-overlay relative z-10 px-8 py-28 md:px-16"
    >
        <div class="mx-auto max-w-7xl">
            <div class="mb-16">
                <h2
                    class="mb-4 text-4xl leading-tight font-extrabold tracking-tight text-white md:text-5xl"
                >
                    Refining Clinical
                    <em
                        class="bg-gradient-to-r from-brand-yellow via-yellow-200 to-brand-yellow bg-clip-text text-transparent italic"
                        >Expertise</em
                    >
                </h2>
                <div
                    class="reveal-up mb-8 h-1 w-24 rounded-full bg-gradient-to-r from-brand-yellow to-transparent"
                ></div>
                <div class="flex flex-col items-start gap-5">
                    <p
                        class="max-w-md text-sm leading-relaxed text-brand-gray-light"
                    >
                        Clerky adapts to every presenting complaint with
                        specialty-specific templates, asks the right questions,
                        then generates a structured summary, so you spend less
                        time on paperwork and more time at the bedside.
                    </p>
                    <Link
                        :href="register()"
                        view-transition
                        class="inline-flex items-center gap-3 rounded-full bg-brand-yellow px-8 py-3.5 text-sm font-extrabold text-black transition-all duration-300 hover:bg-brand-yellow/90 hover:shadow-[0_0_40px_rgba(244,253,59,0.15)] active:scale-95"
                    >
                        Start clerking
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"
                            />
                        </svg>
                    </Link>
                </div>
            </div>

            <div
                class="reveal-stagger grid auto-rows-[180px] grid-cols-4 gap-4 lg:grid-cols-12"
            >
                <div
                    @mousemove="(e) => onCardMouseMove(e, 0)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-4 row-span-2 flex cursor-pointer flex-col justify-between overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-8 transition-all duration-500 hover:border-brand-yellow/50 hover:shadow-[0_0_30px_rgba(244,253,59,0.05),inset_0_0_0_1px_rgba(244,253,59,0.08)] active:scale-[0.98] lg:col-span-5"
                >
                    <span
                        class="pointer-events-none absolute top-4 right-4 text-[44px] leading-none font-black text-white/5 select-none"
                        >01</span
                    >

                    <div
                        v-show="hoveredCard === 0"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(600px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(244,253,59,0.06), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="pointer-events-none absolute -right-16 -bottom-16 h-64 w-64 rounded-full bg-brand-yellow/3 blur-3xl transition-all duration-700 group-hover:scale-110 group-hover:bg-brand-yellow/10"
                    ></div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div
                            class="relative flex h-14 w-14 items-center justify-center rounded-2xl border border-brand-yellow/20 bg-brand-yellow/10 transition-all duration-500 group-hover:rotate-6 group-hover:bg-brand-yellow/20"
                        >
                            <svg
                                class="h-7 w-7 text-brand-yellow"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-bold tracking-widest text-brand-gray uppercase transition-colors group-hover:text-brand-yellow"
                            >Most Popular</span
                        >
                    </div>
                    <div class="relative z-10">
                        <h3
                            class="mb-2 text-2xl font-extrabold text-white transition-transform duration-500 group-hover:translate-x-1"
                        >
                            Obstetrics & Gynaecology
                        </h3>
                        <p
                            class="mb-5 text-xs leading-relaxed text-brand-gray-light transition-colors group-hover:text-white"
                        >
                            Obstetric, menstrual, contraceptive, and booking
                            histories with AI-generated summaries that capture
                            gravida/para, LMP, EGA, and EDD.
                        </p>
                        <div class="flex items-center gap-6">
                            <div class="group/item flex items-center gap-2">
                                <div
                                    class="h-2 w-2 rounded-full bg-brand-yellow group-hover/item:animate-ping"
                                ></div>
                                <span
                                    class="text-[10px] font-bold text-brand-gray"
                                    >Obstetric History</span
                                >
                            </div>
                            <div class="group/item flex items-center gap-2">
                                <div
                                    class="h-2 w-2 rounded-full bg-brand-yellow/60 group-hover/item:animate-ping"
                                ></div>
                                <span
                                    class="text-[10px] font-bold text-brand-gray"
                                    >Menstrual History</span
                                >
                            </div>
                            <div class="group/item flex items-center gap-2">
                                <div
                                    class="h-2 w-2 rounded-full bg-brand-yellow/30 group-hover/item:animate-ping"
                                ></div>
                                <span
                                    class="text-[10px] font-bold text-brand-gray"
                                    >Booking Details</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    @mousemove="(e) => onCardMouseMove(e, 1)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-4 row-span-1 flex cursor-pointer items-center gap-6 overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-7 transition-all duration-500 hover:-translate-y-1 hover:border-brand-yellow/50 hover:bg-brand-surface/80 active:scale-95 lg:col-span-4"
                >
                    <span
                        class="pointer-events-none absolute top-3 right-4 text-[28px] leading-none font-black text-white/5 select-none"
                        >02</span
                    >

                    <div
                        v-show="hoveredCard === 1"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(500px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(244,253,59,0.06), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 transition-all group-hover:scale-110 group-hover:border-brand-yellow/30"
                    >
                        <svg
                            class="h-6 w-6 text-white/60 transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3
                            class="mb-1 text-sm font-bold text-white transition-colors group-hover:text-brand-yellow"
                        >
                            Paediatrics
                        </h3>
                        <p
                            class="truncate text-[11px] leading-relaxed text-brand-gray"
                        >
                            Pre-natal to post-natal history, developmental
                            milestones, immunisation, nutrition, and growth
                            tracking.
                        </p>
                    </div>
                    <svg
                        class="arrow-fade ml-auto h-5 w-5 shrink-0 transform text-brand-border transition-all duration-500 group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-brand-yellow"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </div>

                <div
                    @mousemove="(e) => onCardMouseMove(e, 2)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-4 row-span-1 flex cursor-pointer flex-col justify-between overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-7 transition-all duration-500 hover:-translate-y-1 hover:border-brand-yellow/50 active:scale-95 lg:col-span-3"
                >
                    <span
                        class="pointer-events-none absolute top-2 right-3 text-[24px] leading-none font-black text-white/5 select-none"
                        >03</span
                    >

                    <div
                        v-show="hoveredCard === 2"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(400px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(244,253,59,0.06), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="relative flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5 transition-all group-hover:rotate-12 group-hover:border-brand-yellow/30"
                    >
                        <svg
                            class="h-5 w-5 text-white/60 transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M7.848 8.25l1.536.887M7.848 8.25a3 3 0 1 1 -5.196 -3 3 3 0 0 1 5.196 3zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1 -5.196 3 3 3 0 0 1 5.196 -3zm1.536-.887a2.165 2.165 0 0 0 1.083 -1.838c.005-.352.054-.696.14-1.025m-1.223 2.863l2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068 -1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.331 4.331 0 0 0 10.607 12m3.736 0l7.794 4.5-.802.215a4.5 4.5 0 0 1 -2.48-.043l-5.326-1.629a4.324 4.324 0 0 1 -2.068 -1.379M14.343 12l-2.882 1.664"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="text-sm font-bold text-white transition-colors group-hover:text-brand-yellow"
                        >
                            Surgery
                        </h3>
                        <p class="text-[10px] text-brand-gray">
                            Surgical history • System exam • Drug review
                        </p>
                    </div>
                </div>

                <div
                    @mousemove="(e) => onCardMouseMove(e, 3)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-4 row-span-1 flex cursor-pointer items-center gap-5 overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-7 transition-all duration-500 hover:-translate-y-1 hover:border-brand-yellow/50 active:scale-95 lg:col-span-7"
                >
                    <span
                        class="pointer-events-none absolute top-3 right-4 text-[28px] leading-none font-black text-white/5 select-none"
                        >04</span
                    >

                    <div
                        v-show="hoveredCard === 3"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(500px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(244,253,59,0.06), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 transition-all group-hover:scale-110 group-hover:border-brand-yellow/30"
                    >
                        <svg
                            class="h-6 w-6 text-white/60 transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 5.607A1.124 1.124 0 0120.109 22H3.892a1.124 1.124 0 01-1.094-1.392L4.2 15.3"
                            />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3
                            class="mb-1 text-sm font-bold text-white transition-colors group-hover:text-brand-yellow"
                        >
                            Internal Medicine
                        </h3>
                        <p class="text-[11px] leading-relaxed text-brand-gray">
                            Presenting complaint, drug & surgical history,
                            system-by-system review, and full examination.
                        </p>
                    </div>
                    <svg
                        class="arrow-fade ml-auto h-5 w-5 shrink-0 transform text-brand-border transition-all duration-500 group-hover:rotate-45 group-hover:text-brand-yellow"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </div>

                <div
                    @mousemove="(e) => onCardMouseMove(e, 4)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-4 row-span-1 flex cursor-pointer items-center gap-6 overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-7 transition-all duration-500 hover:border-brand-yellow/50 active:scale-[0.98] lg:col-span-5"
                >
                    <span
                        class="pointer-events-none absolute top-3 right-4 text-[28px] leading-none font-black text-white/5 select-none"
                        >05</span
                    >

                    <div
                        v-show="hoveredCard === 4"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(500px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(244,253,59,0.06), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 transition-all group-hover:scale-110 group-hover:border-brand-yellow/30"
                    >
                        <svg
                            class="h-6 w-6 text-white/60 transition-colors group-hover:text-brand-yellow"
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
                    <div class="min-w-0 flex-1">
                        <h3
                            class="mb-1 text-sm font-bold text-white transition-colors group-hover:text-brand-yellow"
                        >
                            High-Yield Summaries
                        </h3>
                        <p class="text-[11px] leading-relaxed text-brand-gray">
                            AI-generated clinical summaries capturing key
                            findings, gravida/para, and structured SOAP format.
                        </p>
                    </div>
                    <svg
                        class="arrow-fade ml-auto h-5 w-5 shrink-0 transform text-brand-border transition-all duration-500 group-hover:translate-x-1 group-hover:-translate-y-1 group-hover:text-brand-yellow"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                    </svg>
                </div>

                <div
                    @mousemove="(e) => onCardMouseMove(e, 5)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-4 row-span-1 flex cursor-pointer flex-col justify-between overflow-hidden rounded-3xl bg-brand-yellow p-7 shadow-[0_0_40px_rgba(244,253,59,0.1)] transition-all duration-500 hover:scale-105 hover:rotate-1 lg:col-span-4"
                >
                    <span
                        class="pointer-events-none absolute top-2 right-3 text-[24px] leading-none font-black text-black/5 select-none"
                        >06</span
                    >

                    <div
                        v-show="hoveredCard === 5"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(400px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(0,0,0,0.1), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="relative flex h-10 w-10 items-center justify-center rounded-lg bg-black/10"
                    >
                        <svg
                            class="h-5 w-5 text-black"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="text-sm font-extrabold tracking-tight text-black uppercase"
                        >
                            Contribute
                        </h3>
                        <p class="text-[10px] font-bold text-black/60">
                            Help build the open-source question library
                        </p>
                    </div>
                </div>

                <div
                    @mousemove="(e) => onCardMouseMove(e, 6)"
                    @mouseleave="onCardMouseLeave"
                    class="group reveal-up relative col-span-2 row-span-1 flex cursor-pointer flex-col justify-between overflow-hidden rounded-3xl border border-brand-border bg-brand-surface p-7 transition-all duration-500 hover:-translate-y-1 hover:border-brand-yellow/50 active:scale-95 lg:col-span-3"
                >
                    <span
                        class="pointer-events-none absolute top-2 right-3 text-[24px] leading-none font-black text-white/5 select-none"
                        >07</span
                    >

                    <div
                        v-show="hoveredCard === 6"
                        class="pointer-events-none absolute inset-0 transition-opacity duration-300"
                        :style="{
                            background: `radial-gradient(400px circle at ${cursorPos.x}% ${cursorPos.y}%, rgba(244,253,59,0.06), transparent 50%)`,
                        }"
                    ></div>

                    <div
                        class="relative flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5 transition-all group-hover:rotate-6 group-hover:border-brand-yellow/30"
                    >
                        <svg
                            class="h-5 w-5 text-white/60 transition-colors group-hover:text-brand-yellow"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M12 19h.01"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="text-sm font-bold text-white transition-colors group-hover:text-brand-yellow"
                        >
                            Complaint-Specific
                        </h3>
                        <p class="text-[10px] text-brand-gray">
                            Tailored questions per presenting complaint
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.noise-overlay::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 0;
    opacity: 0.025;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-size: 128px 128px;
}

.noise-overlay::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 0;
    opacity: 0.06;
    pointer-events: none;
    background-image:
        linear-gradient(
            to right,
            rgba(244, 253, 59, 0.15) 1px,
            transparent 1px
        ),
        linear-gradient(
            to bottom,
            rgba(244, 253, 59, 0.15) 1px,
            transparent 1px
        );
    background-size: 5rem 5rem;
    mask-image: linear-gradient(
        to bottom,
        transparent,
        black 15%,
        black 85%,
        transparent
    );
    -webkit-mask-image: linear-gradient(
        to bottom,
        transparent,
        black 15%,
        black 85%,
        transparent
    );
}

.arrow-fade {
    opacity: 0.4;
    transition:
        opacity 0.4s ease,
        transform 0.5s ease,
        color 0.5s ease;
}

.group:hover .arrow-fade {
    opacity: 1;
}
</style>
