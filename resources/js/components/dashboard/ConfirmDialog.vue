<script setup lang="ts">
const props = defineProps<{
    open: boolean
    title: string
    message: string
}>()

const emit = defineEmits<{
    confirm: []
    cancel: []
}>()
</script>

<template>
    <Transition name="modal">
        <div
            v-if="open"
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                @click="emit('cancel')"
            />

            <div
                class="relative z-10 w-full max-w-sm rounded-2xl border border-white/8 bg-brand-surface p-6 shadow-2xl"
            >
                <h3 class="text-[15px] font-bold text-white">{{ title }}</h3>
                <p class="mt-2 text-[13px] leading-relaxed text-brand-gray">
                    {{ message }}
                </p>
                <div class="mt-5 flex items-center justify-end gap-3">
                    <button
                        @click="emit('cancel')"
                        class="rounded-xl border border-white/10 px-4 py-2 text-[13px] font-semibold text-brand-gray transition-colors hover:bg-white/5"
                    >
                        Cancel
                    </button>
                    <button
                        @click="emit('confirm')"
                        class="rounded-xl bg-red-500/20 px-4 py-2 text-[13px] font-bold text-red-400 transition-colors hover:bg-red-500/30"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.modal-enter-active {
    transition: opacity 0.25s ease-out;
}
.modal-enter-active > :first-child {
    transition: opacity 0.3s ease-out;
}
.modal-enter-active > :last-child {
    transition:
        transform 0.2s ease-out,
        opacity 0.2s ease-out;
}
.modal-leave-active {
    transition: opacity 0.15s ease-in;
}
.modal-leave-active > :first-child {
    transition: opacity 0.15s ease-in;
}
.modal-leave-active > :last-child {
    transition:
        transform 0.15s ease-in,
        opacity 0.15s ease-in;
}

.modal-enter-from {
    opacity: 0;
}
.modal-enter-from > :first-child {
    opacity: 0;
}
.modal-enter-from > :last-child {
    transform: scale(0.95) translateY(8px);
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}
.modal-leave-to > :first-child {
    opacity: 0;
}
.modal-leave-to > :last-child {
    transform: scale(0.95) translateY(8px);
    opacity: 0;
}
</style>
