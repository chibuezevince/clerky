<script setup lang="ts">
import Input from '@/components/form/Input.vue'
import Submit from '@/components/form/Submit.vue'
import { passwordSteps } from '@/data/auth'
import GuestLayout from '@/layouts/GuestLayout.vue'
import { login } from '@/routes'
import { email } from '@/routes/password'
import { Form, Head, Link, router, setLayoutProps } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'

defineOptions({
    layout: GuestLayout,
})

setLayoutProps({
    title: 'Password Recovery',
    description:
        'Request a password reset email to regain access to your account.',
    steps: passwordSteps,
    step: 1,
})
</script>

<template>
    <Head title="Password Recovery" />

    <Form
        class="flex flex-col gap-5 inert:pointer-events-none inert:opacity-50"
        method="post"
        #default="{ errors, processing }"
        :disable-while-processing="true"
        :action="email()"
        :reset-on-success="true"
        :options="{
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }"
    >
        <div class="flex flex-col gap-2">
            <Input
                required
                name="email"
                label="Email"
                placeholder="john@doe.com"
                type="email"
                :error="errors.email"
            />
        </div>

        <Submit
            :processing="processing"
            text="Send Password Reset Link"
        />
    </Form>

    <p class="mt-8 text-center text-sm text-brand-gray">
        Remember your login?
        <Link
            :href="login()"
            view-transition
            prefetch
            class="font-medium text-white hover:text-brand-yellow"
            >&nbsp;&nbsp;Login</Link
        >
    </p>
</template>
