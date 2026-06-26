<script setup>
import { glass, pillBtn } from '@/data/dashboard.js'
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Search from './Search.vue'
import StatCard from './StatCard.vue'
import NotificationBell from './NotificationBell.vue'
import { Book, ChartPie, ClipboardList, Clock, Plus } from '@lucide/vue'
import { start } from '@/routes/clerk'
const initials = computed(() =>
    page.props.auth.user.name
        .split(' ')
        .map((n) => n.charAt(0).toUpperCase())
        .slice(0, 2)
        .join(''),
)

const page = usePage()

const emit = defineEmits(['active'])

const shortEmail = computed(() => {
    const email = page.props.auth.user.email
    const [name, domain] = email.split('@')
    return `${name.slice(0, 6)}...@${domain}`
})
</script>
<template>
    <header :class="[glass, 'relative z-50 p-5 sm:p-8 lg:mt-5']">
        <div class="relative flex flex-col gap-10">
            <div
                class="flex flex-col gap-6 lg:flex-row lg:items-center lg:gap-8"
            >
                <div class="flex w-full min-w-0 items-center justify-center">
                    <Search />
                </div>

                <div
                    class="flex shrink-0 items-center justify-center gap-4 lg:justify-end"
                >
                    <div class="flex items-center gap-3 lg:hidden">
                        <div
                            class="hidden flex-col items-end gap-1 leading-none sm:flex"
                        >
                            <span
                                class="text-[13px] font-bold tracking-tight whitespace-nowrap text-white"
                            >
                                Dr. {{ $page.props.auth.user.name }}
                            </span>
                            <span
                                class="text-[10px] font-medium whitespace-nowrap text-brand-gray"
                            >
                                {{ shortEmail }}
                            </span>
                        </div>
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full border border-white/10 bg-linear-to-br from-brand-yellow/30 to-teal-500/30 text-[10px] font-black text-white/40 shadow-inner backdrop-blur-sm"
                        >
                            <img
                                v-if="$page.props.auth.user.details?.avatar"
                                :src="`${$page.props.auth.user.details.avatar}`"
                                class="h-full w-full object-cover"
                            />
                            <template v-else>
                                {{ initials }}
                            </template>
                        </div>
                    </div>

                    <Link
                        :href="start()"
                        view-transition
                        prefetch
                        :class="[
                            pillBtn,
                            'flex flex-1 items-center justify-center gap-2 border border-brand-yellow bg-brand-yellow px-6 font-bold whitespace-nowrap text-brand-bg shadow-[0_0_20px_rgba(244,253,59,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(244,253,59,0.4)] active:scale-95 sm:flex-initial',
                        ]"
                    >
                        <Plus class="h-4 w-4 shrink-0" />
                        <span>Start New Case</span>
                    </Link>

                    <NotificationBell />
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <h1
                    class="flex items-center gap-2 text-xl font-extrabold tracking-tight text-white sm:text-2xl"
                >
                    Welcome back, {{ $page.props.auth.user.name.split(' ')[0] }}
                </h1>
                <p
                    class="text-[12px] font-medium text-brand-gray-light sm:text-sm"
                >
                    Let's make today a productive one for your patients.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <StatCard
                    title="Total Cases"
                    :value="String($page.props.casesClerked)"
                    :delta="Number($page.props.clerkedDelta)"
                    :icon="ClipboardList"
                />

                <StatCard
                    title="Completion Rate"
                    :value="
                        String(`${Math.round($page.props.completionRate)}%`)
                    "
                    :delta="Number($page.props.rateDelta)"
                    :icon="ChartPie"
                />

                <StatCard
                    title="Summaries"
                    :value="String($page.props.summaries)"
                    :delta="Number($page.props.summariesDelta)"
                    :icon="Book"
                />

                <StatCard
                    title="Avg. Time"
                    :value="
                        (() => {
                            const total = Math.round(
                                $page.props.avgCompletionTime,
                            )
                            const hours = Math.floor(total / 60)
                            const mins = total % 60
                            return hours > 0 ? `${hours}h ${mins}m` : `${mins}m`
                        })()
                    "
                    :delta="Number($page.props.avgDelta)"
                    :icon="Clock"
                />
            </div>
        </div>
    </header>
</template>
