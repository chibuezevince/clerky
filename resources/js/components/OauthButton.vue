<script setup lang="ts">
import { ref } from 'vue'
import { LoaderCircle } from '@lucide/vue'
import { router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'

const props = withDefaults(
    defineProps<{
        action?: string
        href: string
        provider: string
        redirectTo?: string
    }>(),
    {
        action: 'Continue',
        redirectTo: '/dashboard',
    },
)

const processing = ref<boolean>(false)

function initiateOauth() {
    processing.value = true

    const popup = window.open(
        props.href,
        'oauth_popup',
        'width=500,height=650,scrollbars=yes',
    )

    const handler = (event: MessageEvent) => {
        if (event.origin !== window.location.origin) return

        if (event.data.type === 'oauth_success') {
            cleanup()
            toast.success('Authentication completed. Redirecting...')
            setTimeout(() => {
                router.visit(props.redirectTo)
            }, 500)
        }

        if (event.data.type === 'oauth_error') {
            cleanup()
            toast.error(event.data.error ?? 'Authentication failed')
        }
    }

    const popupWatcher = setInterval(() => {
        if (popup?.closed) cleanup()
    }, 500)

    function cleanup() {
        processing.value = false
        window.removeEventListener('message', handler)
        clearInterval(popupWatcher)
    }

    window.addEventListener('message', handler)
}
</script>
<template>
    <a
        @click="initiateOauth"
        role="button"
        href="javascript:void(0)"
        class="flex flex-1 items-center justify-center gap-3 rounded-lg border border-brand-border bg-brand-bg px-4 py-2.5 text-sm font-medium transition-colors hover:bg-brand-surface"
    >
        <div
            v-if="processing"
            class="flex w-full items-center justify-center gap-2"
        >
            <LoaderCircle class="animate-spin" /> Loading
        </div>

        <slot
            :processing="processing"
            v-else
        />
    </a>
</template>
