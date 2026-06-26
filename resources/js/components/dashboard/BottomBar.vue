<script setup lang="ts">
import { NavItem } from '@/types/dashboard'
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

const props = defineProps<{
    navItems: NavItem[]
    activeNav: string
}>()

const mobileDockNavItems = computed(() => {
    const logoutItem = props.navItems.find((i) => i.id === 'logout')
    return props.navItems
        .filter((i) => i.id !== 'logout')
        .map((i) => {
            if (i.id === 'settings' && logoutItem) {
                return {
                    ...i,

                    children: [
                        { ...i, children: undefined },
                        { ...logoutItem, destructive: true },
                    ],
                }
            }
            return i
        })
})
const mobileDockParent = ref<string | null>(null)

watch(
    () => props.activeNav,
    (id) => {
        for (const item of mobileDockNavItems.value) {
            if (item.children?.some((c) => c.id === id)) {
                mobileDockParent.value = item.id
                return
            }
        }

        if (id === 'logout') mobileDockParent.value = 'settings'
    },
    { immediate: true },
)

const activeDockSection = computed(() => {
    const id = props.activeNav
    for (const item of mobileDockNavItems.value) {
        if (item.id === id) return item.id
        if (item.children?.some((c) => c.id === id)) return item.id
    }

    if (id === 'logout') return 'settings'
    return mobileDockNavItems.value[0]?.id ?? 'dashboard'
})

type DockEntry =
    | { kind: 'back'; id: '__back__' }
    | {
          kind: 'trigger'
          id: string
          label: string
          icon: string
          active: boolean
      }
    | {
          kind: 'link'
          id: string
          label: string
          icon: string | undefined
          url: string
          active: boolean
          destructive?: boolean
      }

const dockEntries = computed((): DockEntry[] => {
    if (mobileDockParent.value) {
        const parent = mobileDockNavItems.value.find(
            (i) => i.id === mobileDockParent.value,
        )
        const children: DockEntry[] = (parent?.children ?? []).map((child) => ({
            kind: 'link',
            id: child.id,
            label: child.label,
            icon: child.icon,
            url: typeof child.url === 'string' ? child.url : child.url.url,
            active: props.activeNav === child.id,
            destructive: (child as any).destructive ?? false,
        }))
        return [{ kind: 'back', id: '__back__' as const }, ...children]
    }

    return mobileDockNavItems.value.slice(0, 5).map((item): DockEntry => {
        if (item.children?.length) {
            return {
                kind: 'trigger',
                id: item.id,
                label: item.label,
                icon: item.icon,
                active: activeDockSection.value === item.id,
            }
        }
        return {
            kind: 'link',
            id: item.id,
            label: item.label,
            icon: item.icon,
            url: typeof item.url === 'string' ? item.url : item.url.url,
            active: props.activeNav === item.id,
        }
    })
})
</script>

<template>
    <nav
        class="fixed inset-x-4 bottom-0 z-50 mb-px hidden overflow-hidden rounded-[28px] border border-white/10 backdrop-blur-xl max-[720px]:block"
    >
        <div
            class="relative w-full"
            style="min-height: 64px"
        >
            <Transition name="dock-parent">
                <div
                    v-if="!mobileDockParent"
                    class="absolute inset-0 flex items-center justify-around px-2 py-2"
                >
                    <template
                        v-for="entry in dockEntries"
                        :key="entry.id"
                    >
                        <button
                            v-if="entry.kind === 'trigger'"
                            @click="mobileDockParent = entry.id"
                            :title="entry.label"
                            class="flex flex-col items-center gap-1 rounded-[20px] px-3 py-2 transition-colors duration-200"
                            :class="
                                entry.active
                                    ? 'bg-brand-yellow'
                                    : 'bg-transparent'
                            "
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5 shrink-0"
                                :class="
                                    entry.active
                                        ? 'text-brand-bg'
                                        : 'text-white/50'
                                "
                            >
                                <path :d="entry.icon" />
                            </svg>
                            <span
                                class="text-[10px] leading-none font-medium whitespace-nowrap"
                                :class="
                                    entry.active
                                        ? 'text-brand-bg'
                                        : 'text-white/40'
                                "
                                >{{ entry.label }}</span
                            >
                        </button>
                        <button
                            v-else-if="entry.kind === 'link'"
                            :title="entry.label"
                            @click="
                                router.get(entry.url, undefined, {
                                    viewTransition: true,
                                })
                            "
                            class="flex flex-col items-center gap-1 rounded-[20px] px-3 py-2 transition-colors duration-200"
                            :class="
                                entry.active
                                    ? 'bg-brand-yellow'
                                    : 'bg-transparent'
                            "
                        >
                            <svg
                                v-if="entry.icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5 shrink-0"
                                :class="
                                    entry.active
                                        ? 'text-brand-bg'
                                        : 'text-white/50'
                                "
                            >
                                <path :d="entry.icon" />
                            </svg>
                            <span
                                v-else
                                class="h-1.5 w-1.5 rounded-full"
                                :class="
                                    entry.active ? 'bg-brand-bg' : 'bg-white/40'
                                "
                            />
                            <span
                                class="text-[10px] leading-none font-medium whitespace-nowrap"
                                :class="
                                    entry.active
                                        ? 'text-brand-bg'
                                        : 'text-white/40'
                                "
                                >{{ entry.label }}</span
                            >
                        </button>
                    </template>
                </div>
            </Transition>

            <Transition name="dock-child">
                <div
                    v-if="mobileDockParent"
                    class="absolute inset-0 flex items-center justify-around px-2 py-2"
                >
                    <button
                        @click="mobileDockParent = null"
                        class="flex flex-col items-center gap-1 rounded-[20px] px-3 py-2 transition-colors duration-200"
                        style="background: rgba(255, 255, 255, 0.08)"
                        title="Back"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5 shrink-0 text-white/60"
                        >
                            <path d="M19 12H5M12 5l-7 7 7 7" />
                        </svg>
                        <span
                            class="text-[10px] leading-none font-medium whitespace-nowrap text-white/35"
                            >Back</span
                        >
                    </button>
                    <template
                        v-for="entry in dockEntries"
                        :key="entry.id"
                    >
                        <button
                            v-if="entry.kind === 'link'"
                            :title="entry.label"
                            @click="
                                router.get(entry.url, undefined, {
                                    viewTransition: true,
                                })
                            "
                            class="flex flex-col items-center gap-1 rounded-[20px] px-3 py-2 transition-colors duration-200"
                            :class="
                                entry.active
                                    ? 'bg-brand-yellow'
                                    : 'bg-transparent'
                            "
                        >
                            <svg
                                v-if="entry.icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5 shrink-0"
                                :class="
                                    entry.active
                                        ? 'text-brand-bg'
                                        : entry.destructive
                                          ? 'text-rose-400/70'
                                          : 'text-white/50'
                                "
                            >
                                <path :d="entry.icon" />
                            </svg>
                            <span
                                v-else
                                class="h-1.5 w-1.5 rounded-full"
                                :class="
                                    entry.active ? 'bg-brand-bg' : 'bg-white/40'
                                "
                            />
                            <span
                                class="text-[10px] leading-none font-medium whitespace-nowrap"
                                :class="
                                    entry.active
                                        ? 'text-brand-bg'
                                        : entry.destructive
                                          ? 'text-rose-400/70'
                                          : 'text-white/40'
                                "
                                >{{ entry.label }}</span
                            >
                        </button>
                    </template>
                </div>
            </Transition>
        </div>
    </nav>
</template>
