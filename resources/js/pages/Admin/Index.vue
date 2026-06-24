<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard'
import { Users, ClipboardCheck, BadgeCheck, MailX } from '@lucide/vue'
import { usePage } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })

const props = usePage().props as unknown as {
    total_users: number
    total_clerkings: number
    total_contributors: number
    unverified_users: number
}

const cards = [
    {
        label: 'Total Users',
        value: props.total_users,
        icon: Users,
        color: 'text-brand-yellow',
    },
    {
        label: 'Total Clerkings',
        value: props.total_clerkings,
        icon: ClipboardCheck,
        color: 'text-emerald-400',
    },
    {
        label: 'Contributors',
        value: props.total_contributors,
        icon: BadgeCheck,
        color: 'text-sky-400',
    },
    {
        label: 'Unverified',
        value: props.unverified_users,
        icon: MailX,
        color: 'text-rose-400',
    },
]
</script>

<template>
    <Head title="Admin" />

    <div class="flex flex-col gap-6 py-8">
        <div>
            <h1
                class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
            >
                Admin
            </h1>
            <p class="mt-1 text-[13px] leading-relaxed text-brand-gray">
                Platform overview and user metrics.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div
                v-for="card in cards"
                :key="card.label"
                :class="[glass, 'flex items-center gap-4 p-5']"
            >
                <component
                    :is="card.icon"
                    :size="24"
                    class="shrink-0"
                    :class="card.color"
                />
                <div>
                    <p class="text-2xl font-extrabold text-white">
                        {{ card.value }}
                    </p>
                    <p class="text-[11px] font-semibold text-brand-gray">
                        {{ card.label }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
