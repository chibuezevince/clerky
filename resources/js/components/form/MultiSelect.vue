<script setup lang="ts">
import type { SelectOption } from '@/types/forms'
import { ref, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import MessageBadge from './MessageBadge.vue'

const props = withDefaults(
    defineProps<{
        modelValue: string | string[]
        options: SelectOption[]
        placeholder?: string
        error?: string
        label?: string
    }>(),
    {
        modelValue: () => [],
    },
)

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const query = ref('')
const triggerRef = ref<HTMLElement | null>(null)
const dropdownRef = ref<HTMLElement | null>(null)
const searchInputRef = ref<HTMLInputElement | null>(null)
const dropdownStyle = ref({})
const mounted = ref(false)

const selectedLabels = computed(() =>
    props.options
        .filter((o) => props.modelValue.includes(o.value as never))
        .map((o) => o.label),
)

const filtered = computed(() =>
    props.options.filter((opt) =>
        opt.label.toLowerCase().includes(query.value.toLowerCase()),
    ),
)

function isSelected(value: string | number) {
    return props.modelValue.includes(value as never)
}

function toggle(value: string | number) {
    const current = [...props.modelValue]
    const idx = current.indexOf(value as never)
    if (idx === -1) {
        current.push(value as never)
    } else {
        current.splice(idx, 1)
    }
    emit('update:modelValue', current)
}

function updateDropdownPosition() {
    if (!triggerRef.value) return
    const rect = triggerRef.value.getBoundingClientRect()
    dropdownStyle.value = {
        position: 'fixed',
        top: `${rect.bottom + 8}px`,
        left: `${rect.left}px`,
        width: `${rect.width}px`,
        zIndex: 9999,
    }
}

function toggleDropdown() {
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

function closeDropdown() {
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
                    : 'border-brand-border bg-brand-surface hover:border-brand-gray/80 hover:bg-brand-surface/80',
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
                class="relative z-10 truncate pr-4 transition-colors duration-200"
                :class="
                    selectedLabels.length
                        ? 'font-semibold text-white'
                        : 'font-normal text-brand-gray/60'
                "
            >
                {{
                    selectedLabels.length
                        ? selectedLabels.join(', ')
                        : placeholder || 'Select options'
                }}
            </span>

            <div class="relative z-10 flex items-center gap-2">
                <span
                    v-if="selectedLabels.length"
                    class="flex h-5 items-center justify-center rounded-full bg-brand-yellow px-2 text-[10px] font-bold text-black"
                >
                    {{ selectedLabels.length }}
                </span>
                <div
                    class="flex h-6 w-6 items-center justify-center rounded-md bg-white/5 transition-all duration-300 group-hover:bg-white/10"
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
        </div>

        <Teleport
            v-if="mounted"
            to="body"
        >
            <Transition
                enter-active-class="transition duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
                enter-from-class="opacity-0 -translate-y-4 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 -translate-y-2 scale-95"
            >
                <div
                    v-if="open"
                    ref="dropdownRef"
                    :style="dropdownStyle"
                    class="origin-top overflow-hidden rounded-xl border border-brand-border bg-[#181818]/95 p-4 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.6)] backdrop-blur-xl"
                >
                    <div class="relative mb-4">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"
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
                            class="h-12 w-full rounded-lg border border-brand-border/50 bg-[#111]/50 pr-5 pl-11 text-sm text-white transition-all duration-200 outline-none focus:border-brand-yellow focus:bg-brand-bg focus:ring-1 focus:ring-brand-yellow/50"
                        />
                    </div>

                    <div
                        class="custom-scroll flex max-h-80 flex-col gap-2 overflow-y-auto pr-1"
                    >
                        <div
                            v-for="(option, key) in filtered"
                            :key="key"
                            @click="toggle(option.value)"
                            class="group relative flex cursor-pointer items-center justify-between overflow-hidden rounded-xl px-5 py-3.5 text-sm font-medium transition-all duration-200"
                            :class="[
                                isSelected(option.value)
                                    ? 'bg-brand-yellow/15 text-brand-yellow'
                                    : 'text-brand-gray-light hover:bg-white/5 hover:pl-6 hover:text-white',
                            ]"
                        >
                            <span class="relative z-10 truncate">{{
                                option.label
                            }}</span>

                            <svg
                                v-if="isSelected(option.value)"
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
                            class="flex h-20 items-center justify-center px-5 py-3.5 text-sm text-brand-gray"
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
