<script setup lang="ts">
import { UnitQuestion } from '@/types/dashboard'
import { computed } from 'vue'

const props = defineProps<{
    dependentQuestion: UnitQuestion
    dependsOnAnswer?: string
}>()

const hasOptions = computed(
    () =>
        ['select', 'multi_select'].includes(
            props.dependentQuestion.input_type,
        ) && props.dependentQuestion.options,
)

const isBoolean = computed(
    () => props.dependentQuestion.input_type === 'boolean',
)
const isScale = computed(() => props.dependentQuestion.input_type === 'scale')

const dependsOnAnswerLabel = computed(() => {
    if (!props.dependsOnAnswer) return '—'

    if (hasOptions.value && props.dependentQuestion.options) {
        return (
            props.dependentQuestion.options[props.dependsOnAnswer] ??
            props.dependsOnAnswer
        )
    }

    return props.dependsOnAnswer
})
</script>

<template>
    <div class="rounded-xl border border-brand-border bg-brand-bg p-3">
        <div class="mb-2 flex items-center justify-between">
            <span
                class="text-[10px] font-semibold tracking-widest text-white/30 uppercase"
            >
                Depends on
            </span>
            <span
                class="rounded-md border border-brand-yellow/20 bg-brand-yellow/8 px-2 py-0.5 font-mono text-[10px] font-semibold text-brand-yellow"
            >
                {{ dependentQuestion.input_type }}
            </span>
        </div>

        <p class="mb-3 text-sm leading-snug font-medium text-white/80">
            {{ dependentQuestion.question }}
        </p>

        <div
            v-if="hasOptions"
            class="mb-3 flex flex-wrap gap-1.5"
        >
            <span
                v-for="(label, key) in dependentQuestion.options"
                :key="key"
                class="rounded-md border px-2 py-0.5 font-mono text-[11px] transition-colors"
                :class="
                    dependsOnAnswer === String(key)
                        ? 'border-brand-yellow/30 bg-brand-yellow/10 text-brand-yellow'
                        : 'border-white/8 bg-white/5 text-white/50'
                "
            >
                {{ label }}
            </span>
        </div>

        <div
            v-else-if="isBoolean"
            class="mb-3 flex gap-1.5"
        >
            <span
                v-for="opt in ['Yes', 'No']"
                :key="opt"
                class="rounded-md border px-2 py-0.5 font-mono text-[11px] transition-colors"
                :class="
                    dependsOnAnswer === opt
                        ? 'border-brand-yellow/30 bg-brand-yellow/10 text-brand-yellow'
                        : 'border-white/8 bg-white/5 text-white/50'
                "
            >
                {{ opt }}
            </span>
        </div>

        <div
            v-else-if="isScale"
            class="mb-3"
        >
            <div class="flex items-center gap-2">
                <span class="font-mono text-[11px] text-white/30">0</span>
                <div class="relative h-1 flex-1 rounded-full bg-white/8">
                    <div
                        class="absolute inset-y-0 left-0 w-1/2 rounded-full bg-brand-yellow/40"
                    />
                </div>
                <span class="font-mono text-[11px] text-white/30">10</span>
            </div>
        </div>

        <div class="flex items-center gap-1.5 border-t border-white/5 pt-2.5">
            <span class="text-[11px] text-white/30">when answer is</span>
            <span
                class="rounded-md bg-white/8 px-2 py-0.5 font-mono text-[11px] text-white/70"
            >
                {{ dependsOnAnswerLabel || '—' }}
            </span>
        </div>
    </div>
</template>
