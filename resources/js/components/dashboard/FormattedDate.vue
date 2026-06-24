<script setup lang="ts">
import { icons } from '@/data/dashboard.js'

const date = new Date(Date.now())

const options = {
    weekday: 'long',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
} as Intl.DateTimeFormatOptions
const formattedDate = date.toLocaleDateString('en-GB', options)
</script>
<template>
    <div class="flex items-center gap-2 text-[13px] text-brand-gray-light">
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="h-4 w-4 text-brand-yellow/70"
        >
            <path :d="icons.stethoscope" />
        </svg>
        <span>{{ formattedDate }}</span>
    </div>
</template>
