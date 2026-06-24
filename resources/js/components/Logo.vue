<script setup lang="ts">
withDefaults(
    defineProps<{
        showLabel?: boolean
        showBlur?: boolean
    }>(),
    {
        showLabel: true,
        showBlur: true
    },
)
</script>
<template>
    <div
        :class="['absolute inset-0 rounded-full transition-all duration-300', showBlur && 'group-hover:bg-brand-yellow/40 bg-brand-yellow/20 blur-md']"
    ></div>
    <div
        class="relative flex h-9 w-9 items-center justify-center rounded-xl border border-white/20 bg-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.35)] backdrop-blur-md transition-all duration-300 group-hover:scale-105 group-hover:-rotate-3"
    >
        <div
            class="absolute inset-0 rounded-xl bg-brand-yellow/20 opacity-70 blur-md"
        ></div>

        <div
            class="absolute inset-0 rounded-xl bg-linear-to-br from-white/30 via-white/10 to-transparent"
        ></div>

        <svg
            class="relative h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
        >
            <rect
                x="4"
                y="3"
                width="13"
                height="18"
                rx="2"
                class="fill-white/5 stroke-white/70"
                stroke-width="1.5"
            />

            <rect
                x="8.5"
                y="1"
                width="5"
                height="3.2"
                rx="1"
                class="fill-white/10 stroke-white/60"
                stroke-width="0.8"
            />

            <line
                x1="6.5"
                y1="8"
                x2="14.5"
                y2="8"
                class="stroke-white/40"
                stroke-width="1"
                stroke-linecap="round"
            />

            <path
                d="M5.5 13.5 H8 L9.5 10.5 L11.5 16 L13 13.5 H15.5"
                class="stroke-brand-yellow"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <line
                x1="6.5"
                y1="18.5"
                x2="14.5"
                y2="18.5"
                class="stroke-white/40"
                stroke-width="1"
                stroke-linecap="round"
            />

            <path
                d="M19.5 2.5 L20.3 4.2 L22 4.5 L20.3 4.8 L19.5 6.5 L18.7 4.8 L17 4.5 L18.7 4.2 Z"
                class="fill-brand-yellow"
            />
        </svg>
    </div>
    <span
        class="relative text-2xl font-extrabold tracking-tight text-white"
        :class="!showLabel && 'hidden sm:block'"
    >
        Clerky<span class="text-brand-yellow">.</span>
    </span>
    <span
        class="mt-2 self-start text-[10px] font-semibold tracking-wider text-brand-yellow/50"
    >
        beta
    </span>
</template>
