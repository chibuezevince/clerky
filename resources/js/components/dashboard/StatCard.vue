<script setup lang="ts">
import type { Component } from 'vue'

defineProps<{
    title: string
    value: string
    delta: number
    icon: Component
}>()
</script>
<template>
    <div
        class="group flex items-center gap-4 rounded-2xl border border-white/5 bg-white/2 p-3.5 shadow-sm transition-all hover:border-white/10 hover:bg-white/4"
    >
        <div
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-yellow shadow-[0_0_15px_rgba(244,253,59,0.2)] transition-all group-hover:shadow-[0_0_20px_rgba(244,253,59,0.3)]"
        >
            <component
                :is="icon"
                class="h-5 w-5 text-brand-bg"
                stroke-width="2.5"
            />
        </div>

        <div class="flex min-w-0 flex-col">
            <span
                class="truncate text-[10px] font-bold tracking-wider text-brand-gray uppercase"
            >
                {{ title }}
            </span>
            <div class="flex items-center gap-2">
                <span
                    class="truncate text-xl font-extrabold tracking-tight text-white"
                >
                    {{ value }}
                </span>
                <div
                    v-if="delta !== 0"
                    class="flex items-center text-[10px] font-bold"
                    :class="delta > 0 ? 'text-teal-400' : 'text-red-400'"
                >
                    {{ delta > 0 ? '↑' : '↓' }}{{ Math.abs(delta) }}%
                </div>
            </div>
        </div>
    </div>
</template>
