<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard.js'
import { ref, computed } from 'vue'
import { Camera, Trash2, BadgeCheck } from '@lucide/vue'
import { ArrowLeftCircle } from '@lucide/vue'
import AccountSecurity from '@/components/settings/AccountSecurity.vue'
import ConnectedAccounts from '@/components/settings/ConnectedAccounts.vue'
import { toast } from 'vue-sonner'
import { avatar } from '@/routes/settings'
import { dashboard } from '@/routes'
import PersonalInformation from '@/components/settings/PersonalInformation.vue'

defineOptions({ layout: AppLayout })

const page = usePage()

type UserDetail = {
    avatar: string | null
    academic_level_id: number | null
    institution_id: number | null
}

type SocialAccount = {
    id: number
    provider: string
    avatar: string | null
}

type PageUser = {
    id: number
    name: string
    username: string
    email: string
    can_contribute: boolean
    details: UserDetail | null
    social_accounts: SocialAccount[]
}

const { institutions, levels } = page.props as unknown as {
    institutions: { id: string; name: string }[]
    levels: { id: string; label: string }[]
}

const reactiveUser = computed(() => {
    return (usePage().props.user ?? page.props.user) as unknown as PageUser
})

const avatarPreview = computed(() => {
    const user = usePage().props.user as unknown as PageUser | undefined
    return user?.details?.avatar ?? null
})
const fileInput = ref<HTMLInputElement>()
const uploading = ref(false)

const triggerFileSelect = () => fileInput.value?.click()

const onFileSelected = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0]
    if (!file) return

    uploading.value = true
    const data = new FormData()
    data.append('avatar', file)

    router.post(avatar(), data, {
        preserveScroll: true,
        onSuccess: () => {
            uploading.value = false
            toast.success('Avatar updated.')
        },
        onError: () => {
            uploading.value = false
            toast.error('Failed to upload avatar.')
        },
    })
}

const initials = computed(() =>
    reactiveUser.value.name
        .split(' ')
        .map((n: string) => n.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2),
)
</script>

<template>
    <Head title="Settings" />

    <div class="col-span-1 flex flex-col gap-6 py-8 lg:col-span-2">
        <Link
            :href="dashboard()"
            prefetch
            class="flex w-fit items-center gap-1.5 font-semibold text-brand-gray transition-colors hover:text-neutral-50 lg:hidden"
        >
            <ArrowLeftCircle :size="25" />
            Back to dashboard
        </Link>

        <div>
            <h1
                class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
            >
                Settings
            </h1>
            <p class="mt-1 text-[13px] leading-relaxed text-brand-gray">
                Manage your profile, preferences, and account settings.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:items-start">
            <div class="flex flex-col gap-6 lg:col-span-2">
                <div :class="[glass, 'flex items-center gap-5 p-6']">
                    <div class="relative shrink-0">
                        <div
                            class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-brand-yellow/20 text-2xl font-bold text-brand-yellow"
                        >
                            <img
                                v-if="avatarPreview"
                                :src="`${avatarPreview}`"
                                class="h-full w-full object-cover"
                            />
                            <template v-else>
                                {{ initials }}
                            </template>
                            <div
                                v-if="uploading"
                                class="absolute inset-0 flex items-center justify-center rounded-full bg-black/40"
                            >
                                <div
                                    class="h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white"
                                ></div>
                            </div>
                        </div>
                        <button
                            @click="triggerFileSelect"
                            class="absolute -right-1 -bottom-1 flex h-7 w-7 items-center justify-center rounded-full border border-white/10 bg-brand-surface text-white/60 shadow-lg transition-colors hover:bg-white/10 hover:text-white"
                        >
                            <Camera :size="13" />
                        </button>
                        <input
                            ref="fileInput"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="hidden"
                            @change="onFileSelected"
                        />
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2.5">
                            <h2
                                class="truncate text-lg font-extrabold text-white"
                            >
                                {{ reactiveUser.name }}
                            </h2>
                            <span
                                v-if="reactiveUser.can_contribute"
                                class="flex shrink-0 items-center gap-1 rounded-full bg-brand-yellow/10 px-2.5 py-0.5 text-[11px] font-semibold text-brand-yellow"
                            >
                                <BadgeCheck :size="12" />
                                Contributor
                            </span>
                        </div>
                        <p class="mt-0.5 text-sm text-brand-gray">
                            {{ reactiveUser.email }}
                        </p>
                    </div>
                </div>

                <PersonalInformation
                    :name="reactiveUser.name"
                    :email="reactiveUser.email"
                    :username="reactiveUser.username"
                    :academic-level-id="
                        reactiveUser.details?.academic_level_id ?? null
                    "
                    :institution-id="
                        reactiveUser.details?.institution_id ?? null
                    "
                    :institutions="institutions"
                    :levels="levels"
                />
            </div>

            <div class="flex flex-col gap-6">
                <AccountSecurity />

                <ConnectedAccounts
                    :social-accounts="reactiveUser.social_accounts"
                />
            </div>
        </div>
    </div>
</template>
