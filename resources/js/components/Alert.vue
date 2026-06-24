<script setup lang="ts">
import { ucfirst } from '@/data/functions';

defineProps<{
    message: string
    type: 'info' | 'warning' | 'success' | 'danger'
}>()
</script>

<template>
    <div
        class="flex items-start gap-3 rounded-full border border-brand-border bg-brand-surface px-4 py-3 text-sm"
        role="alert"
    >
        <svg
            class="mt-0.5 h-5 w-5 shrink-0 text-brand-yellow"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path
                fill-rule="evenodd"
                d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9zm1-5a1.25 1.25 0 100 2.5A1.25 1.25 0 0010 4z"
                clip-rule="evenodd"
            />
        </svg>
        <div class="text-brand-gray-light">
            <span class="font-medium text-brand-yellow"
                >{{ ucfirst(type) }}:</span
            >
            {{ message }}
        </div>
    </div>
</template>
