<script setup lang="ts">
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { Lock, ChevronDown, LoaderCircle } from '@lucide/vue'
import { glass } from '@/data/dashboard.js'
import PasswordInput from '@/components/form/PasswordInput.vue'
import { toast } from 'vue-sonner'
import { password } from '@/routes/settings'

const passwordOpen = ref(false)
const passwordForm = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
})

const changePassword = () => {
    router.patch(
        password(),
        {
            current_password: passwordForm.current_password,
            new_password: passwordForm.new_password,
            new_password_confirmation: passwordForm.new_password_confirmation,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                passwordForm.reset()
                passwordOpen.value = false
                toast.success('Password changed.')
            },
        },
    )
}
</script>

<template>
    <div :class="[glass, 'flex flex-col gap-4 p-6']">
        <button
            @click="passwordOpen = !passwordOpen"
            class="flex w-full items-center gap-3 text-left"
        >
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/5"
            >
                <Lock
                    :size="16"
                    class="text-brand-gray"
                />
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-white">Account Security</h3>
                <p class="text-xs text-brand-gray">Manage your password</p>
            </div>
            <ChevronDown
                :size="16"
                class="text-white/25 transition-transform"
                :class="passwordOpen ? 'rotate-0' : '-rotate-90'"
            />
        </button>

        <div
            v-if="passwordOpen"
            class="flex flex-col gap-4 border-t border-white/5 pt-4"
        >
            <PasswordInput
                v-model="passwordForm.current_password"
                label="Current Password"
                placeholder="Enter current password"
            />
            <PasswordInput
                v-model="passwordForm.new_password"
                label="New Password"
                placeholder="Enter new password"
            />
            <PasswordInput
                v-model="passwordForm.new_password_confirmation"
                label="Confirm New Password"
                placeholder="Confirm new password"
            />
            <div class="flex justify-end">
                <button
                    @click="changePassword"
                    :disabled="passwordForm.processing"
                    class="flex items-center gap-1.5 rounded-lg bg-brand-yellow px-5 py-2.5 text-sm font-bold text-brand-bg transition-opacity hover:opacity-85 disabled:opacity-30"
                >
                    <LoaderCircle
                        v-if="passwordForm.processing"
                        :size="14"
                        class="animate-spin"
                    />
                    <Lock
                        v-else
                        :size="14"
                    />
                    {{
                        passwordForm.processing
                            ? 'Updating...'
                            : 'Update Password'
                    }}
                </button>
            </div>
        </div>
    </div>
</template>
