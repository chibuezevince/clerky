<script setup lang="ts">
import { ref, nextTick, computed, watch } from 'vue'

const props = withDefaults(
    defineProps<{
        digits?: number
    }>(),
    {
        digits: 4,
    },
)

const emit = defineEmits<{
    (e: 'update:code', value: string): void
}>()

const values = ref<string[]>(Array(props.digits).fill(''))
const inputs = ref<HTMLInputElement[]>([])
const code = computed(() => values.value.join(''))

watch(code, (value) => emit('update:code', value))

const setInputRef = (el: HTMLInputElement, index: number) =>
    (inputs.value[index] = el)

const onInput = (e: Event, index: number) => {
    const target = e.target as HTMLInputElement
    const value = target.value.replace(/\D/g, '')

    if (!value) {
        values.value[index] = ''
        return
    }

    values.value[index] = value[0]

    if (index < props.digits - 1) {
        nextTick(() => inputs.value[index + 1]?.focus())
    }
}

function onKeydown(e: KeyboardEvent, index: number) {
    if (e.key === 'Backspace' && !values.value[index] && index > 0) {
        nextTick(() => inputs.value[index - 1]?.focus())
    }
}

function onPaste(e: ClipboardEvent) {
    e.preventDefault()
    const paste = e.clipboardData?.getData('text') || ''
    const digits = paste.replace(/\D/g, '').slice(0, props.digits).split('')

    digits.forEach((digit, i) => {
        values.value[i] = digit
    })

    nextTick(() => {
        const last = Math.min(digits.length, props.digits) - 1
        if (last >= 0) inputs.value[last]?.focus()
    })
}
</script>

<template>
    <div class="flex gap-3">
        <input
            v-for="(_, index) in digits"
            v-bind="$attrs"
            :key="index"
            :ref="(el) => setInputRef(el as HTMLInputElement, index)"
            v-model="values[index]"
            maxlength="1"
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            @input="(e) => onInput(e, index)"
            @keydown="(e) => onKeydown(e, index)"
            @paste="onPaste"
            class="h-16 w-16 rounded-lg border border-[#232834] bg-brand-bg text-center text-2xl font-bold text-white caret-[#F4FD3B] transition-all duration-200 outline-none hover:border-[#2f3542] focus:border-[#F4FD3B] focus:ring-1 focus:ring-[#F4FD3B]/40"
        />
    </div>
</template>
