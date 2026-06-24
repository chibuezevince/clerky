<script setup lang="ts">
import type { RegisterStep } from '@/types/component-props'
import { CheckCircle } from '@lucide/vue'

defineProps<RegisterStep>()
</script>

<template>
    <div
        class="relative flex w-full flex-col gap-1 rounded-xl border p-4 transition-colors duration-300"
        :class="{
            'border-brand-yellow/50 bg-brand-yellow/5': active,
            'border-brand-border bg-brand-bg/50': !active,
            'opacity-60': done && !active,
        }"
    >
        <div
            class="absolute top-1/2 -left-7.5 z-10 flex h-4 w-4 -translate-y-1/2 items-center justify-center rounded-full border-[3px] transition-all duration-300"
            :class="{
                'border-brand-yellow bg-brand-yellow shadow-[0_0_10px_rgba(244,253,59,0.5)]':
                    done,
                'border-brand-yellow': active && !done,
                'border-brand-border bg-brand-bg': !active && !done,
            }"
        >
            <CheckCircle
                v-if="done"
                class="h-2.5 w-2.5 text-black"
            />
        </div>

        <h3
            class="mb-1 flex items-center gap-2 text-sm font-medium transition-colors"
            :class="active || done ? 'text-white' : 'text-brand-gray'"
        >
            {{ title }}
        </h3>

        <p class="text-xs text-brand-gray">{{ description }}</p>
    </div>
</template>
