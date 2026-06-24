<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppHeader from '../components/AppHeader.vue'
import FeaturesSection from '../components/FeaturesSection.vue'
import SpecialtiesSection from '../components/SpecialtiesSection.vue'
import IntelligenceSection from '../components/IntelligenceSection.vue'
import CtaSection from '../components/CtaSection.vue'
import AppFooter from '../components/AppFooter.vue'
import ScrollReveal from '../components/ScrollReveal.vue'

defineProps<{
    canLogin?: boolean
    canRegister?: boolean
    fullName?: string
}>()
</script>

<template>
    <Head title="Clerky - Smart Clinical Clerking" />

    <div
        class="relative min-h-screen overflow-x-hidden bg-brand-bg font-sans text-white selection:bg-brand-yellow selection:text-black"
    >
        <div class="pointer-events-none fixed inset-0 z-0">
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,rgba(244,253,59,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(244,253,59,0.03)_1px,transparent_1px)] bg-size-[4rem_4rem]"
            ></div>
            <div
                class="absolute inset-0 bg-linear-to-b from-transparent via-brand-bg/50 to-brand-bg/95"
            ></div>
        </div>

        <AppHeader
            :can-login="canLogin"
            :can-register="canRegister"
        />

        <ScrollReveal
            animation="left"
            :threshold="0.12"
        >
            <FeaturesSection />
        </ScrollReveal>
        <ScrollReveal
            animation="scale"
            :threshold="0.2"
            rootMargin="0px 0px -80px 0px"
        >
            <SpecialtiesSection />
        </ScrollReveal>
        <ScrollReveal
            animation="right"
            :threshold="0.15"
        >
            <IntelligenceSection />
        </ScrollReveal>
        <ScrollReveal
            animation="rotate"
            :threshold="0.25"
        >
            <CtaSection />
        </ScrollReveal>
        <AppFooter />
    </div>
</template>
