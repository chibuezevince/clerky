<script setup lang="ts">
import { Head, Link, Form, setLayoutProps } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'
import Input from '@/components/form/Input.vue'
import PasswordInput from '@/components/form/PasswordInput.vue'
import Radio from '@/components/form/Radio.vue'
import { register } from '@/routes'
import Submit from '@/components/form/Submit.vue'
import { request } from '@/routes/password'
import { redirect } from '@/routes/auth'
import OauthButton from '@/components/OauthButton.vue'
import { Icon } from '@iconify/vue'
import { submit } from '@/routes/login'

defineOptions({
    layout: GuestLayout,
})

setLayoutProps({
    title: 'Login to your account',
    description: 'Enter your credentials to access your dashboard.',
    steps: [],
    step: 0,
})
</script>

<template>
    <Head title="Login" />

    <div class="mb-8 flex flex-col gap-4 sm:flex-row">
        <OauthButton
            provider="google"
            :href="redirect('google').url"
        >
            <div class="flex items-center gap-2">
                <Icon icon="logos:google-icon" />
                Continue with Google
            </div>
        </OauthButton>

        <OauthButton
            provider="twitter"
            :href="redirect('twitter').url"
        >
            <Icon icon="simple-icons:x" />
            Continue with X
        </OauthButton>
    </div>

    <div class="mb-8 flex items-center gap-4">
        <div class="h-px flex-1 bg-brand-border"></div>
        <span class="text-xs tracking-wider text-brand-gray uppercase">Or</span>
        <div class="h-px flex-1 bg-brand-border"></div>
    </div>

    <Form
        :action="submit()"
        class="flex flex-col gap-5 inert:pointer-events-none inert:opacity-50"
        method="post"
        #default="{ errors, validate, processing }"
        :disable-while-processing="true"
    >
        <div class="flex flex-col gap-2">
            <Input
                name="email"
                label="Email"
                placeholder="john@doe.com"
                type="email"
                @change="validate('email')"
                :error="errors.email"
                autocomplete="username"
            />
        </div>

        <div class="flex flex-col gap-2">
            <PasswordInput
                name="password"
                label="Password"
                placeholder="********"
                @change="validate('password')"
                :error="errors.password"
                autocomplete="current-password"
            />
        </div>

        <div class="mt-2 flex items-center justify-between">
            <label
                class="group flex cursor-pointer items-center gap-3 select-none"
            >
                <Radio
                    name="remember"
                    label="Remember me"
                />
            </label>
            <Link
                :href="request()"
                view-transition
                prefetch
                class="text-sm text-brand-gray-light transition-colors hover:text-brand-yellow"
                >Forgot Password?</Link
            >
        </div>

        <Submit
            :processing="processing"
            text="Login"
        />
    </Form>

    <p class="mt-8 text-center text-sm text-brand-gray">
        Don't have an account?
        <Link
            :href="register()"
            view-transition
            prefetch
            class="font-medium text-white hover:text-brand-yellow"
        >
            Sign up</Link
        >
    </p>
</template>
