<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { Transition } from 'vue'
import { Bell, CheckCheck } from '@lucide/vue'
import { readAll } from '@/routes/notifications'
import FormattedDate from '@/components/dashboard/FormattedDate.vue'
import type { AppNotification } from '@/types/dashboard'

const page = usePage()
const open = ref(false)

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

const toggle = () => (open.value = !open.value)

const handleClickOutside = (e: MouseEvent) => {
    const target = e.target as HTMLElement
    if (!target.closest('[data-notification-bell]')) {
        open.value = false
    }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<template>
    <div
        data-notification-bell
        class="relative"
    >
        <button
            @click.stop="toggle"
            class="relative flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-white/50 transition-colors hover:bg-white/5 hover:text-white"
        >
            <Bell :size="18" />
            <span
                v-if="unreadCount > 0"
                class="absolute -top-0.5 -right-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-brand-yellow px-1 text-[9px] font-bold text-brand-bg"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <Transition name="notif">
            <div
                v-if="open"
                class="absolute top-full right-0 z-50 mt-2 w-80 overflow-hidden rounded-2xl border border-white/10 bg-brand-surface shadow-xl"
            >
                <div
                    class="flex items-center justify-between border-b border-white/5 px-4 py-3"
                >
                    <span class="text-sm font-bold text-white"
                        >Notifications</span
                    >
                    <button
                        v-if="unreadCount > 0"
                        @click="markAllAsRead"
                        class="flex items-center gap-1 text-[11px] text-brand-gray transition-colors hover:text-white"
                    >
                        <CheckCheck :size="13" />
                        Mark all read
                    </button>
                </div>

                <div class="flex max-h-80 flex-col overflow-y-auto">
                    <div
                        v-for="n in notifications"
                        :key="n.id"
                        class="group flex cursor-pointer items-start gap-3 border-b border-white/5 px-4 py-3.5 transition-colors hover:bg-white/2"
                        :class="!n.read_at ? 'bg-brand-yellow/1.5' : ''"
                        @click="!n.read_at && markAsRead(n.id)"
                    >
                        <div
                            class="mt-1.5 shrink-0"
                            :class="
                                !n.read_at
                                    ? 'h-2 w-2 rounded-full bg-brand-yellow'
                                    : 'h-2 w-2 rounded-full bg-white/10'
                            "
                        />
                        <div class="min-w-0 flex-1">
                            <p
                                class="truncate text-[13px] font-semibold"
                                :class="
                                    !n.read_at ? 'text-white' : 'text-white/50'
                                "
                            >
                                {{ n.title }}
                            </p>
                            <p
                                class="mt-0.5 line-clamp-2 text-[12px] leading-relaxed"
                                :class="
                                    !n.read_at
                                        ? 'text-white/60'
                                        : 'text-white/30'
                                "
                            >
                                {{ n.message }}
                            </p>
                            <p class="mt-1 text-[11px] text-white/20">
                                <FormattedDate :date="n.created_at" />
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="notifications.length === 0"
                        class="flex flex-col items-center gap-2 px-4 py-10 text-center"
                    >
                        <Bell
                            :size="18"
                            class="text-white/15"
                        />
                        <p class="text-sm font-medium text-white/30">
                            No notifications
                        </p>
                        <p class="text-xs text-white/15">
                            You're all caught up!
                        </p>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.notif-enter-active {
    animation: notif-in 0.15s ease-out;
}
.notif-leave-active {
    animation: notif-in 0.1s ease-in reverse;
}
@keyframes notif-in {
    from {
        opacity: 0;
        transform: translateY(-4px) scale(0.97);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>
