<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { AlertTriangle, ArrowLeft } from '@lucide/vue'
import { glass } from '@/data/dashboard.js'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps<{
    status: number
}>()

const isAuthenticated = computed(() => !!usePage().props.auth?.user)

const details: Record<
    number,
    { title: string; message: string; hint: string }
> = {
    403: {
        title: 'Access denied',
        message: "You don't have permission to access this page.",
        hint: 'If you believe this is a mistake, contact your administrator.',
    },
    404: {
        title: 'Page not found',
        message: 'It seems you got a little bit lost.',
        hint: 'The page you are looking for might have been moved or deleted.',
    },
    500: {
        title: 'Server error',
        message: 'Something went wrong on our end.',
        hint: 'Please try again later. We have been notified of the issue.',
    },
    503: {
        title: 'Service unavailable',
        message: 'The service is temporarily unavailable.',
        hint: 'We are working on restoring it. Please check back shortly.',
    },
}

const error = computed(
    () =>
        details[props.status] ?? {
            title: 'Unexpected error',
            message: 'An unexpected error occurred.',
            hint: 'Please try again.',
        },
)
</script>

<template>
    <Head :title="`${error.title} - Clerky`" />

    <template v-if="isAuthenticated">
        <AppLayout>
            <div
                class="flex flex-col items-center justify-center gap-6 px-4 py-24"
            >
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5"
                >
                    <AlertTriangle class="h-7 w-7 text-brand-yellow" />
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <h1
                        class="text-xl font-extrabold tracking-tight text-white"
                    >
                        {{ error.title }}
                    </h1>
                    <p class="max-w-xs text-sm text-brand-gray">
                        {{ error.message }}
                    </p>
                    <p class="max-w-xs text-xs text-brand-gray/60">
                        {{ error.hint }}
                    </p>
                </div>
                <Link
                    href="/dashboard"
                    view-transition
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:bg-white/10 active:scale-95"
                >
                    <ArrowLeft :size="16" />
                    Back to dashboard
                </Link>
            </div>
        </AppLayout>
    </template>

    <template v-else>
        <div
            class="flex min-h-screen flex-col items-center justify-center gap-6 bg-brand-bg px-4"
        >
            <div class="pointer-events-none fixed inset-0">
                <div
                    class="absolute inset-0 bg-[linear-gradient(to_right,rgba(244,253,59,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(244,253,59,0.06)_1px,transparent_1px)] bg-size-[4rem_4rem]"
                />
                <div
                    class="absolute inset-0 bg-linear-to-b from-transparent via-brand-bg/50 to-brand-bg/95"
                />
            </div>

            <div
                class="relative flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5"
            >
                <AlertTriangle class="h-7 w-7 text-brand-yellow" />
            </div>
            <div class="relative flex flex-col items-center gap-2 text-center">
                <h1 class="text-xl font-extrabold tracking-tight text-white">
                    {{ error.title }}
                </h1>
                <p class="max-w-xs text-sm text-brand-gray">
                    {{ error.message }}
                </p>
                <p class="max-w-xs text-xs text-brand-gray/60">
                    {{ error.hint }}
                </p>
            </div>
            <Link
                href="/"
                view-transition
                class="relative flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:bg-white/10 active:scale-95"
            >
                <ArrowLeft :size="16" />
                Go back to homepage
            </Link>
        </div>
    </template>
</template>
