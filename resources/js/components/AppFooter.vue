<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { contribute, dashboard, donate, settings } from '@/routes'
import { all } from '@/routes/cases'
import { start } from '@/routes/clerk'
import { index as sectionQuestionsIndex } from '@/routes/section-questions'
import Logo from './Logo.vue'

const isExternal = (href: unknown): href is string =>
    typeof href === 'string' && href.startsWith('http')

const platformLinks = [
    { name: 'Dashboard', href: dashboard() },
    { name: 'Cases', href: all() },
    { name: 'Start Clerking', href: start() },
    { name: 'Settings', href: settings() },
]

const contributeLinks = [
    { name: 'Complaint Questions', href: contribute() },
    { name: 'Section Questions', href: sectionQuestionsIndex() },
    { name: 'Donate', href: 'https://paystack.shop/pay/ug8-4-bulr' },
]
</script>

<template>
    <footer
        class="relative z-10 overflow-hidden border-t border-brand-border py-24"
    >
        <div class="mx-auto max-w-7xl px-8 md:px-16">
            <div class="mb-20 grid grid-cols-1 gap-12 md:grid-cols-3">
                <div>
                    <Link
                        href="/"
                        view-transition
                        prefetch
                        class="group relative flex items-center gap-3"
                    >
                        <Logo :show-blur="false" />
                    </Link>
                    <p
                        class="mb-8 max-w-xs text-sm leading-relaxed text-brand-gray-light"
                    >
                        Structured clinical clerking built by a medical student,
                        free and open-source.
                    </p>
                    <div class="flex gap-3">
                        <a
                            href="https://github.com/chibuezevince/clerky"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-brand-border bg-brand-surface transition-colors hover:border-white/20"
                        >
                            <svg
                                class="h-4 w-4 text-brand-gray"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"
                                />
                            </svg>
                        </a>
                        <a
                            href="https://x.com/chibuezevince"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-brand-border bg-brand-surface transition-colors hover:border-white/20"
                        >
                            <svg
                                class="h-4 w-4 text-brand-gray"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                                />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4
                        class="mb-6 text-[11px] font-bold tracking-widest text-white uppercase"
                    >
                        Platform
                    </h4>
                    <ul class="space-y-3">
                        <li
                            v-for="link in platformLinks"
                            :key="link.name"
                        >
                            <Link
                                :href="link.href"
                                prefetch
                                view-transition
                                class="text-sm text-brand-gray transition-colors hover:text-brand-yellow"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4
                        class="mb-6 text-[11px] font-bold tracking-widest text-white uppercase"
                    >
                        Contribute
                    </h4>
                    <ul class="space-y-3">
                        <li
                            v-for="link in contributeLinks"
                            :key="link.name"
                        >
                            <a
                                v-if="isExternal(link.href)"
                                :href="link.href"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm text-brand-gray transition-colors hover:text-brand-yellow"
                            >
                                {{ link.name }}
                            </a>
                            <Link
                                v-else
                                :href="link.href as any"
                                prefetch
                                view-transition
                                class="text-sm text-brand-gray transition-colors hover:text-brand-yellow"
                            >
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="flex flex-col items-center justify-between gap-6 border-t border-brand-border pt-16 md:flex-row"
            >
                <p
                    class="text-[10px] font-bold tracking-widest text-brand-gray uppercase"
                >
                    &copy; 2026 Clerky. Open-source clinical clerking.
                </p>
            </div>
        </div>
    </footer>
</template>
