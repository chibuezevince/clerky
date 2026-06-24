<script setup lang="ts">
import { LoaderCircle } from '@lucide/vue'

withDefaults(
    defineProps<{
        processing?: boolean
        text?: string
        loadingText?: string
    }>(),
    {
        processing: false,
        text: 'Submit',
        loadingText: 'Loading',
    },
)
</script>

<template>
    <button
        type="submit"
        :disabled="processing"
        class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-brand-yellow py-3.5 font-semibold text-black transition-colors hover:bg-[#e4eb29] disabled:opacity-50"
    >
        <span
            v-if="processing"
            class="flex items-center gap-2"
        >
            <LoaderCircle class="h-4 w-4 animate-spin" /> {{ loadingText }}...
        </span>
        <span v-else> {{ text }} </span>
    </button>
</template>
