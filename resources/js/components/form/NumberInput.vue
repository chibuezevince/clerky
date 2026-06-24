<script setup lang="ts">
import { ref, useAttrs, watch } from 'vue'
import MessageBadge from './MessageBadge.vue'

defineOptions({ inheritAttrs: false })

const attrs = useAttrs()
const props = withDefaults(
    defineProps<{
        label?: string | null
        placeholder?: string
        modelValue?: number | string | null
        min?: number
        max?: number
    }>(),
    {
        min: 1,
        max: 100,
        placeholder: '',
        label: null,
    },
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: number | string | null): void
}>()

const isFocused = ref(false)

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement
    let value = target.value

    if (value === '') {
        emit('update:modelValue', null)
        return
    }

    const numValue = Number(value)
    if (!isNaN(numValue) && props.max !== undefined && numValue > props.max) {
        value = props.max.toString()
        target.value = value
    }

    emit('update:modelValue', value)
}

const isAnimating = ref(false)
const triggerAnimation = () => {
    isAnimating.value = true
    setTimeout(() => {
        isAnimating.value = false
    }, 150)
}

watch(
    () => props.modelValue,
    (newVal, oldVal) => {
        if (newVal !== oldVal) {
            triggerAnimation()
        }
    },
)
</script>

<template>
    <div class="flex flex-col space-y-2">
        <label
            v-if="label"
            class="text-sm font-medium transition-colors duration-300"
            :class="[isFocused ? 'text-brand-yellow' : 'text-brand-gray-light']"
        >
            {{ label }}
        </label>

        <div
            class="group relative flex items-center overflow-hidden rounded-xl border transition-all duration-300 ease-out"
            :class="[
                isFocused
                    ? 'border-brand-yellow bg-brand-bg/90 shadow-[0_0_15px_rgba(234,179,8,0.15)] ring-1 ring-brand-yellow/50'
                    : 'border-brand-border bg-brand-bg hover:border-brand-gray/80 hover:bg-brand-bg/70',
                $attrs.error
                    ? 'border-red-500 shadow-[0_0_15px_rgba(239,68,68,0.15)] ring-red-500/50'
                    : '',
            ]"
        >
            <div
                class="relative flex h-full flex-1 items-center justify-center"
            >
                <div
                    class="absolute inset-0 bg-brand-yellow/10 transition-opacity duration-300"
                    :class="[isAnimating ? 'opacity-100' : 'opacity-0']"
                ></div>

                <input
                    v-bind="$attrs"
                    type="number"
                    :placeholder="placeholder"
                    :min="min"
                    :max="max"
                    :value="modelValue"
                    @focus="isFocused = true"
                    @blur="isFocused = false"
                    @input="handleInput"
                    class="relative z-10 h-12 w-full appearance-none bg-transparent px-4 text-center font-mono text-xl font-bold text-white transition-all duration-150 ease-[cubic-bezier(0.34,1.56,0.64,1)] outline-none placeholder:font-sans placeholder:text-sm placeholder:font-normal placeholder:text-brand-gray/50"
                    :class="[
                        isAnimating
                            ? 'scale-105 text-brand-yellow'
                            : 'scale-100 text-white',
                    ]"
                />
            </div>
        </div>

        <MessageBadge
            v-if="$attrs.error"
            :message="$attrs.error as string"
            variant="error"
        />
    </div>
</template>

<style scoped>
input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type='number'] {
    appearance: textfield;
}
</style>
