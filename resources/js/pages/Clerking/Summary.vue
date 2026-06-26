<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import {
    BadgeCheck,
    Check,
    ChevronLeft,
    ChevronRight,
    Clock,
    Eye,
    List,
    Sparkles,
    X,
} from '@lucide/vue'
import { Motion, AnimatePresence } from 'motion-v'
import { computed, ref } from 'vue'
import type { Clerking, ClerkingUnit, Summary } from '@/types/dashboard'
import { edit, generate } from '@/routes/clerking/summary'
import { useEcho } from '@laravel/echo-vue'
import { toast } from 'vue-sonner'
import { iconMap } from '@/data/constants'
defineOptions({ layout: AppLayout })

const props = defineProps<{
    clerking: Clerking
    responses: Record<string, { id: number; value: string; question: string }[]>
    unit: ClerkingUnit
    summary: Summary | null
}>()

const unitIcon = computed(() => iconMap[props.unit.icon as string])

const sections = computed(() => Object.keys(props.responses))
const activeSection = ref(sections.value[0] ?? '')

const activeResponses = computed(
    () => props.responses[activeSection.value] ?? [],
)

const activeIndex = computed(() => sections.value.indexOf(activeSection.value))

const summaryState = computed(() => {
    if (!props.summary) return 'none'
    if (props.summary.content === null) return 'generating'
    return 'ready'
})

const showSidebar = ref(false)
const slideDirection = ref('left')

const goPrevious = () => {
    const idx = activeIndex.value
    if (idx > 0) {
        slideDirection.value = 'right'
        activeSection.value = sections.value[idx - 1]
    }
}

const goNext = () => {
    const idx = activeIndex.value
    if (idx < sections.value.length - 1) {
        slideDirection.value = 'left'
        activeSection.value = sections.value[idx + 1]
    }
}

const selectSection = (section: string) => {
    const idx = sections.value.indexOf(section)
    slideDirection.value = idx > activeIndex.value ? 'left' : 'right'
    activeSection.value = section
}

const selectMobileSection = (section: string) => {
    activeSection.value = section
    showSidebar.value = false
}

const loadingToastId = ref<number | string>('')

const handleSummaryAction = () => {
    if (summaryState.value === 'ready') {
        router.visit(edit(props.clerking.session_id))
        return
    }

    loadingToastId.value = toast.loading(
        'Generating your summary; this may take a moment.',
    )

    router.post(
        generate(props.clerking.session_id),
        {},
        { preserveScroll: true },
    )
}

if (typeof window !== 'undefined') {
    useEcho(
        `clerking.${props.clerking.session_id}`,
        '.summary.ready',
        ({ generated }) => {
            router.reload({
                onSuccess: () => {
                    if (loadingToastId.value)
                        toast.dismiss(loadingToastId.value)
                    generated
                        ? toast.success('AI summary is ready.')
                        : toast.error(
                              'AI summary could not be generated. Please, try again.',
                          )
                },
            })
        },
    )
}
</script>

