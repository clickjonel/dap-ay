<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, inject } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import Dialog from 'primevue/dialog'
    import InputText from 'primevue/inputtext'
    import Password from 'primevue/password'
    import Select from 'primevue/select'
    import { useToast } from 'primevue/usetoast'

    defineOptions({ layout: Main })

    const user = inject('user')
    const toast = useToast()

    const props = defineProps({
        users:   { type: Object, default: () => ({}) },
        filters: { type: Object, default: () => ({}) },
    })

    // ── Search ─────────────────────────────────────────────
    const search = ref(props.filters.search ?? '')

    let searchTimeout = null
    const onSearch = (e) => {
        search.value = e.target.value
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(() => {
            router.get('/users', { search: search.value }, {
                preserveState:  true,
                preserveScroll: true,
                replace:        true,
            })
        }, 400)
    }

    // ── Pagination ─────────────────────────────────────────
    const goToPage = (url) => {
        if (!url) return
        router.get(url, { search: search.value }, {
            preserveState:  true,
            preserveScroll: true,
        })
    }

    // ── Access level labels ────────────────────────────────
    const accessLevels = [
        { label: 'Admin',  value: 1 },
        { label: 'HRH',    value: 2 },
        { label: 'PDOHO',  value: 3 },
    ]

    const accessLevelLabel = (level) => accessLevels.find(a => a.value === level)?.label ?? '—'
    const accessLevelColor = (level) => ({
        1: 'bg-rose-50 text-rose-600 ring-rose-100',
        2: 'bg-indigo-50 text-indigo-600 ring-indigo-100',
        3: 'bg-emerald-50 text-emerald-600 ring-emerald-100',
    }[level] ?? 'bg-slate-100 text-slate-400')

    const resetPassword = (userID) => {
        // router.put(url, data, options)
        router.put(`/users/${userID}`, {}, { // <--- Added {} here
            preserveState:  true,
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary:  'Password Reset',
                    detail:   'User password has been reset successfully.',
                    life:     2000,
                })
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary:  'Error',
                    detail:   'There was an error resetting the password.',
                    life:     3000,
                })
            },
        })
    }

</script>

<template>
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Users</h1>
            <p class="text-xs text-slate-400 mt-1">Manage system users and their access levels.</p>
        </div>
        <button
            type="button"
            @click="openUserModal('create')"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            New User
        </button>
    </div>

    <!-- ── Filters ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                :value="search"
                @input="onSearch"
                type="text"
                placeholder="Search users..."
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>
        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ props.users.total ?? 0 }} users</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1 flex flex-col">
        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">User</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Province</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Access Level</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="!props.users.data?.length">
                    <td colspan="4" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                            <p class="text-sm font-medium text-slate-400">No users found</p>
                            <p class="text-xs text-slate-300">Try adjusting your search or create a new user.</p>
                        </div>
                    </td>
                </tr>

                <tr
                    v-for="(u, index) in props.users.data"
                    :key="u.id"
                    class="hover:bg-slate-50/70 transition-colors"
                >
                    <!-- # -->
                    <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums w-10">
                        {{ String(index + 1).padStart(2, '0') }}
                    </td>

                    <!-- User -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-[11px] font-bold shrink-0">
                                {{ u.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) }}
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-semibold text-slate-700 truncate">{{ u.name }}</span>
                                <span class="text-[10px] text-slate-400 truncate">{{ u.email }}</span>
                            </div>
                        </div>
                    </td>

                    <!-- Access level -->
                    <td class="px-5 py-3.5">
                        <span 
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold ring-1 font-light">
                            {{ u.access_levels.province.name }}
                        </span>
                    </td>

                    <!-- Access level -->
                    <td class="px-5 py-3.5">
                        <span 
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold ring-1"
                            :class="accessLevelColor(u.access_levels.access_level)"
                        >
                            {{ accessLevelLabel(u.access_levels.access_level) }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-3.5" @click.stop>
                        <div class="flex items-center justify-end gap-1">
                            <button
                                type="button"
                                class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                title="Reset Password"
                                @click="resetPassword(u.id)"
                            >
                                <Icon icon="hugeicons:reset-password" class="text-sm" />
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- ── Pagination ─────────────────────────────── -->
        <div
            v-if="props.users.last_page > 1"
            class="flex items-center justify-between px-5 py-3 border-t border-slate-100 mt-auto"
        >
            <span class="text-xs text-slate-400">
                Showing {{ props.users.from }}–{{ props.users.to }} of {{ props.users.total }}
            </span>
            <div class="flex gap-1">
                <button
                    v-for="link in props.users.links"
                    :key="link.label"
                    :disabled="!link.url || link.active"
                    @click="goToPage(link.url)"
                    class="px-3 py-1.5 text-[11px] font-medium rounded-lg border transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                    :class="link.active
                        ? 'bg-indigo-600 text-white border-indigo-600'
                        : 'border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50'"
                    v-html="link.label"
                />
            </div>
        </div>
    </div>


</div>
</template>