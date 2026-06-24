<script lang="ts" setup>
import Select from '@/components/form/Select.vue'
import GuestLayout from '@/layouts/GuestLayout.vue'
import { Form, Head, setLayoutProps, usePage } from '@inertiajs/vue3'
import { LoaderCircle } from '@lucide/vue'
import { reactive } from 'vue'
import { registerSteps } from '@/data/auth'

defineOptions({
    layout: GuestLayout,
})

setLayoutProps({
    step: 3,
    title: 'Set Up Your Account',
    description:
        'Please provide the following information to set up your account.',
    steps: registerSteps,
})

const form = reactive({
    level: '',
    institution: '',
})

type SetupProp = {
    institutions: { id: string; name: string }[]
    levels: { id: string; label: string }[]
}

const page = usePage<SetupProp>()
const { institutions, levels } = page.props

const newLevels = levels.map((level) => ({
    value: String(level.id),
    label: level.label,
}))

const newInstitutions = institutions.map((institution) => ({
    value: String(institution.id),
    label: institution.name,
}))
</script>

<template>
    <Head title="Setup" />

    <Form
        method="post"
        class="flex flex-col gap-5"
        #default="{ processing, errors }"
        :headers="{ 'X-Inertia-Complete-Setup': 'true' }"
        :options="{
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }"
        :transform="(data) => form"
    >
        <div class="flex flex-col gap-2">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-2">
                    <Select
                        v-model="form.institution"
                        :options="newInstitutions"
                        placeholder="Which school are you in?"
                        :error="errors.institution"
                    />
                </div>

                <div class="flex flex-col gap-2">
                    <Select
                        v-model="form.level"
                        :options="newLevels"
                        placeholder="What is your level?"
                        :error="errors.level"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="processing"
                    class="mt-2 flex h-12 items-center justify-center gap-2 rounded-lg bg-brand-yellow text-sm font-semibold text-black transition hover:opacity-90 disabled:opacity-50"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="h-4 w-4 animate-spin"
                    />
                    <span v-if="!processing">Continue</span>
                </button>
            </div>
        </div>
    </Form>
</template>
