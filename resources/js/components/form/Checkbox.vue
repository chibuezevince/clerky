<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        modelValue?: boolean | string | number | null
    }>(),
    {
        modelValue: null,
    },
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean | string | number | null): void
}>()

const isYes = () => props.modelValue === 'yes'

const isNo = () => props.modelValue === 'no'

const select = (value: 'yes' | 'no' | null) => {
    emit('update:modelValue', value)
}
</script>

<template>
    <div
        class="mx-auto flex w-fit items-center justify-center gap-2 rounded-2xl border border-brand-border bg-brand-surface p-1.5 transition-shadow duration-200 focus-within:ring-2 focus-within:ring-brand-yellow/40"
    >
        <button
            type="button"
            class="relative min-w-20 rounded-xl px-8 py-3.5 text-center text-base font-bold tracking-wide transition-all duration-300 ease-out"
            :class="
                isNo()
                    ? 'bg-red-500/20 text-red-400 shadow-[0_0_12px_rgba(239,68,68,0.15)]'
                    : 'text-brand-gray-light hover:bg-white/4 hover:text-white'
            "
            @click="select('no')"
        >
            No
        </button>

        <button
            type="button"
            class="relative min-w-20 rounded-xl px-8 py-3.5 text-center text-base font-bold tracking-wide transition-all duration-300 ease-out"
            :class="
                !isYes() && !isNo()
                    ? 'bg-white/8 text-brand-gray shadow-[0_0_12px_rgba(255,255,255,0.05)]'
                    : 'text-brand-gray-light hover:bg-white/4 hover:text-white'
            "
            @click="select(null)"
        >
            —
        </button>

        <button
            type="button"
            class="relative min-w-20 rounded-xl px-8 py-3.5 text-center text-base font-bold tracking-wide transition-all duration-300 ease-out"
            :class="
                isYes()
                    ? 'bg-brand-yellow text-black shadow-[0_0_12px_rgba(244,253,59,0.2)]'
                    : 'text-brand-gray-light hover:bg-white/4 hover:text-white'
            "
            @click="select('yes')"
        >
            Yes
        </button>
    </div>
</template>
