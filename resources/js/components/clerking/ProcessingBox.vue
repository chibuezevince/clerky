<script setup lang="ts">
import { glass, processingVerbs } from '@/data/dashboard.js'
import { Brain } from '@lucide/vue'
import { Motion } from 'motion-v'
import { onMounted, onUnmounted, ref } from 'vue'

const showInitialPhrase = ref(true)
const currentVerbIndex = ref(0)
let interval: ReturnType<typeof setInterval> | null = null

onMounted(() => {
    interval = setInterval(() => {
        if (showInitialPhrase.value) {
            showInitialPhrase.value = false
        } else {
            currentVerbIndex.value =
                (currentVerbIndex.value + 1) % processingVerbs.length
        }
    }, 2000)
})

onUnmounted(() => {
    if (interval) {
        clearInterval(interval)
        interval = null
    }
})
</script>

<template>
    <div
        :class="[
            glass,
            'relative flex min-h-110 flex-col items-center justify-center gap-8 overflow-hidden p-6 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] md:p-8 xl:min-h-125 xl:gap-10 xl:p-10',
        ]"
    >
        <div
            class="relative flex h-32 w-32 items-center justify-center md:h-40 md:w-40"
        >
            <div class="animate-spin-slow absolute inset-0">
                <div
                    class="h-full w-full rounded-full border-2 border-transparent border-t-brand-yellow/60 border-r-brand-yellow/20"
                />
            </div>

            <div class="animate-spin-reverse absolute inset-2">
                <div
                    class="h-full w-full rounded-full border-2 border-transparent border-b-brand-yellow/60 border-l-brand-yellow/40"
                />
            </div>

            <div class="animate-spin-faster absolute inset-5">
                <div
                    class="h-full w-full rounded-full border border-transparent border-t-white/30"
                />
            </div>

            <Motion
                :initial="{ scale: 0.8, opacity: 0.6 }"
                :animate="{ scale: 1.2, opacity: 1 }"
                :transition="{
                    repeat: Infinity,
                    duration: 1.5,
                    ease: 'easeInOut',
                    repeatType: 'reverse',
                }"
                class="relative z-10 flex h-12 w-12 items-center justify-center md:h-14 md:w-14"
            >
                <div
                    class="absolute inset-0 rounded-full bg-brand-yellow/20 blur-xl"
                />
                <div
                    class="h-full w-full rounded-full border-2 border-brand-yellow/60 bg-brand-yellow/10 shadow-[0_0_30px_rgba(244,253,59,0.3)]"
                >
                    <div class="flex h-full w-full items-center justify-center">
                        <Brain
                            :size="22"
                            class="text-brand-yellow md:size-6.5"
                        />
                    </div>
                </div>
            </Motion>

            <div class="animate-spin-slower absolute inset-0">
                <div
                    class="absolute top-0 left-1/2 h-2 w-2 -translate-x-1/2 rounded-full bg-brand-yellow shadow-[0_0_10px_rgba(244,253,59,0.8)]"
                />
            </div>
            <div class="animate-spin-reverse-slow absolute inset-0">
                <div
                    class="absolute bottom-0 left-1/2 h-1.5 w-1.5 -translate-x-1/2 rounded-full bg-white/50 shadow-[0_0_8px_rgba(255,255,255,0.4)]"
                />
            </div>
        </div>

        <div class="flex flex-col items-center gap-2 text-center">
            <Motion
                :initial="{ opacity: 0, y: 10 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{ delay: 0.2, duration: 0.6 }"
                class="flex items-center gap-1.5"
            >
                <Motion
                    v-if="showInitialPhrase"
                    :key="'initial'"
                    :initial="{ opacity: 0 }"
                    :animate="{ opacity: 1 }"
                    :transition="{ duration: 0.5 }"
                >
                    <span
                        class="text-lg font-black tracking-tight text-white md:text-xl"
                    >
                        Processing Responses
                    </span>
                </Motion>

                <Motion
                    v-else
                    :key="processingVerbs[currentVerbIndex]"
                    :initial="{ opacity: 0, y: 16, filter: 'blur(6px)' }"
                    :animate="{ opacity: 1, y: 0, filter: 'blur(0px)' }"
                    :transition="{
                        type: 'spring',
                        stiffness: 180,
                        damping: 18,
                    }"
                >
                    <span
                        class="text-lg font-black tracking-tight text-white md:text-xl"
                    >
                        {{ processingVerbs[currentVerbIndex] }}
                    </span>
                </Motion>
                <span class="flex gap-0.5">
                    <Motion
                        v-for="i in 3"
                        :key="i"
                        :animate="{ opacity: [0.3, 1, 0.3] }"
                        :transition="{
                            repeat: Infinity,
                            duration: 1.2,
                            delay: i * 0.2,
                            ease: 'easeInOut',
                        }"
                        class="h-1.5 w-1.5 rounded-full bg-brand-yellow"
                    />
                </span>
            </Motion>

            <Motion
                :initial="{ opacity: 0 }"
                :animate="{ opacity: 1 }"
                :transition="{ delay: 0.4, duration: 0.6 }"
            >
                <p class="max-w-xs text-sm text-white/40">
                    Analyzing and generating the next set of questions based on
                    your responses
                </p>
            </Motion>
        </div>

        <Motion
            :initial="{ opacity: 0 }"
            :animate="{ opacity: 1 }"
            :transition="{ delay: 0.3, duration: 0.8 }"
            class="flex items-center gap-3"
        >
            <div
                class="h-px w-16 bg-linear-to-r from-transparent to-brand-yellow/30"
            />
            <Motion
                :animate="{ scale: [1, 1.2, 1] }"
                :transition="{
                    repeat: Infinity,
                    duration: 2,
                    ease: 'easeInOut',
                }"
                class="h-1.5 w-1.5 rounded-full bg-brand-yellow/60"
            />
            <div
                class="h-px w-16 bg-linear-to-l from-transparent to-brand-yellow/30"
            />
        </Motion>
    </div>
</template>

<style scoped>
@keyframes spin-slow {
    to {
        transform: rotate(360deg);
    }
}

@keyframes spin-reverse {
    to {
        transform: rotate(-360deg);
    }
}

@keyframes spin-faster {
    to {
        transform: rotate(360deg);
    }
}

@keyframes spin-slower {
    to {
        transform: rotate(360deg);
    }
}

@keyframes spin-reverse-slow {
    to {
        transform: rotate(-360deg);
    }
}

.animate-spin-slow {
    animation: spin-slow 3s linear infinite;
}

.animate-spin-reverse {
    animation: spin-reverse 4s linear infinite;
}

.animate-spin-faster {
    animation: spin-faster 2.5s linear infinite;
}

.animate-spin-slower {
    animation: spin-slower 5s linear infinite;
}

.animate-spin-reverse-slow {
    animation: spin-reverse-slow 6s linear infinite;
}
</style>
