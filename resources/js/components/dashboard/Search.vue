<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { useDebounceFn, useRefHistory } from '@vueuse/core'
import { LoaderCircle } from '@lucide/vue'
import SearchDropdown from './SearchDropdown.vue'
import type { Clerking } from '@/types/dashboard'

const emit = defineEmits<{
    active: [value: boolean]
}>()

const searchInput = ref<HTMLInputElement>()
const isFocused = ref(false)
const searchQuery = ref('')
const clerkingResults = ref<Clerking[]>([])
const { undo: undoClerkingResults } = useRefHistory(clerkingResults)

const isSearching = ref(false)

const debouncedSearch = useDebounceFn(async (query: string) => {
    isSearching.value = true

    try {
        const params = new URLSearchParams({ q: query })
        const response = await fetch(`/dashboard/search/clerkings?${params}`, {
            headers: { 'X-Inertia': 'true' },
        })
        const data = await response.json()
        clerkingResults.value = data
    } finally {
        isSearching.value = false
    }
}, 250)

const onInput = (e: Event) => {
    const value = (e.target as HTMLInputElement).value
    if (!value.trim()) {
        clerkingResults.value = []
        return
    }
    searchQuery.value = value
    debouncedSearch(value)
}

const onFocus = () => {
    isFocused.value = true
    undoClerkingResults()
}
const onBlur = () =>
    setTimeout(() => {
        isFocused.value = false
        clerkingResults.value = []
    }, 200)

watch([isFocused, searchQuery], () => {
    emit('active', isFocused.value && searchQuery.value.length > 0)
})

const viewClerking = (sessionId: string) => {
    isFocused.value = false
    searchQuery.value = ''
    clerkingResults.value = []
    router.visit(`/dashboard/clerking/${sessionId}`)
}

const focusSearch = (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault()
        searchInput.value?.focus()
    }
}

onMounted(() =>
    window.addEventListener('keydown', (event) => focusSearch(event)),
)
onUnmounted(() =>
    window.removeEventListener('keydown', (event) => focusSearch(event)),
)
</script>
<template>
    <div class="relative z-50">
        <div
            v-if="isFocused && searchQuery"
            class="fixed inset-0 bg-black/40"
        />
        <div
            class="group relative flex w-full items-center gap-3 rounded-xl border border-white/5 bg-white/3 px-4 py-2 transition-all hover:border-white/10 hover:bg-white/5"
        >
            <LoaderCircle
                v-if="isSearching"
                class="h-4 w-4 animate-spin text-brand-gray"
            />
            <svg
                v-else
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                class="h-4 w-4 text-brand-gray transition-colors group-hover:text-white"
            >
                <circle
                    cx="11"
                    cy="11"
                    r="7"
                />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <input
                ref="searchInput"
                v-model="searchQuery"
                @input="onInput"
                @focus="onFocus"
                @blur="onBlur"
                class="min-w-0 flex-1 bg-transparent text-[13px] text-neutral-50 outline-none placeholder:text-brand-gray"
                placeholder="Search cases, patients, or prompts…"
            />
            <div
                class="flex items-center gap-1 rounded border border-white/10 bg-white/5 px-1.5 py-0.5 text-[10px] font-medium text-brand-gray"
            >
                <span class="text-[12px] leading-none">⌘</span>
                <span>K</span>
            </div>
        </div>

        <SearchDropdown
            :results="clerkingResults"
            @select="viewClerking"
        />
    </div>
</template>
