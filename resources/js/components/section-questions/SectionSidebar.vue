<script setup lang="ts">
import { glass } from '@/data/dashboard'
import { ClerkingSection, UnitQuestion } from '@/types/dashboard'

import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { motion, AnimatePresence } from 'motion-v'
import { ListPlusIcon, Pencil, X } from '@lucide/vue'

const isSidebarOpen = ref(false)

const handleEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape') isSidebarOpen.value = false
}

const props = defineProps<{
    sections: Record<string, UnitQuestion[]>
    template: { id: number; name: string; slug: string }
    clerkingSections: ClerkingSection[]
    editingQuestion: UnitQuestion | null
}>()

const emit = defineEmits<{
    (e: 'close-edit'): void
}>()

watch(isSidebarOpen, (open) => {
    if (window.innerWidth < 1024) {
        document.body.style.overflow = open ? 'hidden' : ''
        if (!open && props.editingQuestion) emit('close-edit')
    }
})

onMounted(() => window.addEventListener('keydown', handleEscape))
onUnmounted(() => {
    window.removeEventListener('keydown', handleEscape)
    document.body.style.overflow = ''
})

const sidebarTitle = computed(() =>
    props.editingQuestion ? 'Edit Question' : 'Add New Question',
)

const sidebarDescription = computed(() =>
    props.editingQuestion
        ? 'Update the question details below.'
        : 'New questions are automatically added to the bottom of the list.',
)

const sidebarIcon = computed(() =>
    props.editingQuestion ? Pencil : ListPlusIcon,
)

const panelTransition = {
    x: { type: 'spring', stiffness: 320, damping: 32, mass: 0.9 },
    opacity: { duration: 0.22, ease: 'easeOut' },
} as const

const backdropTransition = { duration: 0.28, ease: [0.16, 1, 0.3, 1] } as const
const handleTransition = { duration: 0.2, ease: 'easeOut' } as const

import type { PanInfo } from 'motion-v'
import SectionNewQuestion from './SectionNewQuestion.vue'

const waveActive = ref(false)

const onDragEnd = (_event: PointerEvent, info: PanInfo) => {
    if (info.offset.x > 120 || info.velocity.x > 500) {
        isSidebarOpen.value = false
    }
}

watch(
    () => props.editingQuestion,
    (question) => {
        if (question) {
            waveActive.value = true
            setTimeout(() => (waveActive.value = false), 1500)
            if (window.innerWidth < 1024) {
                isSidebarOpen.value = true
            }
        }
    },
)
</script>

<template>
    <div class="h-full lg:col-span-2">
        <div class="hidden lg:sticky lg:top-6 lg:block lg:self-start">
            <div
                :class="[glass, 'relative flex flex-col overflow-hidden']"
                style="max-height: calc(100vh - 3rem)"
            >
                <div
                    v-if="waveActive"
                    class="pointer-events-none absolute inset-0 z-10"
                >
                    <div class="wave-shimmer h-full w-full" />
                </div>

                <div class="shrink-0 px-6 pt-6 md:px-8 md:pt-8">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/5"
                        >
                            <component :is="sidebarIcon" />
                        </div>
                        <div class="flex flex-col gap-1">
                            <h2
                                class="text-lg font-extrabold tracking-tight text-white"
                            >
                                {{ sidebarTitle }}
                            </h2>
                            <p class="text-xs text-brand-gray">
                                {{ sidebarDescription }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="flex-1 overflow-y-auto overscroll-contain px-6 pb-6 md:px-8 md:pb-8"
                >
                    <div class="flex flex-col gap-5 pt-6">
                        <SectionNewQuestion
                            :sections="sections"
                            :template="template"
                            :clerking-sections="clerkingSections"
                            :editing-question="editingQuestion"
                            @close-edit="emit('close-edit')"
                        />
                    </div>
                </div>
            </div>
        </div>

        <AnimatePresence>
            <motion.button
                v-if="!isSidebarOpen"
                @click="isSidebarOpen = true"
                aria-label="Open template details"
                :initial="{ opacity: 0, x: 10 }"
                :animate="{ opacity: 1, x: 0 }"
                :exit="{ opacity: 0, x: 10 }"
                :transition="handleTransition"
                :while-hover="{ x: -2 }"
                :while-tap="{ scale: 0.92 }"
                class="fixed top-1/2 right-0 z-40 -translate-y-1/2 rounded-l-xl border border-r-0 border-white/10 bg-brand-surface/80 px-2 py-4 text-white/40 shadow-lg backdrop-blur-md transition-colors hover:bg-brand-surface hover:text-brand-yellow lg:hidden"
            >
                <ListPlusIcon :size="18" />
            </motion.button>
        </AnimatePresence>

        <AnimatePresence>
            <motion.div
                v-if="isSidebarOpen"
                @click="isSidebarOpen = false"
                :initial="{ opacity: 0 }"
                :animate="{ opacity: 1 }"
                :exit="{ opacity: 0 }"
                :transition="backdropTransition"
                class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
            />

            <div
                v-if="isSidebarOpen"
                class="fixed top-1/2 right-4 z-50 w-full max-w-sm -translate-y-1/2 lg:hidden"
            >
                <motion.div
                    :initial="{ x: '110%', opacity: 0 }"
                    :animate="{ x: '0%', opacity: 1 }"
                    :exit="{ x: '110%', opacity: 0 }"
                    :transition="panelTransition"
                    :class="[
                        glass,
                        'relative flex flex-col overflow-hidden rounded-2xl shadow-2xl',
                    ]"
                >
                    <div
                        v-if="waveActive"
                        class="pointer-events-none absolute inset-0 z-10"
                    >
                        <div class="wave-shimmer h-full w-full" />
                    </div>

                    <motion.div
                        drag="x"
                        :drag-constraints="{ left: 0, right: 300 }"
                        :drag-elastic="0.15"
                        :drag-momentum="false"
                        @drag-end="onDragEnd"
                        class="flex cursor-grab items-center justify-between px-6 pt-5 pb-4 active:cursor-grabbing md:px-8"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/5"
                            >
                                <component :is="sidebarIcon" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <h2
                                    class="text-lg font-extrabold tracking-tight text-white"
                                >
                                    {{ sidebarTitle }}
                                </h2>
                                <p class="text-xs text-brand-gray">
                                    {{ sidebarDescription }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click.stop="isSidebarOpen = false"
                            class="rounded p-2 text-white/40 transition-colors hover:text-white"
                        >
                            <X :size="20" />
                        </button>
                    </motion.div>

                    <div
                        class="overflow-y-auto overscroll-contain px-6 pb-6 md:px-8 md:pb-8"
                        style="max-height: calc(80svh - 80px)"
                    >
                        <div class="flex flex-col gap-5">
                            <SectionNewQuestion
                                :template="template"
                                :clerking-sections="clerkingSections"
                                :sections="sections"
                                :editing-question="editingQuestion"
                                @close-edit="emit('close-edit')"
                            />
                        </div>
                    </div>
                </motion.div>
            </div>
        </AnimatePresence>
    </div>
</template>

<style scoped>
@keyframes wave-sweep {
    0% {
        transform: translateX(-100%);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateX(100%);
        opacity: 0;
    }
}

.wave-shimmer {
    background: linear-gradient(
        90deg,
        transparent 0%,
        rgba(251, 191, 36, 0.06) 30%,
        rgba(251, 191, 36, 0.12) 50%,
        rgba(251, 191, 36, 0.06) 70%,
        transparent 100%
    );
    animation: wave-sweep 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
</style>
