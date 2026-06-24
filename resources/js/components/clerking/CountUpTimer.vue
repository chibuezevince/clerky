<script setup lang="ts">
import { pause, resume } from '@/routes/clerking'
import {
    computed,
    inject,
    onMounted,
    onUnmounted,
    ref,
    watch,
    type Ref,
} from 'vue'
import { type Clerking } from '@/types/dashboard'
import { formattedTime } from '@/data/dashboard'
import { Clock, Pause, Play } from '@lucide/vue'
import { router } from '@inertiajs/vue3'

const props = defineProps<{ clerking: Clerking }>()
const isPaused = inject<Ref<boolean>>(
    'isPaused',
    ref(props.clerking.started_at === null),
)

let diffInSeconds = ref(0)

const totalSeconds = computed(() => Math.round(diffInSeconds.value))

const hours = computed(() => Math.floor(totalSeconds.value / 3600))
const minutes = computed(() => Math.floor((totalSeconds.value % 3600) / 60))
const seconds = computed(() => totalSeconds.value % 60)

let timer: number | undefined = undefined

onMounted(() => {
    const startedAt = new Date(props.clerking.started_at)
    const now = new Date()

    if (props.clerking.started_at) {
        diffInSeconds.value =
            Math.abs(startedAt.getTime() - now.getTime()) / 1000
        diffInSeconds.value += props.clerking.elapsed_seconds
    } else {
        diffInSeconds.value = props.clerking.elapsed_seconds
    }

    timer = setInterval(() => {
        if (isPaused.value) return
        diffInSeconds.value += 1
    }, 1000)
})

onUnmounted(() => {
    clearInterval(timer)
})

const toggle = () => {
    isPaused.value = !isPaused.value
}

watch(isPaused, (value) => {
    if (value) {
        router.post(
            pause({ clerking: props.clerking.session_id }),
            { elapsed_seconds: diffInSeconds.value },
            { preserveScroll: true },
        )
    } else {
        const formattedDate = formattedTime()
        router.post(
            resume({ clerking: props.clerking.session_id }),
            { started_at: formattedDate },
            { preserveScroll: true },
        )
    }
})
</script>
<template>
    <div
        class="flex items-center justify-between rounded-2xl border border-white/5 bg-white/3 p-1.5 shadow-inner md:justify-end md:gap-5"
    >
        <div class="flex items-center gap-4 pr-2 pl-4">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl border border-brand-yellow/20 bg-brand-yellow/10 md:hidden"
            >
                <Clock
                    :size="20"
                    class="text-brand-yellow"
                    :class="{ 'animate-pulse': isPaused }"
                />
            </div>
            <Clock
                :size="18"
                class="hidden text-brand-yellow md:block"
                :class="{ 'animate-pulse': isPaused }"
            />
            <div class="flex flex-col">
                <span
                    class="mb-0.5 text-[9px] leading-none font-bold tracking-wider text-brand-gray uppercase"
                    >Time Elapsed</span
                >
                <div
                    class="flex items-center font-mono text-xl font-bold text-white md:text-base md:tracking-widest"
                >
                    <template v-if="hours > 0">
                        <div class="relative h-7 overflow-hidden">
                            {{ hours.toString().padStart(2, '0') }}
                        </div>
                        <span class="mx-0.5 opacity-50">:</span>
                    </template>

                    <div class="relative h-7 overflow-hidden">
                        {{ minutes.toString().padStart(2, '0') }}
                    </div>

                    <span class="mx-0.5 opacity-50">:</span>

                    <div class="relative h-7 overflow-hidden">
                        {{ seconds.toString().padStart(2, '0') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="h-8 w-px bg-white/10"></div>

        <button
            @click="toggle"
            class="group/btn flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-white transition-all hover:bg-white/10 active:scale-90 md:h-10 md:w-10"
            :class="{ 'h-12 w-12': true }"
            :title="isPaused ? 'Resume' : 'Pause'"
        >
            <component
                :is="isPaused ? Play : Pause"
                :size="16"
                class="transition-transform group-hover/btn:scale-110"
            />
        </button>
    </div>
</template>
