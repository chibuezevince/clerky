<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { User, Check, LoaderCircle } from '@lucide/vue'
import { glass } from '@/data/dashboard.js'
import Input from '@/components/form/Input.vue'
import Select from '@/components/form/Select.vue'
import { toast } from 'vue-sonner'
import { update } from '@/routes/settings'

const props = defineProps<{
    name: string
    email: string
    username: string
    academicLevelId: number | null
    institutionId: number | null
    institutions: { id: string; name: string }[]
    levels: { id: string; label: string }[]
}>()

const form = useForm({
    name: props.name,
    academic_level_id: props.academicLevelId
        ? String(props.academicLevelId)
        : '',
    institution_id: props.institutionId ? String(props.institutionId) : '',
})

const levelOptions = computed(() =>
    props.levels.map((l) => ({ value: String(l.id), label: l.label })),
)

const institutionOptions = computed(() =>
    props.institutions.map((i) => ({ value: String(i.id), label: i.name })),
)

const save = () => {
    const data = new FormData()
    data.append('name', form.name)
    data.append('academic_level_id', form.academic_level_id || '')
    data.append('institution_id', form.institution_id || '')

    router.post(update(), data, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Settings saved.')
        },
    })
}
</script>

<template>
    <div :class="[glass, 'flex flex-col gap-6 p-6']">
        <div class="flex items-center gap-3">
            <div
                class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/5"
            >
                <User
                    :size="16"
                    class="text-brand-gray"
                />
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">
                    Personal Information
                </h3>
                <p class="text-xs text-brand-gray">
                    Update your profile details
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <Input
                v-model="form.name"
                label="Full Name"
                placeholder="Your full name"
            />

            <Input
                :model-value="email"
                label="Email Address"
                placeholder="Your email"
                disabled
            />

            <Input
                :model-value="username"
                label="Username"
                placeholder="Your username"
                disabled
            />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <Select
                    v-model="form.academic_level_id"
                    label="Academic Level"
                    placeholder="Select level"
                    :options="levelOptions"
                />
                <Select
                    v-model="form.institution_id"
                    label="Institution"
                    placeholder="Select institution"
                    :options="institutionOptions"
                />
            </div>
        </div>

        <div class="flex justify-end">
            <button
                @click="save"
                :disabled="form.processing"
                class="flex items-center gap-1.5 rounded-lg bg-brand-yellow px-5 py-2.5 text-sm font-bold text-brand-bg transition-opacity hover:opacity-85 disabled:opacity-30"
            >
                <LoaderCircle
                    v-if="form.processing"
                    :size="14"
                    class="animate-spin"
                />
                <Check
                    v-else
                    :size="14"
                />
                {{ form.processing ? 'Saving...' : 'Save Changes' }}
            </button>
        </div>
    </div>
</template>
