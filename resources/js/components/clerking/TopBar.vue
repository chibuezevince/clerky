<script setup lang="ts">
import { Stethoscope, ArrowLeft } from '@lucide/vue'
import { glass } from '@/data/dashboard.js'
import { Clerking } from '@/types/dashboard'
import { Link } from '@inertiajs/vue3'
import { dashboard } from '@/routes'
import CountUpTimer from './CountUpTimer.vue'

const props = defineProps<{
    clerking: Clerking
    processing?: boolean
}>()
</script>

<template>
    <div
        :class="[
            glass,
            'mt-5 flex flex-col gap-5 p-4 transition-all duration-300 hover:border-white/10 md:flex-row md:items-center md:justify-between md:px-6 md:py-4 xl:px-8 xl:py-5',
        ]"
    >
        <div
            class="flex items-center justify-between md:justify-start md:gap-8"
        >
            <div class="flex items-center gap-4">
                <Link
                    :href="dashboard()"
                    view-transition
                    prefetch
                    class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/5 bg-white/5 text-white transition-transform active:scale-90 md:hidden"
                >
                    <ArrowLeft :size="22" />
                </Link>

                <div class="group relative hidden md:block">
                    <div
                        class="absolute -inset-2 rounded-full bg-brand-yellow/10 opacity-0 blur-md transition-opacity group-hover:opacity-100"
                    ></div>
                    <div
                        class="relative flex h-12 w-12 items-center justify-center rounded-2xl border border-brand-yellow/20 bg-brand-yellow/10 text-brand-yellow"
                    >
                        <Stethoscope :size="24" />
                    </div>
                </div>

                <div class="flex items-center gap-4 xl:gap-8">
                    <div
                        class="flex flex-col gap-0.5 md:items-start"
                        v-if="clerking?.patient"
                    >
                        <span
                            class="text-[9px] font-bold tracking-[0.15em] text-brand-gray uppercase md:tracking-widest"
                            >Patient</span
                        >
                        <span
                            class="text-xs font-extrabold tracking-tight text-white xl:text-sm"
                            >{{ clerking.patient?.name }}</span
                        >
                    </div>

                    <div
                        class="hidden h-8 w-px bg-white/8 md:block xl:h-10"
                    ></div>

                    <div class="flex flex-col gap-0.5 md:items-start">
                        <span
                            class="text-[9px] font-bold tracking-[0.15em] text-brand-gray uppercase md:tracking-widest"
                            >Case ID</span
                        >
                        <span
                            class="text-xs font-extrabold tracking-tight text-white xl:text-sm"
                            >{{ clerking.case_number }}</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <CountUpTimer
            :clerking="props.clerking"
            :processing="props.processing ?? props.clerking.is_processing"
        />
    </div>
</template>