<template>
    <Head title="Clerking Summary" />

    <div class="col-span-1 lg:col-span-2">
        <div class="mx-auto mt-5 max-w-5xl">
            <div
                :class="[
                    glass,
                    'mb-6 flex flex-col items-start gap-4 p-6 md:flex-row md:items-center md:p-8',
                ]"
            >
                <div
                    class="hidden h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white/5 md:flex"
                >
                    <component
                        :is="unitIcon"
                        class="h-7 w-7 text-white/40"
                    />
                </div>
                <div class="flex w-full flex-1 flex-col gap-1 md:w-auto">
                    <div
                        class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:gap-3"
                    >
                        <h1
                            class="text-lg font-extrabold tracking-tight text-white md:text-xl"
                        >
                            {{ unit.name }} : {{ clerking.case_number }}
                        </h1>
                        <span
                            class="rounded-full bg-white/10 px-3 py-0.5 text-xs font-semibold text-white/50"
                        >
                            Clerking Complete
                        </span>
                    </div>
                    <p class="text-sm text-brand-gray">
                        <Clock class="mr-1 inline h-3.5 w-3.5" />
                        {{ clerking.completed_at }}
                    </p>
                </div>
                <button
                    @click="handleSummaryAction"
                    :disabled="summaryState === 'generating'"
                    :class="[
                        'flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition-all md:w-auto',
                        summaryState === 'generating'
                            ? 'cursor-not-allowed bg-brand-yellow/20 text-brand-yellow/60'
                            : 'bg-brand-yellow/15 text-brand-yellow hover:bg-brand-yellow/25 active:scale-95',
                    ]"
                >
                    <Sparkles
                        v-if="summaryState !== 'ready'"
                        :class="[
                            'h-4 w-4',
                            summaryState === 'generating'
                                ? 'animate-pulse'
                                : '',
                        ]"
                    />
                    <Eye
                        v-else
                        class="h-4 w-4"
                    />
                    {{
                        summaryState === 'none'
                            ? 'Generate Summary'
                            : summaryState === 'generating'
                              ? 'Generating AI response...'
                              : 'View Summary'
                    }}
                </button>
            </div>

            <div class="relative flex flex-col gap-4 md:flex-row md:gap-6">
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="-translate-x-full opacity-0"
                    leave-active-class="transition duration-200 ease-in"
                    leave-to-class="-translate-x-full opacity-0"
                >
                    <div
                        v-if="showSidebar"
                        :class="[
                            glass,
                            'absolute top-0 left-0 z-10 flex w-64 flex-col overflow-hidden md:hidden',
                        ]"
                        style="max-height: 24rem"
                    >
                        <div
                            class="flex items-center justify-between border-b border-white/5 px-4 py-3"
                        >
                            <span
                                class="text-xs font-extrabold tracking-wider text-brand-gray uppercase"
                            >
                                Sections
                            </span>
                            <button
                                @click="showSidebar = false"
                                class="rounded-lg p-1 text-brand-gray hover:bg-white/10 hover:text-neutral-50"
                            >
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                        <div class="flex-1 overflow-y-auto py-2">
                            <div
                                v-for="section in sections"
                                :key="section"
                                @click="selectMobileSection(section)"
                                :class="[
                                    'flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm transition-colors',
                                    activeSection === section
                                        ? 'border-l-2 border-white/20 bg-white/8 text-white'
                                        : 'border-l-2 border-transparent text-brand-gray hover:bg-white/5 hover:text-neutral-50',
                                ]"
                            >
                                <svg
                                    class="h-4 w-4 shrink-0 text-white/40"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <rect
                                        x="4"
                                        y="2"
                                        width="16"
                                        height="20"
                                        rx="3"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        fill="none"
                                    />
                                    <path
                                        d="M9 10h6"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                    />
                                    <path
                                        d="M9 14h6"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                    />
                                    <path
                                        d="M9 18h4"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                    />
                                    <path
                                        d="M12 7L14 5L12 3"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <span class="font-medium">
                                    {{ section }}
                                </span>
                            </div>
                        </div>
                    </div>
                </Transition>

                <div
                    :class="[
                        glass,
                        'custom-scroll hidden w-56 shrink-0 flex-col py-3 md:flex md:h-[calc(100vh-14rem)] md:overflow-y-auto',
                    ]"
                >
                    <div
                        v-for="section in sections"
                        :key="section"
                        @click="selectSection(section)"
                        :class="[
                            'flex cursor-pointer items-center gap-3 px-4 py-2.5 text-sm transition-colors',
                            activeSection === section
                                ? 'border-l-2 border-white/20 bg-white/8 text-white'
                                : 'border-l-2 border-transparent text-brand-gray hover:bg-white/5 hover:text-neutral-50',
                        ]"
                    >
                        <svg
                            class="h-4 w-4 shrink-0 text-white/40"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <rect
                                x="4"
                                y="2"
                                width="16"
                                height="20"
                                rx="3"
                                stroke="currentColor"
                                stroke-width="1.5"
                                fill="none"
                            />
                            <path
                                d="M9 10h6"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                            <path
                                d="M9 14h6"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                            <path
                                d="M9 18h4"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                            />
                            <path
                                d="M12 7L14 5L12 3"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <span class="font-medium">{{ section }}</span>
                    </div>
                </div>

                <div
                    :class="[
                        glass,
                        'custom-scroll h-[calc(100vh-14rem)] flex-1 overflow-y-auto',
                    ]"
                >
                    <div
                        class="flex items-center justify-between border-b border-white/5 px-4 py-3 md:px-6 md:py-4"
                    >
                        <div class="flex items-center gap-2">
                            <button
                                @click="showSidebar = true"
                                class="rounded-lg p-1.5 text-brand-gray hover:bg-white/10 hover:text-neutral-50 md:hidden"
                            >
                                <List class="h-4 w-4" />
                            </button>
                            <h2
                                class="text-xs font-extrabold tracking-wider text-brand-yellow uppercase md:text-sm"
                            >
                                {{ activeSection }}
                            </h2>
                        </div>
                        <div class="flex items-center gap-1 md:gap-2">
                            <button
                                @click="goPrevious"
                                :disabled="activeIndex === 0"
                                :class="[
                                    'flex items-center gap-1 rounded-lg px-2 py-1.5 text-xs font-semibold transition-colors md:gap-1.5 md:px-3',
                                    activeIndex === 0
                                        ? 'cursor-not-allowed text-brand-gray/40'
                                        : 'text-brand-gray hover:bg-white/10 hover:text-neutral-50',
                                ]"
                            >
                                <ChevronLeft class="h-3.5 w-3.5" />
                                <span class="hidden sm:inline">Previous</span>
                            </button>
                            <button
                                @click="goNext"
                                :disabled="activeIndex === sections.length - 1"
                                :class="[
                                    'flex items-center gap-1 rounded-lg px-2 py-1.5 text-xs font-semibold transition-colors md:gap-1.5 md:px-3',
                                    activeIndex === sections.length - 1
                                        ? 'cursor-not-allowed text-brand-gray/40'
                                        : 'text-brand-gray hover:bg-white/10 hover:text-neutral-50',
                                ]"
                            >
                                <span class="hidden sm:inline">Next</span>
                                <ChevronRight class="h-3.5 w-3.5" />
                            </button>
                        </div>
                    </div>

                    <AnimatePresence mode="wait">
                        <Motion
                            :key="activeSection"
                            :initial="{
                                opacity: 0,
                                x: slideDirection === 'left' ? 16 : -16,
                            }"
                            :animate="{ opacity: 1, x: 0 }"
                            :exit="{
                                opacity: 0,
                                x: slideDirection === 'left' ? -16 : 16,
                            }"
                            :transition="{
                                x: {
                                    type: 'spring',
                                    stiffness: 500,
                                    damping: 40,
                                },
                                opacity: { duration: 0.1 },
                            }"
                        >
                            <div class="divide-y divide-white/5">
                                <div
                                    v-for="response in activeResponses"
                                    :key="response.id"
                                    class="flex flex-col gap-1 px-4 py-3 md:px-6 md:py-4"
                                >
                                    <span
                                        class="text-xs font-medium text-brand-gray"
                                    >
                                        {{ response.question }}
                                    </span>
                                    <span
                                        class="text-sm font-semibold text-white"
                                    >
                                        {{ response.value }}
                                    </span>
                                </div>
                                <div
                                    v-if="activeResponses.length === 0"
                                    class="px-4 py-8 text-center text-sm text-brand-gray md:px-6"
                                >
                                    No responses for this section.
                                </div>
                            </div>
                        </Motion>
                    </AnimatePresence>
                </div>
            </div>
        </div>
    </div>
</template>
