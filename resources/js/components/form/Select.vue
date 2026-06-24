<script setup lang="ts">
import type { SelectOption } from '@/types/forms'
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import MessageBadge from './MessageBadge.vue'

const props = defineProps<{
    modelValue: string | number | null | undefined
    options: SelectOption[]
    placeholder?: string
    error?: string
    label?: string | null
}>()

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const query = ref('')
const triggerRef = ref<HTMLElement | null>(null)
const dropdownRef = ref<HTMLElement | null>(null)
const searchInputRef = ref<HTMLInputElement | null>(null)

const dropdownStyle = ref({})
const mounted = ref(false)
const dropsUp = ref(false)

const selectedLabel = computed(() => {
    return props.options.find((option) => option.value === props.modelValue)
        ?.label
})

const filtered = computed(() =>
    props.options.filter((opt) =>
        opt.label.toLowerCase().includes(query.value.toLowerCase()),
    ),
)

const pick = (optionValue: string | number) => {
    emit('update:modelValue', optionValue)
    closeDropdown()
}

const updateDropdownPosition = () => {
    if (!triggerRef.value) return
    const rect = triggerRef.value.getBoundingClientRect()
    const spaceBelow = window.innerHeight - rect.bottom
    const spaceAbove = rect.top
    const dropdownHeight = 320

    dropsUp.value = spaceBelow < dropdownHeight && spaceAbove > spaceBelow

    dropdownStyle.value = {
        position: 'fixed',
        left: `${rect.left}px`,
        width: `${rect.width}px`,
        zIndex: 9999,
        ...(dropsUp.value
            ? { bottom: `${window.innerHeight - rect.top + 8}px` }
            : { top: `${rect.bottom + 8}px` }),
    }
}

const toggleDropdown = () => {
    if (open.value) {
        closeDropdown()
    } else {
        updateDropdownPosition()
        open.value = true
        nextTick(() => {
            searchInputRef.value?.focus()
        })
    }
}

const closeDropdown = () => {
    open.value = false
    query.value = ''
}

const handleGlobalClick = (e: MouseEvent) => {
    if (!open.value) return
    const target = e.target as Node
    if (triggerRef.value?.contains(target)) return
    if (dropdownRef.value?.contains(target)) return
    closeDropdown()
}

const onScrollOrResize = () => {
    if (open.value) updateDropdownPosition()
}

onMounted(() => {
    mounted.value = true
    document.addEventListener('click', handleGlobalClick)
    window.addEventListener('scroll', onScrollOrResize, true)
    window.addEventListener('resize', onScrollOrResize)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleGlobalClick)
    window.removeEventListener('scroll', onScrollOrResize, true)
    window.removeEventListener('resize', onScrollOrResize)
})
</script>

<template>
    <div class="relative flex w-full flex-col space-y-1.5">
        <label
            v-if="label"
            class="text-sm font-medium transition-colors duration-300"
            :class="[open ? 'text-brand-yellow' : 'text-brand-gray-light']"
        >
            {{ label }}
        </label>

        <div
            ref="triggerRef"
            @click="toggleDropdown"
            class="group relative flex h-12 cursor-pointer items-center justify-between overflow-hidden rounded-xl border px-4 text-sm text-white transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
            :class="[
                open
                    ? 'scale-[1.02] border-brand-yellow bg-brand-bg/90 shadow-[0_0_15px_rgba(234,179,8,0.15)] ring-1 ring-brand-yellow/50'
                    : 'border-brand-border hover:border-brand-gray/80 hover:bg-brand-surface/80',
                props.error
                    ? 'border-red-500 shadow-[0_0_15px_rgba(239,68,68,0.15)] ring-red-500/50'
                    : '',
            ]"
        >
            <div
                class="absolute inset-0 bg-brand-yellow/10 transition-opacity duration-300"
                :class="[open ? 'opacity-100' : 'opacity-0']"
            ></div>

            <span
                class="relative z-10 truncate transition-colors duration-200"
                :class="
                    selectedLabel
                        ? 'font-semibold text-white'
                        : 'font-normal text-brand-gray/60'
                "
            >
                {{ selectedLabel || placeholder || 'Select an option' }}
            </span>

            <div
                class="relative z-10 flex h-6 w-6 items-center justify-center rounded-md bg-white/5 transition-all duration-300 group-hover:bg-white/10"
                :class="{
                    'rotate-180 bg-brand-yellow/20 text-brand-yellow group-hover:bg-brand-yellow/30':
                        open,
                }"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transition-transform duration-300"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>
        </div>

        <Teleport
            v-if="mounted"
            to="body"
        >
            <Transition
                :enter-active-class="'transition duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]'"
                :enter-from-class="
                    dropsUp
                        ? 'opacity-0 translate-y-4 scale-95'
                        : 'opacity-0 -translate-y-4 scale-95'
                "
                enter-to-class="opacity-100 translate-y-0 scale-100"
                :leave-active-class="'transition duration-200 ease-in'"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                :leave-to-class="
                    dropsUp
                        ? 'opacity-0 translate-y-2 scale-95'
                        : 'opacity-0 -translate-y-2 scale-95'
                "
            >
                <div
                    v-if="open"
                    ref="dropdownRef"
                    :style="dropdownStyle"
                    class="overflow-hidden rounded-xl border border-brand-border bg-[#181818]/95 p-3 shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)] backdrop-blur-xl"
                    :class="dropsUp ? 'origin-bottom' : 'origin-top'"
                >
                    <div class="relative mb-3">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-brand-gray"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <input
                            ref="searchInputRef"
                            v-model="query"
                            placeholder="Search..."
                            class="h-11 w-full rounded-lg border border-brand-border/50 bg-[#111]/50 pr-4 pl-10 text-sm text-white transition-all duration-200 outline-none focus:border-brand-yellow focus:bg-brand-bg focus:ring-1 focus:ring-brand-yellow/50"
                        />
                    </div>

                    <div
                        class="custom-scroll flex max-h-60 flex-col gap-1.5 overflow-y-auto pr-1"
                    >
                        <div
                            v-for="(option, key) in filtered"
                            :key="key"
                            @click="pick(option.value)"
                            class="group relative flex cursor-pointer items-center justify-between overflow-hidden rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200"
                            :class="[
                                modelValue === option.value
                                    ? 'bg-brand-yellow/15 text-brand-yellow'
                                    : 'text-brand-gray-light hover:bg-white/5 hover:pl-5 hover:text-white',
                            ]"
                        >
                            <span class="relative z-10 truncate">{{
                                option.label
                            }}</span>

                            <svg
                                v-if="modelValue === option.value"
                                xmlns="http://www.w3.org/2000/svg"
                                class="relative z-10 h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>

                        <div
                            v-if="filtered.length === 0"
                            class="flex h-20 items-center justify-center px-4 py-3 text-sm text-brand-gray"
                        >
                            No results found
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
        <MessageBadge
            v-if="props.error"
            variant="error"
            :message="props.error"
        />
    </div>
</template>

<style scoped>
.custom-scroll::-webkit-scrollbar {
    width: 6px;
}
.custom-scroll::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scroll::-webkit-scrollbar-thumb {
    background: #333;
    border-radius: 10px;
}
.custom-scroll::-webkit-scrollbar-thumb:hover {
    background: #444;
}
</style>
