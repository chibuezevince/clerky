<script setup>
import { computed } from 'vue'
import VueApexCharts from 'vue3-apexcharts'
import { glass } from '@/data/dashboard.js'

const props = defineProps({
    trend: {
        type: Array,
        default: () => [],
    },
    totalClerkings: {
        type: Number,
        default: null,
    },
    delta: {
        type: Number,
        default: null,
    },
    activePeriod: {
        type: String,
        default: '1M',
    },
})

const dateRange = computed(() => {
    const start = new Date(props.trend[0].date)
    const end = new Date(props.trend[props.trend.length - 1].date)

    const startFormatted = start.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
    })
    const endFormatted = end.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
    })

    return `${startFormatted} - ${endFormatted}`
})

const emit = defineEmits(['period-change'])

const periods = ['1W', '1M', '1Y', 'MAX']

const series = computed(() => [
    {
        name: 'Clerkings',
        data: props.trend.map((t) => ({
            x: new Date(t.date).getTime(),
            y: t.aggregate,
        })),
    },
])

const chartOptions = computed(() => ({
    chart: {
        type: 'area',
        background: 'transparent',
        toolbar: { show: false },
        zoom: { enabled: false },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 400,
        },
        fontFamily: 'inherit',
    },
    stroke: {
        curve: 'smooth',
        width: 3,
        colors: ['#f4fd3b'],
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.25,
            opacityTo: 0,
            stops: [0, 100],
            colorStops: [
                { offset: 0, color: '#f4fd3b', opacity: 0.25 },
                { offset: 100, color: '#f4fd3b', opacity: 0 },
            ],
        },
    },
    markers: {
        size: 0,
        hover: {
            size: 6,
            fillColor: '#f4fd3b',
            strokeColor: '#0a0a0a',
            strokeWidth: 2,
        },
    },
    xaxis: {
        type: 'datetime',
        labels: {
            style: {
                colors: '#6b7280',
                fontSize: '11px',
                fontFamily: 'monospace',
            },
            datetimeFormatter: {
                day: 'dd MMM',
                month: 'MMM yy',
            },
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
        crosshairs: {
            stroke: {
                color: 'rgba(244,253,59,0.3)',
                dashArray: 3,
                width: 1,
            },
        },
    },
    yaxis: {
        labels: {
            style: {
                colors: '#6b7280',
                fontSize: '11px',
                fontFamily: 'monospace',
            },
            formatter: (val) => Math.round(val),
        },
    },
    grid: {
        borderColor: 'rgba(255,255,255,0.04)',
        strokeDashArray: 0,
        xaxis: { lines: { show: false } },
        yaxis: { lines: { show: true } },
        padding: { left: 0, right: 0 },
    },
    tooltip: {
        theme: 'dark',
        x: { format: 'dd MMM yyyy' },
        y: {
            formatter: (val) => `${val} clerkings`,
        },
        marker: { fillColors: ['#f4fd3b'] },
        style: { fontSize: '12px', fontFamily: 'monospace' },
    },
    dataLabels: { enabled: false },
    legend: { show: false },
    noData: {
        text: 'No clerkings yet',
        style: {
            color: '#6b7280',
            fontSize: '13px',
            fontFamily: 'monospace',
        },
    },
}))
</script>
<template>
    <section :class="[glass, 'p-6']">
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="mb-3 text-[22px] font-bold tracking-tight">
                    Clerking Performance
                </h2>
                <div class="flex items-center gap-3">
                    <div
                        class="grid h-9.5 w-9.5 place-items-center rounded-[10px] border border-brand-yellow/20 bg-brand-yellow/10"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#f4fd3b"
                            stroke-width="1.6"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5"
                        >
                            <path
                                d="M9 4a3 3 0 0 0-3 3 2.5 2.5 0 0 0-2 4 2.5 2.5 0 0 0 1 4.5A3 3 0 0 0 9 20V4z M15 4a3 3 0 0 1 3 3 2.5 2.5 0 0 1 2 4 2.5 2.5 0 0 1-1 4.5A3 3 0 0 1 15 20V4z"
                            />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs text-brand-gray">
                            Clerkings Over Time
                        </div>
                        <div class="mt-0.5 font-mono text-lg font-bold">
                            <template v-if="totalClerkings !== null">
                                {{ totalClerkings }}
                                <span
                                    v-if="delta !== null"
                                    :class="
                                        delta >= 0
                                            ? 'text-teal-300'
                                            : 'text-orange-400'
                                    "
                                    class="text-[11px]"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="inline h-3 w-3"
                                    >
                                        <path
                                            v-if="delta >= 0"
                                            d="M12 19V5 M5 12l7-7 7 7"
                                        />
                                        <path
                                            v-else
                                            d="M12 5v14 M5 12l7 7 7-7"
                                        />
                                    </svg>
                                    {{ delta >= 0 ? '+' : '' }}{{ delta }}%
                                </span>
                            </template>
                            <template v-else>—</template>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex w-full flex-col items-center gap-2.5 sm:w-auto sm:items-end"
            >
                <div
                    class="flex gap-1 rounded-full border border-white/5 bg-black/30 p-1"
                >
                    <button
                        v-for="p in periods"
                        :key="p"
                        @click.prevent="emit('period-change', p)"
                        :class="[
                            'cursor-pointer rounded-full border-0 px-3.5 py-1.5 text-xs font-semibold',
                            activePeriod === p
                                ? 'bg-brand-yellow text-brand-bg shadow-[0_0_16px_rgba(244,253,59,0.4)]'
                                : 'bg-transparent text-brand-gray-light',
                        ]"
                    >
                        {{ p }}
                    </button>
                </div>
                <div
                    class="inline-flex items-center gap-1.5 rounded-full border border-white/5 bg-black/30 px-3 py-1.5 text-xs text-brand-gray-light"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.6"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="h-3.5 w-3.5"
                    >
                        <path
                            d="M3 7h18 M5 4v3 M19 4v3 M4 9h16v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9z"
                        />
                    </svg>
                    <span>{{ dateRange }}</span>
                </div>
            </div>
        </div>

        <VueApexCharts
            :key="activePeriod"
            type="area"
            height="280"
            :options="chartOptions"
            :series="series"
        />
    </section>
</template>
