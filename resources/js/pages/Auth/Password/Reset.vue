<script setup lang="ts">
import Input from '@/components/form/Input.vue'
import PasswordInput from '@/components/form/PasswordInput.vue'
import Submit from '@/components/form/Submit.vue'
import { passwordSteps } from '@/data/auth'
import GuestLayout from '@/layouts/GuestLayout.vue'
import { request } from '@/routes/password'
import { submit } from '@/routes/password/reset'
import { Form, Head, Link, setLayoutProps, usePage } from '@inertiajs/vue3'

defineOptions({
    layout: GuestLayout,
})

setLayoutProps({
    title: 'Password Recovery',
    description:
        'Request a password reset email to regain access to your account.',
    steps: passwordSteps,
    step: 2,
})

const pageEmail = usePage().props.email as string | null | undefined
</script>

<template>
    <Head title="Password Reset" />

    <Form
        class="flex flex-col gap-5 inert:pointer-events-none inert:opacity-50"
        method="post"
        #default="{ errors, processing }"
        :disable-while-processing="true"
        :action="submit()"
        :options="{
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }"
        :transform="
            (data) => ({
                ...data,
                token: $page.props.token as string,
            })
        "
    >
        <div class="flex flex-col gap-2">
            <Input
                name="email"
                label="Email"
                type="email"
                :error="errors.email"
                :model-value="pageEmail"
                readonly
            />

            <PasswordInput
                label="New Password"
                name="password"
                placeholder="********"
                type="password"
                :error="errors.password"
            />

            <PasswordInput
                label="Confirm Password"
                name="password_confirmation"
                placeholder="********"
                type="password"
                :error="errors.password_confirmation"
            />
        </div>

        <Submit
            :processing="processing"
            text="Reset Password"
        />
    </Form>

    <p class="mt-8 text-center text-sm text-brand-gray">
        Back to
        <Link
            :href="request()"
            view-transition
            prefetch
            class="font-medium text-white hover:text-brand-yellow"
            >&nbsp;&nbsp;Forgot Password?</Link
        >
    </p>
</template>
