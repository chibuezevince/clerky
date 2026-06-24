<script setup lang="ts">
import { onMounted } from 'vue'
import { computed, useAttrs } from 'vue'

defineOptions({ inheritAttrs: false })

const props = defineProps<{
    modelValue: string
}>()

defineEmits(['update:modelValue'])

const attrs = useAttrs()
const charLimit = computed(() => attrs.maxlength as number | undefined)
const isNearLimit = computed(
    () => charLimit.value && props.modelValue.length > charLimit.value * 0.85,
)
</script>

<template>
    <div class="flex flex-col gap-1.5">
        <textarea
            :value="modelValue"
            v-bind="attrs"
            @input="
                $emit(
                    'update:modelValue',
                    ($event.target as HTMLTextAreaElement).value,
                )
            "
            class="custom-scroll relative h-40 w-full resize-none rounded-[20px] border border-white/6 bg-white/2 p-5 text-base text-white transition-all duration-300 placeholder:text-white/10 focus:border-brand-yellow/40 focus:bg-white/4 focus:ring-0 focus:outline-none md:p-8 md:text-lg xl:text-xl"
        ></textarea>

        <div class="flex justify-end">
            <span
                class="font-mono text-xs transition-colors duration-200"
                :class="isNearLimit ? 'text-brand-yellow' : 'text-brand-gray'"
            >
                {{ modelValue.length }} / {{ charLimit }}
            </span>
        </div>
    </div>
</template>
