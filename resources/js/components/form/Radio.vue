<script setup lang="ts">
withDefaults(
    defineProps<{
        label?: string
        modelValue?: boolean
    }>(),
    {
        modelValue: false,
    },
)

defineEmits<{
    (e: 'update:modelValue', value: boolean): void
}>()
</script>

<template>
    <label
        class="group inline-flex w-full cursor-pointer items-center justify-center gap-3 select-none"
    >
        <div class="relative flex shrink-0 items-center justify-center">
            <input
                v-bind="$attrs"
                type="radio"
                class="peer sr-only"
                :checked="modelValue"
                @input="
                    $emit(
                        'update:modelValue',
                        ($event.target as HTMLInputElement).checked,
                    )
                "
            />

            <div
                class="h-5 w-5 rounded-full border-2 border-brand-border bg-brand-bg transition-all duration-200 group-hover:border-brand-yellow/40 peer-checked:border-brand-yellow peer-focus-visible:ring-2 peer-focus-visible:ring-brand-yellow/30 peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-brand-bg"
            />

            <div
                class="pointer-events-none absolute h-2.5 w-2.5 scale-0 rounded-full bg-brand-yellow opacity-0 transition-all duration-200 ease-out peer-checked:scale-100 peer-checked:opacity-100"
            />
        </div>

        <span
            v-if="label"
            class="text-sm text-brand-gray-light transition-colors duration-200 group-hover:text-white"
        >
            {{ label }}
        </span>
    </label>
</template>
