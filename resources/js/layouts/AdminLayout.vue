<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { index as adminIndex, users as adminUsers } from '@/routes/admin'
import { dashboard } from '@/routes'
import { LogOut, LayoutDashboard, Users, ArrowLeft, Menu, X } from '@lucide/vue'
import { logout } from '@/actions/App/Http/Controllers/Auth/LoginController'

const activeNav = usePage().url
const mobileMenuOpen = ref(false)
const closeMenu = () => {
    mobileMenuOpen.value = false
}

const navItems = [
    {
        id: 'admin.dashboard',
        url: adminIndex(),
        label: 'Dashboard',
        icon: LayoutDashboard,
    },
    {
        id: 'admin.users',
        url: adminUsers(),
        label: 'Manage Users',
        icon: Users,
    },
]

const glass =
    'bg-brand-surface/45 backdrop-blur-2xl backdrop-saturate-150 border border-white/[0.06] rounded-3xl'
</script>

<template>
    <div
        class="relative h-screen overflow-hidden bg-brand-bg px-6 font-sans text-neutral-50 max-[720px]:px-4"
    >
        <div
            class="pointer-events-none absolute -top-40 left-[30%] h-125 w-125 rounded-full bg-brand-yellow opacity-[0.08] blur-[120px]"
        ></div>
        <div
            class="pointer-events-none absolute right-[10%] -bottom-40 h-112.5 w-112.5 rounded-full bg-brand-yellow opacity-[0.05] blur-[120px]"
        ></div>

        <div
            class="custom-scroll relative mx-auto grid h-full max-w-400 grid-cols-[220px_1fr] gap-5 overflow-y-auto max-[720px]:grid-cols-1"
        >
            <aside class="flex flex-col gap-4 py-8 max-[720px]:hidden">
                <Link
                    :href="dashboard()"
                    prefetch
                    class="mb-6 flex items-center gap-2.5"
                >
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/20 bg-white/10"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <rect
                                x="4"
                                y="3"
                                width="13"
                                height="18"
                                rx="2"
                                class="fill-white/5 stroke-white/70"
                                stroke-width="1.5"
                            />
                            <path
                                d="M5.5 13.5 H8 L9.5 10.5 L11.5 16 L13 13.5 H15.5"
                                class="stroke-brand-yellow"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                    <span
                        class="text-lg font-extrabold tracking-tight text-white"
                        >Admin</span
                    >
                </Link>

                <nav class="flex flex-col gap-1">
                    <Link
                        v-for="item in navItems"
                        :key="item.id"
                        :href="item.url"
                        prefetch
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-bold transition-all"
                        :class="
                            activeNav === item.url.url
                                ? 'bg-brand-yellow/10 text-brand-yellow'
                                : 'text-brand-gray hover:bg-white/4 hover:text-white'
                        "
                    >
                        <component
                            :is="item.icon"
                            :size="18"
                        />
                        {{ item.label }}
                    </Link>
                </nav>

                <div class="mt-auto flex flex-col gap-1 pt-8">
                    <Link
                        :href="dashboard()"
                        prefetch
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-bold text-brand-gray transition-all hover:bg-white/4 hover:text-white"
                    >
                        <ArrowLeft :size="18" />
                        Back to App
                    </Link>
                    <Link
                        :href="logout()"
                        method="get"
                        as="button"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-bold text-rose-400/60 transition-all hover:bg-rose-400/10 hover:text-rose-400"
                    >
                        <LogOut :size="18" />
                        Logout
                    </Link>
                </div>
            </aside>

            <!-- Mobile hamburger -->
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="fixed top-4 right-4 z-50 flex h-10 w-10 cursor-pointer items-center justify-center rounded-xl border border-white/10 bg-brand-surface text-white md:hidden"
            >
                <Menu
                    v-if="!mobileMenuOpen"
                    :size="20"
                />
                <X
                    v-else
                    :size="20"
                />
            </button>

            <slot />
        </div>

        <!-- Mobile sidebar overlay -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileMenuOpen"
                class="fixed inset-0 z-40 flex min-h-screen flex-col items-center justify-center bg-brand-bg md:hidden"
                @click.self="closeMenu"
            >
                <div class="flex w-full max-w-xs flex-col gap-6">
                    <Link
                        :href="dashboard()"
                        prefetch
                        @click="closeMenu"
                        class="flex items-center justify-center gap-2.5"
                    >
                        <span
                            class="text-2xl font-extrabold tracking-tight text-white"
                            >Admin</span
                        >
                    </Link>

                    <nav class="flex flex-col gap-2">
                        <Link
                            v-for="item in navItems"
                            :key="item.id"
                            :href="item.url"
                            prefetch
                            @click="closeMenu"
                            class="flex items-center justify-center gap-3 rounded-xl px-6 py-3.5 text-lg font-bold transition-all"
                            :class="
                                activeNav === item.url.url
                                    ? 'bg-brand-yellow/10 text-brand-yellow'
                                    : 'text-brand-gray hover:text-white'
                            "
                        >
                            <component
                                :is="item.icon"
                                :size="20"
                            />
                            {{ item.label }}
                        </Link>
                    </nav>

                    <div class="flex flex-col gap-2 pt-4">
                        <Link
                            :href="dashboard()"
                            prefetch
                            @click="closeMenu"
                            class="flex items-center justify-center gap-3 rounded-xl px-6 py-3 text-base font-bold text-brand-gray transition-colors hover:text-white"
                        >
                            <ArrowLeft :size="18" />
                            Back to App
                        </Link>
                        <Link
                            :href="logout()"
                            method="get"
                            as="button"
                            @click="closeMenu"
                            class="flex items-center justify-center gap-3 rounded-xl px-6 py-3 text-base font-bold text-rose-400/60 transition-colors hover:text-rose-400"
                        >
                            <LogOut :size="18" />
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
