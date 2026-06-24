<script setup lang="ts">
import { redirect } from '@/routes/clerk'
import type { ClerkingUnit } from '@/types/dashboard'
import { Link } from '@inertiajs/vue3'
import { ChevronRight } from '@lucide/vue'

defineProps<{
    unit: ClerkingUnit
    index: number
    variant?: 'mobile' | 'desktop'
}>()
</script>

<template>
    <Link
        v-if="variant === 'desktop'"
        :href="redirect(unit.slug)"
        view-transition
        prefetch
        class="animate-slide-up group relative flex items-center gap-6 rounded-4xl border border-white/5 bg-white/3 p-8 text-left transition-all duration-500 hover:-translate-y-1 hover:border-brand-yellow/25 hover:bg-white/5"
        :style="{ animationDelay: `${(index + 1) * 100}ms` }"
    >
        <div
            class="absolute -inset-4 -z-10 rounded-[50px] bg-brand-yellow/0 blur-3xl transition-all duration-700 group-hover:scale-105 group-hover:bg-brand-yellow/4"
        ></div>

        <div
            class="absolute inset-x-12 top-0 h-px bg-linear-to-r from-transparent via-brand-yellow/20 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
        ></div>

        <div
            class="relative flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl border border-white/10 bg-neutral-900/60 shadow-inner transition-all duration-500 group-hover:scale-105 group-hover:border-brand-yellow/20 group-hover:bg-brand-yellow/5"
        >
            <component
                :is="unit.icon"
                class="relative h-9 w-9 text-brand-yellow/70 transition-all duration-500 group-hover:text-brand-yellow group-hover:drop-shadow-[0_0_12px_rgba(244,253,59,0.3)]"
                stroke-width="1.2"
            />
            <div
                class="absolute inset-0 rounded-3xl bg-brand-yellow/0 blur-xl transition-all duration-500 group-hover:bg-brand-yellow/10"
            ></div>
        </div>

        <div class="flex flex-col gap-4">
            <h3
                class="text-xl font-bold tracking-tight text-white/90 transition-colors duration-300 group-hover:text-white"
            >
                {{ unit.name }}
            </h3>
            <p
                class="max-w-sm text-sm leading-relaxed text-brand-gray-light/50 transition-colors duration-300 group-hover:text-brand-gray-light/80"
            >
                {{ unit.description }}
            </p>
        </div>

        <div
            class="absolute right-10 bottom-10 h-2 w-2 rounded-full bg-brand-yellow opacity-0 transition-all duration-500 group-hover:scale-[1.5] group-hover:opacity-100 group-hover:shadow-[0_0_12px_#f4fd3b]"
        ></div>
    </Link>

    <Link
        v-if="variant === 'mobile'"
        :key="unit.id"
        :href="redirect(unit.slug)"
        view-transition
        prefetch
        class="animate-slide-up group relative flex items-center gap-5 rounded-2xl border border-white/5 bg-white/4 p-5 transition-all duration-300 hover:bg-white/6 active:scale-[0.98]"
        :style="{ animationDelay: `${(index + 1) * 80}ms` }"
    >
        <div
            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-neutral-900/40 text-brand-yellow transition-all group-hover:border-brand-yellow/20"
        >
            <component
                :is="unit.icon"
                class="h-8 w-8"
                stroke-width="1.5"
            />
        </div>

        <div class="flex flex-1 flex-col gap-1 text-left">
            <h3
                class="text-lg font-bold text-white transition-colors group-hover:text-brand-yellow"
            >
                {{ unit.name }}
            </h3>
            <p class="text-xs text-brand-gray-light/60">
                {{ unit.description }}
            </p>
        </div>

        <ChevronRight
            class="h-6 w-6 text-brand-gray/20 transition-all group-hover:translate-x-1 group-hover:text-brand-yellow"
        />
    </Link>
</template>
