<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onMounted, onBeforeUnmount, ref } from 'vue'
import { dashboard, register } from '@/routes'
import Logo from './Logo.vue'

defineProps<{
    canLogin?: boolean
    canRegister?: boolean
}>()

const user = computed(
    () => usePage().props.auth.user as Record<string, unknown> | undefined,
)
const isAuthenticated = computed(() => !!user.value)
const cta = computed(() =>
    isAuthenticated.value
        ? { label: 'Dashboard', href: dashboard(), icon: true }
        : { label: 'Get Started', href: register(), icon: true },
)

const navItems = [
    { name: 'Specialties', href: '#features' },
    { name: 'How it Works', href: '#how' },
    { name: 'AI', href: '#ai' },
    { name: 'Support', href: '#support' },
]

const activeNav = ref('')
const isMobileMenuOpen = ref(false)
let observer: IntersectionObserver | null = null

const scrollToSection = (href: string) => {
    const id = href.replace('#', '')
    const el = document.getElementById(id)
    if (el) el.scrollIntoView({ behavior: 'smooth' })
}

onMounted(() => {
    const ids = navItems.map((item) => item.href.replace('#', ''))

    observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    activeNav.value = `#${entry.target.id}`
                }
            }
        },
        { rootMargin: '-40% 0px -55% 0px' },
    )

    for (const id of ids) {
        const el = document.getElementById(id)
        if (el) observer.observe(el)
    }
})

onBeforeUnmount(() => {
    if (observer) observer.disconnect()
})
</script>

<template>
    <header
        class="fixed top-0 left-1/2 z-50 mb-20 w-[95%] max-w-6xl -translate-x-1/2 rounded-b-[1.75rem] border-x border-b border-white/10 bg-white/5 px-6 py-4 shadow-[0_8px_32px_rgba(0,0,0,0.5)] backdrop-blur-2xl transition-all duration-300 md:px-8 lg:w-[97%] lg:max-w-352"
    >
        <nav class="flex items-center justify-between">
            <div class="flex items-center gap-12">
                <Link
                    href="/"
                    view-transition
                    prefetch
                    class="group relative flex items-center gap-3"
                >
                    <Logo />
                </Link>

                <div class="hidden items-center gap-8 md:flex">
                    <a
                        v-for="item in navItems"
                        :key="item.name"
                        :href="item.href"
                        @click.prevent="scrollToSection(item.href)"
                        class="text-sm font-bold transition-all duration-300 hover:-translate-y-0.5"
                        :class="
                            activeNav === item.href
                                ? 'text-brand-yellow'
                                : 'text-white/60 hover:text-white'
                        "
                    >
                        {{ item.name }}
                    </a>
                </div>
            </div>

            <div class="ml-auto flex items-center gap-5">
                <Link
                    :href="cta.href"
                    view-transition
                    prefetch
                    class="hidden items-center gap-2.5 rounded-full bg-brand-yellow px-5 py-2.5 text-[13px] font-bold text-black transition-all hover:brightness-110 md:flex"
                >
                    {{ cta.label }}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                    >
                        <circle
                            cx="10"
                            cy="10"
                            r="10"
                            fill="#000"
                        />
                        <path
                            d="M7 10h7M11 7.5l2.5 2.5-2.5 2.5"
                            stroke="#F4FD3B"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            fill="none"
                        />
                    </svg>
                </Link>

                <button
                    @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="relative z-50 -mr-2 flex h-10 w-10 cursor-pointer items-center justify-center p-2 text-white md:hidden"
                >
                    <svg
                        class="absolute h-6 w-6 transition-all duration-300"
                        :class="
                            isMobileMenuOpen
                                ? 'scale-50 rotate-90 opacity-0'
                                : 'scale-100 rotate-0 opacity-100'
                        "
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 6h16M4 12h16m-7 6h7"
                        />
                    </svg>
                    <svg
                        class="absolute h-6 w-6 transition-all duration-300"
                        :class="
                            isMobileMenuOpen
                                ? 'scale-100 rotate-0 opacity-100'
                                : 'scale-50 -rotate-90 opacity-0'
                        "
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </nav>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isMobileMenuOpen"
                class="fixed inset-0 z-40 flex min-h-screen flex-col items-center justify-center bg-brand-bg md:hidden"
            >
                <div class="flex w-full flex-col items-center gap-8 px-8">
                    <a
                        v-for="(item, index) in navItems"
                        :key="item.name"
                        :href="item.href"
                        @click.prevent="
                            scrollToSection(item.href);
                            isMobileMenuOpen = false
                        "
                        class="animate-displaced-return text-3xl font-extrabold tracking-tight text-white opacity-0 transition-colors duration-300 hover:text-brand-yellow"
                        :style="`animation-delay: ${index * 75 + 100}ms`"
                    >
                        {{ item.name }}
                    </a>

                    <Link
                        :href="cta.href"
                        view-transition
                        prefetch
                        @click="isMobileMenuOpen = false"
                        class="animate-displaced-return mt-8 flex w-full max-w-70 items-center justify-center gap-3 rounded-full bg-brand-yellow px-8 py-4 text-lg font-bold text-black opacity-0 shadow-[0_0_30px_rgba(244,253,59,0.2)] transition-all hover:brightness-110"
                        :style="`animation-delay: ${navItems.length * 75 + 100}ms`"
                    >
                        {{ cta.label }}
                        <span
                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-black"
                        >
                            <svg
                                class="h-3 w-3 text-brand-yellow"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 12h14M12 5l7 7-7 7"
                                />
                            </svg>
                        </span>
                    </Link>
                </div>
            </div>
        </Transition>
    </header>
</template>

<style scoped>
@keyframes displacedReturn {
    0% {
        opacity: 0;
        transform: translateY(40px) scale(0.95) rotate(-2deg);
    }

    100% {
        opacity: 1;
        transform: translateY(0) scale(1) rotate(0);
    }
}

.animate-displaced-return {
    animation: displacedReturn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
