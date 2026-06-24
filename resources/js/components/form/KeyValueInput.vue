<script setup lang="ts">
import { computed } from 'vue'
import { X, Plus } from '@lucide/vue'

interface KeyValueRow {
    key: string
    value: string
}

const props = withDefaults(
    defineProps<{
        modelValue: string
        placeholder?: {
            key?: string
            value?: string
        }
        disabled?: boolean
        labels?: {
            key?: string
            value?: string
        }
    }>(),
    {
        placeholder: () => ({ key: 'Key', value: 'Value' }),
        labels: () => ({ key: 'Key', value: 'Value' }),
    },
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

const parseRows = (val: string): KeyValueRow[] => {
    if (!val) return [{ key: '', value: '' }]
    try {
        const parsed = JSON.parse(val)
        if (Array.isArray(parsed) && parsed.length > 0) return parsed
        return [{ key: '', value: '' }]
    } catch {
        return [{ key: '', value: '' }]
    }
}

const rows = computed<KeyValueRow[]>({
    get: () => parseRows(props.modelValue),
    set: (val) => emit('update:modelValue', JSON.stringify(val)),
})

const updateRow = (index: number, field: 'key' | 'value', newValue: string) => {
    const updated = rows.value.map((row, i) =>
        i === index ? { ...row, [field]: newValue } : row,
    )
    rows.value = updated
}

const removeRow = (index: number) => {
    if (rows.value.length <= 1) return
    rows.value = rows.value.filter((_, i) => i !== index)
}

const addRow = () => {
    rows.value = [...rows.value, { key: '', value: '' }]
}
</script>

<template>
    <div class="flex flex-col gap-3">
        <div
            :class="[
                'mx-auto grid w-full max-w-lg items-center gap-2 px-1',
                rows.length > 1 ? 'grid-cols-[1fr_1fr_auto]' : 'grid-cols-2',
            ]"
        >
            <span
                class="text-center text-[10px] font-bold tracking-[0.2em] text-brand-gray uppercase"
                >{{ labels.key }}</span
            >
            <span
                class="text-center text-[10px] font-bold tracking-[0.2em] text-brand-gray uppercase"
                >{{ labels.value }}</span
            >
        </div>
        <div class="flex flex-col items-center gap-2">
            <div
                v-for="(row, index) in rows"
                :key="index"
                class="flex flex-col items-center gap-2"
                :class="index !== rows.length - 1 ? 'mb-4' : 'mb-7'"
            >
                <div
                    :class="[
                        'grid w-full max-w-lg items-center gap-2',
                        rows.length > 1
                            ? 'grid-cols-[1fr_1fr_auto]'
                            : 'grid-cols-2',
                    ]"
                >
                    <input
                        :value="row.key"
                        :placeholder="placeholder.key"
                        :disabled="disabled"
                        @input="
                            updateRow(
                                index,
                                'key',
                                ($event.target as HTMLInputElement).value,
                            )
                        "
                        class="w-full rounded-lg border border-white/6 bg-white/2 px-4 py-3 font-mono text-sm text-white placeholder-brand-gray transition-all duration-300 focus:border-brand-yellow/40 focus:bg-white/4 focus:ring-0 focus:outline-none disabled:cursor-not-allowed disabled:opacity-40"
                    />
                    <div class="relative">
                        <input
                            :value="row.value"
                            :placeholder="placeholder.value"
                            :disabled="disabled"
                            @input="
                                updateRow(
                                    index,
                                    'value',
                                    ($event.target as HTMLInputElement).value,
                                )
                            "
                            class="w-full rounded-lg border border-white/6 bg-white/2 px-4 py-3 font-mono text-sm text-white placeholder-brand-gray transition-all duration-300 focus:border-brand-yellow/40 focus:bg-white/4 focus:ring-0 focus:outline-none disabled:cursor-not-allowed disabled:opacity-40"
                        />
                        <button
                            v-if="index === rows.length - 1"
                            type="button"
                            :disabled="disabled"
                            @click="addRow"
                            class="absolute right-0 -bottom-8 flex h-7 w-20 items-center justify-center gap-1.5 rounded border border-dashed border-white/10 bg-brand-surface text-xs text-brand-gray shadow-lg transition-all duration-200 hover:border-brand-yellow/40 hover:bg-brand-yellow/10 hover:text-brand-yellow disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            Add <Plus :size="12" />
                        </button>
                    </div>
                    <button
                        v-if="rows.length > 1"
                        type="button"
                        :disabled="disabled"
                        @click="removeRow(index)"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-white/6 bg-white/2 text-brand-gray transition-all duration-200 hover:border-red-400/30 hover:bg-red-400/10 hover:text-red-400 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        <X :size="16" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
