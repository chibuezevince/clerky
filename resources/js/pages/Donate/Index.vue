<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard'
import {
    Heart,
    ArrowLeftCircle,
    ShieldCheck,
    Star,
    ChevronRight,
    Share2,
    Building2,
    ExternalLink,
} from '@lucide/vue'
import { dashboard } from '@/routes'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })

const amounts = [1000, 2000, 3000, 5000]
const selectedAmount = ref(2000)
const customAmount = ref('')

const selectAmount = (amount: number) => {
    selectedAmount.value = amount
    customAmount.value = ''
}

const donate = () => {
    window.location.href = `https://paystack.shop/pay/ug8-4-bulr`
}

const goals = [
    { icon: Star, text: 'Keep the project free and open source' },
    { icon: Star, text: 'Support ongoing feature development' },
    { icon: Star, text: 'Improve medical education tools' },
    { icon: Star, text: 'Expand to more specialties and regions' },
    { icon: Star, text: 'Maintain server and AI infrastructure' },
]
</script>

<template>
    <Head title="Donate" />

    <div class="flex max-w-2xl flex-col gap-10 py-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">
                Support Clerky
            </h1>
            <p
                class="mt-2 max-w-lg text-[14px] leading-relaxed text-brand-gray"
            >
                Clerky is an open-source project built by medical students, for
                medical students. Your donation helps us keep the platform free
                and continuously improve our tools.
            </p>
        </div>

        <div :class="[glass, 'p-6']">
            <h2
                class="mb-1.5 text-[11px] font-bold tracking-wider text-brand-gray uppercase"
            >
                Select Amount
            </h2>
            <div class="mt-3 flex flex-wrap items-center gap-2">
                <button
                    v-for="amount in amounts"
                    :key="amount"
                    @click="selectAmount(amount)"
                    class="cursor-pointer rounded-lg px-5 py-2.5 text-sm font-extrabold transition-all"
                    :class="
                        selectedAmount === amount && !customAmount
                            ? 'bg-brand-yellow text-brand-bg'
                            : 'bg-white/[5 text-white hover:bg-white/8'
                    "
                >
                    ₦{{ amount }}
                </button>
                <div
                    class="bg-white/[5 flex items-center gap-1.5 rounded-lg px-4 py-2.5"
                    :class="customAmount ? 'ring-2 ring-brand-yellow/40' : ''"
                >
                    <span class="text-sm font-semibold text-brand-gray">₦</span>
                    <input
                        v-model="customAmount"
                        type="text"
                        placeholder="Custom"
                        class="w-16 min-w-0 bg-transparent text-sm font-semibold text-white outline-none placeholder:text-white/20"
                    />
                </div>
            </div>
        </div>

        <div :class="[glass, 'p-6']">
            <h2
                class="mb-3 text-[11px] font-bold tracking-wider text-brand-gray uppercase"
            >
                Payment Method
            </h2>
            <div class="flex gap-2">
                <button
                    class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-brand-yellow/30 bg-brand-yellow/4 px-4 py-3 text-sm font-bold text-brand-yellow transition-all"
                >
                    <img
                        src="/images/Paystack_Logo.png"
                        class="w-20"
                    />
                </button>
            </div>
        </div>

        <div :class="[glass, 'p-6']">
            <button
                @click="donate"
                class="mt-5 w-full cursor-pointer rounded-xl bg-brand-yellow px-6 py-3.5 text-sm font-extrabold text-brand-bg transition-all hover:bg-brand-yellow/90"
            >
                Donate ₦{{ customAmount || selectedAmount }}
            </button>
        </div>
    </div>
    <aside class="flex flex-col gap-5 py-8">
        <div></div>

        <div :class="[glass, 'p-6']">
            <h3 class="mb-4 text-sm font-extrabold text-white">
                Your contribution helps us
            </h3>
            <div class="space-y-3">
                <div
                    v-for="goal in goals"
                    :key="goal.text"
                    class="flex items-start gap-2.5"
                >
                    <component
                        :is="goal.icon"
                        :size="14"
                        class="mt-0.5 shrink-0 text-brand-yellow"
                    />
                    <span class="text-[13px] leading-relaxed text-white/80">
                        {{ goal.text }}
                    </span>
                </div>
            </div>
        </div>
        <div :class="[glass, 'p-6']">
            <h3 class="mb-4 text-sm font-extrabold text-white">About Clerky</h3>
            <div class="space-y-2.5">
                <button
                    class="flex w-full cursor-pointer items-center gap-3 rounded-xl border border-white/6 bg-white/3 px-4 py-3 text-left transition-all hover:border-white/12"
                >
                    <div class="min-w-0">
                        <p class="mt-0.5 text-[11px] text-brand-gray">
                            I was clerking a patient one day and it was so
                            stressful that I had to write down every single
                            stuff down, rewrite it and create a summary out of
                            it for presentation. I just came back and decided to
                            code ts.
                            <br />
                            Clerky was born out of pure laziness on my part but
                            I really hope you find it useful.
                        </p>
                    </div>
                </button>
            </div>
        </div>
    </aside>
</template>
