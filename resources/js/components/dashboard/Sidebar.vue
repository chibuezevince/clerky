<script setup lang="ts">
import { glass } from '@/data/dashboard'
import { NavItem } from '@/types/dashboard'
import { Link } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import Logo from '../Logo.vue'

const props = defineProps<{
    navItems: NavItem[]
    activeNav: string
}>()

const expandedSections = ref<Set<string>>(new Set())

watch(
    () => props.activeNav,
    (id) => {
        for (const item of props.navItems) {
            if (item.children?.some((c) => c.id === id)) {
                expandedSections.value = new Set([item.id])
                return
            }
        }
        expandedSections.value = new Set()
    },
    { immediate: true },
)

const toggleSection = (id: string) => {
    const next = new Set(expandedSections.value)
    next.has(id) ? next.delete(id) : next.add(id)
    expandedSections.value = next
}
</script>

<template>
    <aside
        :class="[
            glass,
            'sticky top-5 bottom-5 flex h-[95vh] w-55 flex-col items-stretch gap-6 self-start px-4 py-5',
            'max-[720px]:hidden',
        ]"
    >
        <Link
            href="/"
            view-transition
            prefetch
            class="group relative flex items-center gap-2"
        >
            <Logo :show-label="true" />
        </Link>

        <nav class="flex flex-col items-stretch gap-2">
            <template
                v-for="item in navItems"
                :key="item.id"
            >
                <template v-if="item.children">
                    <button
                        @click="toggleSection(item.id)"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left transition-all hover:bg-white/5"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5 shrink-0 text-brand-gray"
                        >
                            <path :d="item.icon" />
                        </svg>
                        <span class="text-[13px] font-bold text-brand-gray">
                            {{ item.label }}
                        </span>
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            class="ml-auto h-4 w-4 shrink-0 text-white/25 transition-transform"
                            :class="
                                expandedSections.has(item.id) ? 'rotate-90' : ''
                            "
                        >
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                    <div
                        v-if="expandedSections.has(item.id)"
                        class="ml-3 flex flex-col gap-1 border-l border-white/6 pl-3"
                    >
                        <Link
                            v-for="child in item.children"
                            :key="child.id"
                            :title="child.label"
                            :href="child.url"
                            view-transition
                            prefetch
                            :class="[
                                'flex h-11 w-full shrink-0 cursor-pointer items-center justify-start rounded-xl border px-3 transition-all',
                                activeNav === child.id
                                    ? 'border-brand-yellow/20 bg-brand-yellow/10 text-brand-yellow'
                                    : 'border-transparent text-brand-gray hover:bg-white/5 hover:text-neutral-50',
                            ]"
                        >
                            <span
                                class="text-[13px] font-medium whitespace-nowrap"
                            >
                                {{ child.label }}
                            </span>
                        </Link>
                    </div>
                </template>

                <Link
                    v-else
                    :title="item.label"
                    :href="item.url"
                    view-transition
                    prefetch
                    :class="[
                        'flex h-11 w-full shrink-0 cursor-pointer items-center justify-start gap-3 rounded-xl border px-3 transition-all',
                        activeNav === item.id
                            ? 'border-brand-yellow/20 bg-brand-yellow/10 text-brand-yellow'
                            : 'border-transparent text-brand-gray hover:bg-white/5 hover:text-neutral-50',
                    ]"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-5 w-5 shrink-0"
                    >
                        <path :d="item.icon" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap">
                        {{ item.label }}
                    </span>
                </Link>
            </template>
        </nav>

        <div
            class="mt-auto flex items-center gap-3 rounded-xl border border-white/6 px-3 py-2.5"
        >
            <div
                class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand-yellow/20 text-[13px] font-bold text-brand-yellow"
            >
                <img
                    v-if="$page.props.auth.user.details?.avatar"
                    :src="`${$page.props.auth.user.details.avatar}`"
                    class="h-full w-full object-cover"
                />
                <template v-else>
                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                </template>
            </div>
            <div class="flex min-w-0 flex-col">
                <span
                    class="truncate text-[13px] font-medium text-neutral-50"
                    >{{ $page.props.auth.user.name }}</span
                >
                <span class="truncate text-[11px] text-brand-gray/60"
                    >@{{ $page.props.auth.user.username }}</span
                >
                <span class="truncate text-[11px] text-brand-gray">{{
                    $page.props.auth.user.email
                }}</span>
            </div>
        </div>
    </aside>
</template>
