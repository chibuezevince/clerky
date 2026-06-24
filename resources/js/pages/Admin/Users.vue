<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { glass } from '@/data/dashboard'
import { Search, Trash2, ArrowLeftCircle } from '@lucide/vue'
import { index as adminIndex, users as adminUsers } from '@/routes/admin'
import { computed, unref, ref } from 'vue'
import { toast } from 'vue-sonner'
import {
    toggleContributor as toggleContributorRoute,
    destroy as destroyRoute,
} from '@/routes/admin/users'

defineOptions({ layout: AdminLayout })

const page = usePage()
const usersProp = computed(
    () =>
        page.props.users as {
            data: {
                id: number
                name: string
                email: string
                username: string
                email_verified_at: string | null
                can_contribute: boolean
                created_at: string
            }[]
            meta: { current_page: number; last_page: number; total: number }
        },
)

const search = ref('')

const performSearch = () => {
    router.get(
        adminIndex(),
        { search: unref(search) },
        { preserveState: true, replace: true },
    )
}

const goToPage = (page: number) => {
    router.get(
        adminUsers({ query: { page } }),
        { search: unref(search) },
        { preserveState: true },
    )
}
</script>

<template>
    <Head title="Admin — Users" />

    <div class="flex flex-col gap-6 py-8">
        <div class="flex items-center justify-between gap-4">
            <h1
                class="text-xl font-extrabold tracking-tight text-white md:text-2xl"
            >
                Users
            </h1>
            <div
                class="flex items-center gap-2 rounded-xl border border-white/6 bg-white/3 px-4 py-2.5"
            >
                <Search
                    :size="16"
                    class="text-brand-gray"
                />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search users..."
                    class="min-w-0 bg-transparent text-sm font-semibold text-white outline-none placeholder:text-white/20"
                    @keydown.enter="performSearch"
                />
            </div>
        </div>

        <div :class="[glass, 'overflow-hidden']">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-white/6 text-[11px] font-bold tracking-wider text-brand-gray uppercase"
                    >
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Verified</th>
                        <th class="px-5 py-3">Contributor</th>
                        <th class="px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="user in usersProp.data"
                        :key="user.id"
                        class="border-b border-white/3 transition-colors hover:bg-white/2"
                    >
                        <td class="px-5 py-4 font-semibold text-white">
                            {{ user.name }}
                        </td>
                        <td class="px-5 py-4 text-brand-gray">
                            {{ user.email }}
                        </td>
                        <td class="px-5 py-4">
                            <span
                                v-if="user.email_verified_at"
                                class="rounded-full bg-emerald-400/10 px-2 py-0.5 text-[10px] font-bold text-emerald-400"
                                >Verified</span
                            >
                            <span
                                v-else
                                class="rounded-full bg-rose-400/10 px-2 py-0.5 text-[10px] font-bold text-rose-400"
                                >Unverified</span
                            >
                        </td>
                        <td class="px-5 py-4">
                            <button
                                type="button"
                                role="switch"
                                :aria-checked="user.can_contribute"
                                @click="
                                    router.patch(
                                        toggleContributorRoute({
                                            user: user.id,
                                        }),
                                        {},
                                        {
                                            preserveScroll: true,
                                            onSuccess: () =>
                                                toast.success('Updated'),
                                        },
                                    )
                                "
                                class="relative inline-flex h-5 w-9 cursor-pointer items-center rounded-full transition-colors"
                                :class="
                                    user.can_contribute
                                        ? 'bg-brand-yellow'
                                        : 'bg-white/10'
                                "
                            >
                                <span
                                    class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform duration-200"
                                    :class="
                                        user.can_contribute
                                            ? 'translate-x-4.5'
                                            : 'translate-x-0.75'
                                    "
                                />
                            </button>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <button
                                    @click="
                                        router.delete(
                                            destroyRoute({ user: user.id }),
                                            {
                                                preserveScroll: true,
                                                onSuccess: () =>
                                                    toast.success(
                                                        'User deleted',
                                                    ),
                                            },
                                        )
                                    "
                                    class="flex cursor-pointer items-center gap-1 rounded-lg px-2.5 py-1.5 text-[11px] font-bold text-rose-400/60 transition-colors hover:bg-rose-400/10 hover:text-rose-400"
                                >
                                    <Trash2 :size="12" />
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="!usersProp.data.length"
                class="px-5 py-8 text-center text-sm text-brand-gray"
            >
                No users found.
            </div>
        </div>

        <div
            v-if="usersProp.meta?.last_page > 1"
            class="flex items-center justify-center gap-2"
        >
            <button
                v-for="page in usersProp.meta.last_page"
                :key="page"
                @click="goToPage(page)"
                class="rounded-lg px-3 py-1.5 text-xs font-bold transition-colors"
                :class="
                    usersProp.meta.current_page === page
                        ? 'bg-brand-yellow text-brand-bg'
                        : 'bg-white/5 text-brand-gray hover:text-white'
                "
            >
                {{ page }}
            </button>
        </div>
    </div>
</template>
