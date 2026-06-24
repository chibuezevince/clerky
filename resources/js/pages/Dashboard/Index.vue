<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import Header from '@/components/dashboard/Header.vue'
import ClerkingPerformanceChart from '@/components/dashboard/ClerkingPerformanceChart.vue'
import LastestClerkings from '@/components/dashboard/LastestClerkings.vue'
import PendingSummaries from '@/components/dashboard/PendingSummaries.vue'
import RecentSummaries from '@/components/dashboard/RecentSummaries.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { useSummaryListeners } from '@/composables/useSummaryListeners'
import { computed } from 'vue'
import type { Clerking, Summary } from '@/types/dashboard'

defineOptions({
    layout: AppLayout,
})

const page = usePage()

const changePeriod = (period: string) => {
    router.reload({
        data: { period },
        only: ['trend', 'activePeriod'],

        onSuccess: () => (page.props.activePeriod = period),
    })
}

useSummaryListeners(
    computed(() => page.props.pendingSummaries as Summary[]),
    computed(() => page.props.recentSummaries as Summary[]),
)
</script>

<template>
    <Head title="Dashboard" />

    <main class="flex min-w-0 flex-col gap-5">
        <Header />

        <ClerkingPerformanceChart
            :trend="
                $page.props.trend as Array<{ date: string; aggregate: number }>
            "
            :active-period="$page.props.activePeriod as string"
            :total-clerkings="$page.props.casesClerked as number"
            :delta="$page.props.clerkedDelta as number"
            @period-change="changePeriod($event)"
        />
    </main>

    <aside
        class="flex flex-col gap-5 max-[1100px]:col-span-full max-[1100px]:flex-row max-[720px]:flex-col"
    >
        <LastestClerkings
            :latest-clerkings="$page.props.latestClerkings as Clerking[]"
        />

        <PendingSummaries
            :pending-summaries="$page.props.pendingSummaries as Summary[]"
            :total-completed="$page.props.totalCompleted as number"
        />

        <RecentSummaries
            :recent-summaries="$page.props.recentSummaries as Summary[]"
        />
    </aside>
</template>
