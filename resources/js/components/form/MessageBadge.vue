<script setup lang="ts">
import { CheckCircle2, CircleX, Info, TriangleAlert } from '@lucide/vue'

const variants = {
    error: {
        icon: CircleX,
        class: 'bg-red-500/15 text-red-400',
    },
    success: {
        icon: CheckCircle2,
        class: 'bg-green-500/15 text-green-400',
    },
    warning: {
        icon: TriangleAlert,
        class: 'bg-yellow-500/15 text-yellow-400',
    },
    info: {
        icon: Info,
        class: 'bg-blue-500/15 text-blue-400',
    },
}

defineProps<{
    message?: string
    variant: keyof typeof variants
}>()
</script>

<template>
    <span
        v-if="message"
        v-bind="$attrs"
        class="mt-1 mb-3 flex items-center gap-1 rounded-md px-2.5 py-1 text-xs font-medium"
        :class="variants[variant].class"
    >
        <component
            :is="variants[variant].icon"
            :size="15"
        />
        {{ message }}
    </span>
</template>
