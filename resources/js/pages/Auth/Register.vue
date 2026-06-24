<script setup lang="ts">
import { Head, Form, setLayoutProps, Link } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'
import OauthButton from '@/components/OauthButton.vue'
import Input from '@/components/form/Input.vue'
import PasswordInput from '@/components/form/PasswordInput.vue'
import Submit from '@/components/form/Submit.vue'
import { submit } from '@/routes/register'
import { login } from '@/routes'
import { registerSteps } from '@/data/auth'
import { redirect } from '@/routes/auth'
import { Icon } from '@iconify/vue'

defineOptions({
    layout: GuestLayout,
})

setLayoutProps({
    title: 'Get started with us',
    description: 'Complete these easy steps to register your account.',
    step: 1,
    steps: registerSteps,
})
</script>

<template>
    <Head title="Register" />
    <div class="mb-8 flex flex-col gap-4 sm:flex-row">
        <OauthButton
            provider="google"
            :href="redirect('google').url"
        >
            <Icon icon="logos:google-icon" />
            Continue with Google
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
        method="post"
        class="flex flex-col gap-5"
        #default="{ errors, validate, processing }"
        :options="{
            preserveScroll: true,
        }"
    >
        <div class="flex flex-col gap-2">
            <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                <Input
                    label="Name"
                    name="name"
                    placeholder="John Doe"
                    @change="validate('name')"
                    :error="errors.name"
                />

                <Input
                    label="Username"
                    name="username"
                    placeholder="@johndoe"
                    @change="validate('username')"
                    :error="errors.username"
                />
            </div>

            <Input
                label="Email"
                name="email"
                placeholder="john@doe.com"
                type="email"
                @change="validate('email')"
                :error="errors.email"
                autocomplete="email"
            />
        </div>

        <div class="flex flex-col gap-2">
            <PasswordInput
                label="Password"
                name="password"
                placeholder="********"
                @change="validate('password')"
                :error="errors.password"
                autocomplete="current-password"
            />
        </div>
        <span
            v-if="errors.default"
            class="mb-3 inline-block rounded-md bg-red-500/15 px-2.5 py-1 text-xs font-medium text-red-400"
        >
            {{ errors.default }}
        </span>

        <Submit
            :processing="processing"
            text="Create account"
        />
    </Form>

    <p class="mt-8 text-center text-sm text-brand-gray">
        Do you have an account?
        <Link
            :href="login()"
            prefetch
            class="font-medium text-white hover:text-brand-yellow"
            >Sign in</Link
        >
    </p>
</template>
