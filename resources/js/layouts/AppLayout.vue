<script lang="ts" setup>
import 'vue-sonner/style.css'
import Logo from '@/components/Logo.vue'
import { navItems as rawNavItems, glass } from '@/data/dashboard'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, onBeforeUnmount, onMounted, onUnmounted, ref } from 'vue'
import { toast, Toaster } from 'vue-sonner'
import { NavItem } from '@/types/dashboard'
import Sidebar from '@/components/dashboard/Sidebar.vue'
import BottomBar from '@/components/dashboard/BottomBar.vue'

const props = withDefaults(
    defineProps<{
        pageHeight?: 'h-full' | 'h-screen'
    }>(),
    {
        pageHeight: 'h-screen',
    },
)
const navItems = rawNavItems as NavItem[]
const activeNav = computed(() => {
    const path = usePage().url

    const items = [...navItems]
        .filter((item) => item.id !== 'logout')
        .flatMap((item) => {
            const parent = {
                ...item,
                url: typeof item.url === 'string' ? item.url : item.url.url,
            }
            const children =
                item.children?.map((c) => ({
                    ...c,
                    url: typeof c.url === 'string' ? c.url : c.url.url,
                })) ?? []
            return [parent, ...children]
        })
        .sort((a, b) => b.url.length - a.url.length)

    for (const item of items)
        if (path === item.url || path.startsWith(`${item.url}/`)) return item.id

    return 'dashboard'
})

const isScrolled = ref(false)
let rafId: number | null = null

const handleScroll = () => {
    if (rafId !== null) return
    rafId = requestAnimationFrame(() => {
        isScrolled.value = window.scrollY > 32
        rafId = null
    })
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true })
    handleScroll()
})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll)
    if (rafId !== null) cancelAnimationFrame(rafId)
})

const toastListener = router.on('flash', (event) => {
    const toastKeys = [
        'success',
        'error',
        'info',
        'warning',
        'message',
    ] as const
    for (const key of toastKeys) {
        if (event.detail.flash[key]) {
            toast[key](event.detail.flash[key])
            break
        }
    }
})

onUnmounted(() => toastListener())
</script>

<template>
    <div
        class="relative h-screen overflow-hidden bg-brand-bg px-6 font-sans text-neutral-50 max-[720px]:px-4 max-[720px]:pt-6"
    >
        <div
            class="pointer-events-none absolute -top-40 left-[30%] h-125 w-125 rounded-full bg-brand-yellow opacity-[0.08] blur-[120px]"
        ></div>
        <div
            class="pointer-events-none absolute top-[20%] -left-24 h-100 w-100 rounded-full bg-teal-300 opacity-[0.06] blur-[120px]"
        ></div>
        <div
            class="pointer-events-none absolute right-[10%] -bottom-40 h-112.5 w-112.5 rounded-full bg-brand-yellow opacity-[0.05] blur-[120px]"
        ></div>

        <div
            class="pointer-events-none absolute inset-0"
            style="
                background-image:
                    linear-gradient(
                        to right,
                        rgba(244, 253, 59, 0.25) 1px,
                        transparent 1px
                    ),
                    linear-gradient(
                        to bottom,
                        rgba(244, 253, 59, 0.25) 1px,
                        transparent 1px
                    );
                background-size: 140px 140px;
            "
        ></div>

        <div
            class="custom-scroll relative mx-auto grid h-full max-w-400 grid-cols-[220px_1fr_340px] gap-5 overflow-y-auto max-[1100px]:grid-cols-[220px_1fr] max-[720px]:grid-cols-1 max-[720px]:pb-24"
        >
            <Sidebar
                :nav-items="navItems"
                :active-nav="activeNav"
            />

            <slot />
        </div>
    </div>

    <BottomBar
        :nav-items="navItems"
        :active-nav="activeNav"
    />

    <Toaster
        theme="dark"
        :toast-options="{
            style: {
                background: 'var(--color-brand-surface)',
                color: '#ffffff',
                border: '1px solid var(--color-brand-border)',
                borderRadius: '12px',
                padding: '12px 14px',
                fontFamily: 'var(--font-sans)',
                boxShadow: '0 10px 30px rgba(0,0,0,0.35)',
            },
        }"
    />
</template>
