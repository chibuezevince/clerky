<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const props = withDefaults(
    defineProps<{
        animation?: 'up' | 'left' | 'right' | 'scale' | 'rotate' | 'stagger'
        threshold?: number
        rootMargin?: string
    }>(),
    {
        animation: 'up',
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px',
    },
)

const el = ref<HTMLElement>()
const revealed = ref(false)

let observer: IntersectionObserver | null = null

onMounted(() => {
    if (!el.value) return

    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                revealed.value = true
                observer?.unobserve(entry.target)
            }
        },
        { threshold: props.threshold, rootMargin: props.rootMargin },
    )

    observer.observe(el.value)
})

onUnmounted(() => {
    observer?.disconnect()
})
</script>

<template>
    <div
        ref="el"
        :class="{ revealed }"
    >
        <slot />
    </div>
</template>
