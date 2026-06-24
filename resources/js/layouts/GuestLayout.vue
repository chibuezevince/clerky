<script setup lang="ts">
import 'vue-sonner/style.css'
import Logo from '@/components/Logo.vue'
import RegisterStep from '@/components/RegisterStep.vue'
import { computed, onUnmounted, ref } from 'vue'
import { toast, Toaster } from 'vue-sonner'
import type { RegisterStep as Step } from '@/types/component-props'
import { Link, router } from '@inertiajs/vue3'

const props = withDefaults(
    defineProps<{
        title?: string
        description?: string
        step?: number
        steps?: Step[]
    }>(),
    {
        step: 0,
        steps: () => [],
    },
)

const registerSteps = computed<Step[]>(() =>
    props.steps.map((step, index) => ({
        ...step,
        active: index + 1 === props.step,
        done: index + 1 < props.step,
    })),
)

const toastListener = router.on('flash', (event) => {
    const toastKeys = [
        'success',
        'error',
        'info',
        'warning',
        'message',
    ] as const

    for (const key of toastKeys) {
        if (event.detail.flash[key]) {
            toast[key](event.detail.flash[key])
            break
        }
    }
})

onUnmounted(() => toastListener())
</script>
<template>
    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-brand-bg p-4 font-sans text-white lg:p-8"
    >
        <div
            class="pointer-events-none absolute inset-0 opacity-20"
            style="
                background-image:
                    linear-gradient(
                        to right,
                        var(--color-brand-border) 1px,
                        transparent 1px
                    ),
                    linear-gradient(
                        to bottom,
                        var(--color-brand-border) 1px,
                        transparent 1px
                    );
                background-size: 80px 80px;
            "
        ></div>

        <div
            class="relative z-10 flex w-full max-w-275 flex-col overflow-hidden rounded-4xl border border-brand-border bg-brand-surface shadow-2xl lg:flex-row"
        >
            <div
                :class="[
                    'relative flex flex-col border-r border-brand-border bg-linear-to-b from-brand-yellow/10 via-brand-surface to-brand-surface p-8 lg:w-[45%]',
                    !step && 'items-center justify-center text-center',
                ]"
            >
                <div class="flex items-center justify-center gap-2">
                    <Link
                        href="/"
                        view-transition
                        prefetch
                        class="group relative flex items-center gap-2"
                    >
                        <Logo />
                    </Link>
                </div>

                <div
                    :class="[
                        step ? 'mt-6 mb-10 text-center' : 'mt-6 text-center',
                    ]"
                >
                    <h1 class="mb-3 text-3xl font-bold">{{ props.title }}</h1>
                    <p class="text-sm text-brand-gray-light">
                        {{ props.description }}
                    </p>
                </div>

                <div
                    class="relative flex flex-1 flex-col gap-6 pl-6"
                    v-if="step"
                >
                    <RegisterStep
                        v-for="(step, index) in registerSteps"
                        :key="index"
                        :title="step.title"
                        :description="step.description"
                        :active="step.active"
                        :done="step.done"
                    />
                </div>
            </div>

            <div class="flex flex-col justify-center p-10 lg:w-[55%] lg:p-16">
                <slot />
            </div>
        </div>

        <Toaster
            v-if="$page.component"
            theme="dark"
            :toast-options="{
                style: {
                    background: 'var(--color-brand-surface)',
                    color: '#ffffff',
                    border: '1px solid var(--color-brand-border)',
                    borderRadius: '12px',
                    padding: '12px 14px',
                    fontFamily: 'var(--font-sans)',
                    boxShadow: '0 10px 30px rgba(0,0,0,0.35)',
                },
            }"
        />
    </div>
</template>
