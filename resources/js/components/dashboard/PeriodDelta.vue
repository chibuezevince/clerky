<script setup lang="ts">
import { ArrowDown, ArrowUp } from '@lucide/vue'

withDefaults(defineProps<{ data: number | null }>(), {
    data: null,
})
</script>
<template>
    <div
        class="mt-1 flex items-center gap-1 text-xs"
        v-if="data"
        :class="Number(data) > 0 ? 'text-teal-300' : 'text-orange-400'"
    >
        <ArrowUp
            v-if="Number(data) > 0"
            class="h-3 w-3"
        />
        <ArrowDown
            v-else
            class="h-3 w-3"
        />
        {{ data }}%
    </div>
</template>
