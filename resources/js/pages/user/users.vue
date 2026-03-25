<script setup>
import Main from '@/layouts/main.vue'
import { ref, inject } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select' // Import the Select component
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'

defineOptions({ layout: Main })

const user = inject('user')
const toast = useToast()

const props = defineProps({
    users:     { type: Object, default: () => ({}) },
    filters:   { type: Object, default: () => ({}) },
    provinces: { type: Array,  default: () => [] }, // Add provinces prop from Laravel
})

// ── Search & Pagination ──────────────────────────────────
const search = ref(props.filters.search ?? '')
let searchTimeout = null
const onSearch = (e) => {
    search.value = e.target.value
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        router.get('/users', { search: search.value }, {
            preserveState: true, preserveScroll: true, replace: true,
        })
    }, 400)
}

const goToPage = (url) => {
    if (!url) return
    router.get(url, { search: search.value }, { preserveState: true, preserveScroll: true })
}

// ── Create User Modal ──────────────────────────────────
const showCreateModal = ref(false)
const form = useForm({
    name: '',
    email: '',
    province_id: null, // Add this to your form state
})

const openCreateModal = () => {
    form.reset()
    form.clearErrors()
    showCreateModal.value = true
}

const submitCreateUser = () => {
    form.post('/users/create', { 
        onSuccess: () => {
            showCreateModal.value = false
            toast.add({
                severity: 'success',
                summary:  'User Created',
                detail:   'New user has been added successfully.',
                life:     3000,
            })
        },
    })
}

// ── Password Reset ─────────────────────────────────────
const resetPassword = (userID) => {
    if (!confirm('Are you sure you want to reset this user\'s password?')) return
    router.put(`/users/${userID}`, {}, {
        preserveState: true, preserveScroll: true,
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Password Reset', detail: 'User password has been reset successfully.', life: 2000 })
        },
    })
}

// ── Helpers ────────────────────────────────────────────
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
</script>

<template>
    <div class="h-full flex flex-col gap-5 p-6">
        <Toast />

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Users</h1>
                <p class="text-xs text-slate-400 mt-1">Manage system users and their access levels.</p>
            </div>
            <button @click="openCreateModal" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200">
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                New User
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative flex-1 max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input :value="search" @input="onSearch" type="text" placeholder="Search users..." class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all" />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ props.users.total ?? 0 }} users</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1 flex flex-col">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-100 border-b border-slate-100 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">User</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Province</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Access Level</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="(u, index) in props.users.data" :key="u.id" class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums w-10">
                            {{ String(index + 1).padStart(2, '0') }}
                        </td>
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
                        <td class="px-5 py-3.5 text-[11px] text-slate-600 font-medium">
                            {{ u.access_levels?.province?.name ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold ring-1" :class="accessLevelColor(u.access_levels?.access_level)">
                                {{ accessLevelLabel(u.access_levels?.access_level) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <button @click="resetPassword(u.id)" class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                <Icon icon="hugeicons:reset-password" class="text-sm" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Dialog v-model:visible="showCreateModal" header="Create New User" :modal="true" :draggable="false" class="w-full max-w-md mx-4">
            <form @submit.prevent="submitCreateUser" class="flex flex-col gap-4 mt-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Full Name</label>
                    <InputText v-model="form.name" placeholder="John Doe" :class="{ 'p-invalid': form.errors.name }" class="p-inputtext-sm w-full" />
                    <small v-if="form.errors.name" class="text-rose-500 text-[10px] font-medium">{{ form.errors.name }}</small>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Email Address</label>
                    <InputText v-model="form.email" type="email" placeholder="john@example.com" :class="{ 'p-invalid': form.errors.email }" class="p-inputtext-sm w-full" />
                    <small v-if="form.errors.email" class="text-rose-500 text-[10px] font-medium">{{ form.errors.email }}</small>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase">Province</label>
                    <Select 
                        v-model="form.province_id" 
                        :options="props.provinces" 
                        optionLabel="name" 
                        optionValue="id" 
                        placeholder="Select a Province" 
                        class="w-full p-inputtext-sm"
                        :class="{ 'p-invalid': form.errors.province_id }"
                    />
                    <small v-if="form.errors.province_id" class="text-rose-500 text-[10px] font-medium">{{ form.errors.province_id }}</small>
                </div>

                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-100">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" :disabled="form.processing" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg disabled:opacity-50 transition-all">
                        <Icon v-if="form.processing" icon="hugeicons:loading-03" class="animate-spin" />
                        Save User
                    </button>
                </div>
            </form>
        </Dialog>
    </div>
</template>