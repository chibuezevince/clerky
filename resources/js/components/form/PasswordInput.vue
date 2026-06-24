<script setup lang="ts">
import { ref } from 'vue'
import { EyeIcon, EyeOffIcon } from '@lucide/vue'
import MessageBadge from './MessageBadge.vue'

defineProps<{
    label: string
    placeholder: string
    modelValue?: string
}>()

const showPassword = ref<boolean>(false)

defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()
</script>
<template>
    <div>
        <label class="text-sm text-brand-gray-light">{{ label }}</label>
        <div class="relative">
            <input
                v-bind="$attrs"
                :type="showPassword ? 'text' : 'password'"
                :placeholder="placeholder"
                class="w-full rounded-lg border border-brand-border bg-brand-bg py-3 pr-10 pl-4 font-mono text-sm text-white placeholder-brand-gray transition-all outline-none focus:border-brand-yellow focus:ring-1 focus:ring-brand-yellow"
                @input="
                    $emit(
                        'update:modelValue',
                        ($event.target as HTMLInputElement).value,
                    )
                "
            />

            <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute top-1/2 right-3 -translate-y-1/2 text-brand-gray hover:text-white"
            >
                <EyeOffIcon v-if="showPassword" />
                <EyeIcon v-else />
            </button>
        </div>
        <MessageBadge
            :message="$attrs.error as string"
            variant="error"
        />
    </div>
</template>
