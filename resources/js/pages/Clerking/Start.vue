<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import type { Component } from 'vue'

import { Baby, Venus, Stethoscope, Scissors, HeartPulse } from '@lucide/vue'
import { ClerkingUnit } from '@/types/dashboard'
import SelectCard from '@/components/clerking/SelectCard.vue'
import { iconMap } from '@/data/constants'

defineOptions({
    layout: AppLayout,
})

let units = usePage().props.units as ClerkingUnit[]

units = units.map((unit: ClerkingUnit) => ({
    ...unit,
    icon: unit.icon ? (iconMap[unit.icon as string] ?? null) : null,
}))
</script>

<template>
    <Head title="Start Clerking" />

    <main class="mt-5 col-span-1 min-h-[calc(100vh-200px)] min-[1101px]:col-span-2">
        <section
            :class="[
                glass,
                'group/container h-[95vh] relative flex flex-col items-center px-6 py-6 lg:justify-center lg:px-12 lg:py-12',
            ]"
        >
            <div
                class="pointer-events-none absolute inset-0 overflow-hidden rounded-[inherit]"
            >
                <div
                    class="animate-drift-slow absolute -top-24 -left-24 h-96 w-96 rounded-full bg-brand-yellow/5 blur-[100px]"
                ></div>
                <div
                    class="animate-drift-slow-reverse absolute -right-24 -bottom-24 h-96 w-96 rounded-full bg-teal-400/5 blur-[100px]"
                ></div>
            </div>

            <div class="relative z-10 flex w-full flex-col items-center">
                <div
                    class="animate-slide-up mb-8 max-w-2xl text-center lg:mb-10"
                >
                    <h1
                        class="text-2xl font-black tracking-tight text-white sm:text-3xl lg:text-[2.5rem]"
                    >
                        What would you like <br class="hidden lg:block" />
                        to clerk today?
                    </h1>
                    <p
                        class="mx-auto mt-4 max-w-md text-sm font-medium text-brand-gray-light opacity-70 lg:text-base"
                    >
                        Select a clinical unit to begin your patient assessment.
                    </p>
                </div>

                <div class="hidden w-full max-w-5xl grid-cols-2 gap-6 lg:grid">
                    <SelectCard
                        v-for="(unit, index) in units"
                        :key="unit.id"
                        :unit="unit"
                        :index="index"
                        variant="desktop"
                    />
                </div>

                <div class="flex w-full flex-col gap-4 lg:hidden">
                    <SelectCard
                        v-for="(unit, index) in units"
                        :key="unit.id"
                        :unit="unit"
                        :index="index"
                        variant="mobile"
                    />
                </div>
            </div>
        </section>
    </main>
</template>

<style scoped>
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes drift {
    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -20px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 30px) scale(0.95);
    }
}

.animate-slide-up {
    animation: slide-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.animate-drift-slow {
    animation: drift 15s ease-in-out infinite;
}

.animate-drift-slow-reverse {
    animation: drift 18s ease-in-out infinite reverse;
}
</style>
