<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Bell, CheckCheck, ChevronRight } from '@lucide/vue'
import { glass } from '@/data/dashboard.js'
import { readAll } from '@/routes/notifications'
import FormattedDate from '@/components/dashboard/FormattedDate.vue'
import type { AppNotification } from '@/types/dashboard'

const page = usePage()

const notifications = computed<AppNotification[]>(() => {
    const n = page.props.notifications as
        | { unread_count: number; latest: AppNotification[] }
        | undefined
    return n?.latest ?? []
})

const unreadCount = computed(() => {
    const n = page.props.notifications as
        | { unread_count: number; latest: AppNotification[] }
        | undefined
    return n?.unread_count ?? 0
})

const markAsRead = (id: string) => {
    router.patch(`/notifications/${id}/read`, undefined, {
        preserveScroll: true,
    })
}

const markAllAsRead = () => {
    router.patch(readAll(), undefined, {
        preserveScroll: true,
    })
}
</script>

<template>
    <div :class="[glass, 'flex flex-col']">
        <div class="flex items-center justify-between px-5 pt-5 pb-3">
            <div class="flex items-center gap-2.5">
                <Bell
                    :size="16"
                    class="text-brand-gray"
                />
                <span class="text-sm font-bold text-white">Notifications</span>
                <span
                    v-if="unreadCount > 0"
                    class="flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-yellow/15 px-1.5 text-[10px] font-bold text-brand-yellow"
                >
                    {{ unreadCount }}
                </span>
            </div>
            <button
                v-if="unreadCount > 0"
                @click="markAllAsRead"
                class="flex items-center gap-1 text-[11px] text-brand-gray transition-colors hover:text-white"
            >
                <CheckCheck :size="13" />
                Mark all read
            </button>
        </div>

        <div class="flex flex-col">
            <div
                v-for="n in notifications"
                :key="n.id"
                class="group flex cursor-pointer items-start gap-3 border-t border-white/5 px-5 py-3.5 transition-colors hover:bg-white/2"
                :class="!n.read_at ? 'bg-brand-yellow/2' : ''"
                @click="!n.read_at && markAsRead(n.id)"
            >
                <div
                    class="mt-1 shrink-0"
                    :class="
                        !n.read_at
                            ? 'h-2 w-2 rounded-full bg-brand-yellow'
                            : 'h-2 w-2 rounded-full bg-white/10'
                    "
                />

                <div class="min-w-0 flex-1">
                    <p
                        class="truncate text-[13px] font-semibold"
                        :class="!n.read_at ? 'text-white' : 'text-white/50'"
                    >
                        {{ n.title }}
                    </p>
                    <p
                        class="mt-0.5 line-clamp-2 text-[12px] leading-relaxed"
                        :class="!n.read_at ? 'text-white/60' : 'text-white/30'"
                    >
                        {{ n.message }}
                    </p>
                    <p class="mt-1 text-[11px] text-white/20">
                        <FormattedDate :date="n.created_at" />
                    </p>
                </div>

                <ChevronRight
                    v-if="!n.read_at"
                    :size="14"
                    class="mt-1 shrink-0 text-white/15 opacity-0 transition-opacity group-hover:opacity-100"
                />
            </div>

            <div
                v-if="notifications.length === 0"
                class="flex flex-col items-center gap-2 px-5 py-10 text-center"
            >
                <Bell
                    :size="18"
                    class="text-white/15"
                />
                <p class="text-sm font-medium text-white/30">
                    No notifications
                </p>
                <p class="text-xs text-white/15">You're all caught up!</p>
            </div>
        </div>
    </div>
</template>
