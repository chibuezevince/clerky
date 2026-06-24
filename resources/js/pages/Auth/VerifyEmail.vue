<script setup lang="ts">
import CodeInput from '@/components/form/CodeInput.vue'
import MessageBadge from '@/components/form/MessageBadge.vue'
import { registerSteps } from '@/data/auth'
import GuestLayout from '@/layouts/GuestLayout.vue'
import { resend } from '@/routes/email'
import { submit } from '@/routes/email/verify'
import { Form, Head, router, setLayoutProps, usePage } from '@inertiajs/vue3'
import { LoaderCircle, MailCheck } from '@lucide/vue'
import { onMounted, ref } from 'vue'

type PageProps = {
    otpSentAt: string
}

const timer = ref<number>(0)

onMounted(() => {
    const page = usePage<PageProps>()

    const { otpSentAt } = page.props
    const diffInSeconds = Math.floor(
        (Date.now() - new Date(otpSentAt).getTime()) / 1000,
    )

    countdown(diffInSeconds)
})

const countdown = (diffInSeconds: number) => {
    if (diffInSeconds > 60) return

    timer.value = 60 - diffInSeconds
    const interval = setInterval(() => {
        timer.value--
        if (timer.value === 0) clearInterval(interval)
    }, 1000)
}

defineOptions({
    layout: GuestLayout,
})

setLayoutProps({
    title: 'Verify Your Email',
    description:
        'We have sent a verification code to your email address. Please enter the code to verify your account.',
    step: 2,
    steps: registerSteps,
})

const resendCode = () => {
    router.post(
        resend(),
        {},
        {
            onFinish: () => countdown(0),
        },
    )
}

const code = ref<string>('')
</script>

<template>
    <Head title="Verify Email" />
    <Form
        #default="{ processing, errors }"
        method="post"
        :action="submit()"
        :transform="() => ({ code })"
        :options="{
            replace: true,
        }"
    >
        <div class="mx-auto flex w-full flex-col items-center">
            <div class="flex items-center justify-center gap-2 sm:gap-3">
                <CodeInput v-model:code="code" />
            </div>

            <div class="mt-4 flex w-full items-center justify-end lg:w-[75%]">
                <div
                    v-if="timer === 0"
                    class="flex items-center gap-2 text-sm text-brand-gray-light"
                >
                    <span
                        class="h-2 w-2 animate-pulse rounded-full bg-green-400"
                    ></span>

                    <a
                        class="cursor-pointer transition-colors hover:text-brand-yellow"
                        @click.prevent="resendCode"
                        >Resend code</a
                    >
                </div>

                <div
                    v-else
                    class="flex items-center gap-2 rounded-full bg-[#1a1f2e] px-3 py-1 text-sm text-brand-gray-light"
                >
                    <span
                        class="h-2 w-2 animate-pulse rounded-full bg-brand-yellow"
                    ></span>
                    <span class="tabular-nums">Resend in {{ timer }}s</span>
                </div>
            </div>

            <div class="mt-2 flex w-full lg:w-[75%]">
                <MessageBadge
                    v-if="errors.code"
                    :message="errors.code"
                    variant="error"
                    class="w-full"
                />
            </div>

            <button
                type="submit"
                :disabled="processing"
                class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-brand-yellow py-3.5 font-semibold text-black transition-colors hover:bg-[#e4eb29] disabled:opacity-50 lg:w-[75%]"
            >
                <span
                    v-if="processing"
                    class="flex items-center gap-2"
                >
                    <LoaderCircle class="h-4 w-4 animate-spin" />
                    Verifying Account
                </span>

                <span
                    v-else
                    class="flex items-center gap-2"
                >
                    <MailCheck class="h-4 w-4" />
                    Verify Account
                </span>
            </button>
        </div>
    </Form>
</template>
