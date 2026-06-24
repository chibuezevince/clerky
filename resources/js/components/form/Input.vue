<script setup lang="ts">
import { ref, watch } from 'vue'
import MessageBadge from './MessageBadge.vue'

const props = withDefaults(
    defineProps<{
        label: string
        placeholder?: string
        type?: string
        modelValue?: string | number | null
    }>(),
    {
        type: 'text',
        placeholder: '',
    },
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const internalValue = ref(
    props.modelValue !== undefined && props.modelValue !== null
        ? String(props.modelValue)
        : '',
)

watch(
    () => props.modelValue,
    (val) => {
        if (val !== undefined && val !== null) internalValue.value = String(val)
    },
)

const onInput = (event: Event) => {
    const value = (event.target as HTMLInputElement).value
    internalValue.value = value
    emit('update:modelValue', value)
}
</script>

<template>
    <div>
        <label class="text-sm text-brand-gray-light">{{ label }}</label>
        <input
            v-bind="$attrs"
            :type="type"
            :placeholder="placeholder"
            :value="internalValue"
            @input="onInput"
            class="mt-1 w-full rounded-lg border border-brand-border bg-brand-bg px-4 py-3 font-mono text-sm text-white placeholder-brand-gray transition-all outline-none focus:border-brand-yellow focus:ring-1 focus:ring-brand-yellow"
        />

        <MessageBadge
            :message="$attrs.error as string"
            variant="error"
        />
    </div>
</template>
