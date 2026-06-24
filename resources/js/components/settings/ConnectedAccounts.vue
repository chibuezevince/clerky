<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import { Shield } from '@lucide/vue'
import { glass } from '@/data/dashboard.js'
import { toast } from 'vue-sonner'
import { link, unlink } from '@/routes/auth'
import OauthButton from '@/components/OauthButton.vue'
import { Icon } from '@iconify/vue'

const props = defineProps<{
    socialAccounts: { id: number; provider: string; avatar: string | null }[]
}>()

const providers: { key: string; label: string; icon: string }[] = [
    { key: 'google', label: 'Google', icon: 'logos:google-icon' },
    { key: 'twitter', label: 'X (Twitter)', icon: 'simple-icons:x' },
]

let currentUrl = ''

onMounted(() => (currentUrl = window.location.href))

const unlinkProvider = (provider: string) => {
    router.delete(unlink(provider), {
        preserveScroll: true,
        onSuccess: () => toast.success('Account disconnected.'),
        onError: (errors) => {
            const msg = errors?.provider ?? 'Failed to disconnect.'
            toast.error(msg)
        },
    })
}
</script>

<template>
    <div :class="[glass, 'flex flex-col gap-4 p-6']">
        <div class="flex items-center gap-3">
            <div
                class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/5"
            >
                <Shield
                    :size="16"
                    class="text-brand-gray"
                />
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Connected Accounts</h3>
                <p class="text-xs text-brand-gray">Link your accounts</p>
            </div>
        </div>

        <div
            v-for="account in socialAccounts"
            :key="account.id"
            class="flex items-center justify-between rounded-xl border border-white/8 px-4 py-3"
        >
            <div class="flex items-center gap-3">
                <Icon
                    :icon="
                        providers.find((p) => p.key === account.provider)
                            ?.icon ?? 'mdi:account-circle'
                    "
                    class="h-8 w-8"
                />
                <div>
                    <span class="text-sm font-medium text-white/70">
                        {{
                            providers.find((p) => p.key === account.provider)
                                ?.label ?? account.provider
                        }}
                    </span>
                    <p class="text-[11px] text-brand-gray">Connected</p>
                </div>
            </div>
            <button
                @click="unlinkProvider(account.provider)"
                class="rounded-lg border border-red-500/20 px-3 py-1 text-[11px] font-medium text-red-400 transition-colors hover:bg-red-500/10"
            >
                Disconnect
            </button>
        </div>

        <div class="flex flex-col gap-2">
            <OauthButton
                v-for="p in providers.filter(
                    (p) => !socialAccounts?.some((a) => a.provider === p.key),
                )"
                :key="p.key"
                :provider="p.key"
                :href="link(p.key).url"
                :redirect-to="currentUrl"
                :action="`Connect ${p.label}`"
            >
                <Icon
                    :icon="p.icon"
                    class="h-5 w-5"
                />
                Connect {{ p.label }}
            </OauthButton>
        </div>
    </div>
</template>
