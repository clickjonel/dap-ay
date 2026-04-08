<script setup>
import Main from '@/layouts/main.vue'
import { ref, inject } from 'vue'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import Dialog from 'primevue/dialog'

defineOptions({ layout: Main })

const toast = useToast()
const search = ref('')
const user = inject('user')

const props = defineProps({
    reports: { type: Object, default: () => ({ data: [] }) },
    filters: { type: Object, default: () => ({}) },
})

// ── Status Styling Mapping ────────────────────────────
const getStatusStyles = (status) => {
    const styles = {
        approved: 'bg-emerald-50 text-emerald-600 border-emerald-100',
        rejected: 'bg-rose-50 text-rose-600 border-rose-100',
        pending: 'bg-amber-50 text-amber-600 border-amber-100',
    }
    return styles[status?.toLowerCase()] || 'bg-slate-50 text-slate-500 border-slate-100'
}

// ── Search Logic ───────────────────────────────────────
const onSearch = debounce((e) => {
    router.get('/reports', { search: e.target.value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}, 400)

const canEditReport = (reportStatus) => {
    if(reportStatus === 'approved') {
        return false
    }
    return true
}

function editReport(id) {
    router.visit(`reports/${id}/edit`)
}

// ── Status Dialog Logic ────────────────────────────────
const statusDialog = ref(false)
const selectedReport = ref(null)
const processingStatus = ref(false)

const statusOptions = [
    { label: 'Approved', value: 'approved', color: 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-emerald-100', icon: 'hugeicons:checkmark-circle-02' },
    { label: 'Rejected', value: 'rejected', color: 'bg-rose-50 text-rose-600 hover:bg-rose-100 border-rose-100', icon: 'hugeicons:cancel-circle-02' },
]

function openStatusDialog(report) {
    selectedReport.value = report
    statusDialog.value = true
}

function submitStatus(statusValue) {
    processingStatus.value = true
    router.delete(`/reports/${selectedReport.value.id}`, {
        data: { status: statusValue },
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ 
                severity: 'success', 
                summary: 'Report Updated', 
                detail: `Status set to ${statusValue}`, 
                life: 3000 
            })
            statusDialog.value = false
        },
        onFinish: () => {
            processingStatus.value = false
        }
    })
}
</script>

<template>
    <div class="h-full flex flex-col gap-5 p-6">
        <Toast />

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Reports</h1>
                <p class="text-xs text-slate-400 mt-1">Manage reports and their data.</p>
            </div>
            <button
                @click="router.visit('reports/create')"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                Create Report
            </button>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative flex-1 max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    @input="onSearch"
                    type="text"
                    placeholder="Search report..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ props.reports.total ?? 0 }} reports</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden flex-1 flex flex-col shadow-sm">
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 w-10">#</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Barangay</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center">Clients</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Submitted By</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Date</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="props.reports.data.length === 0">
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <Icon icon="hugeicons:file-02" class="text-3xl text-slate-300" />
                                    <p class="text-sm font-medium text-slate-400">No reports found</p>
                                </div>
                            </td>
                        </tr>

                        <tr
                            v-for="(report, index) in props.reports.data"
                            :key="report.id"
                            class="group hover:bg-slate-50/60 transition-colors cursor-pointer"
                            @click="editReport(report.id)"
                        >
                            <td class="px-5 py-3.5 text-xs text-slate-300 font-mono">
                                {{ props.reports.from + index }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                        <Icon icon="hugeicons:location-04" class="text-indigo-400 text-sm" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-700 truncate leading-tight">
                                            {{ report.barangay?.name ?? '—' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                                            {{ report.barangay?.municipality?.name }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-xs font-semibold text-slate-700">{{ report.total_clients ?? 0 }}</span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="flex -space-x-2 mr-1">
                                        <div
                                            v-for="u in report.users?.slice(0, 3)"
                                            :key="u.id"
                                            class="w-6 h-6 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[9px] font-bold text-indigo-600 uppercase shrink-0"
                                            :title="u.name"
                                        >
                                            {{ u.name?.charAt(0) }}
                                        </div>
                                        <div
                                            v-if="report.users?.length > 3"
                                            class="w-6 h-6 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[8px] font-bold text-slate-500 shrink-0"
                                        >
                                            +{{ report.users.length - 3 }}
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-slate-600 font-medium leading-tight truncate max-w-[100px]">
                                        {{ report.users?.map(u => u.name).join(', ') }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 text-xs text-slate-400">
                                {{ report.date ? new Date(report.date).toLocaleDateString() : '—' }}
                            </td>

                            <td class="px-5 py-3.5 flex flex-col justify-center items-start gap-2">
                                <span 
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                    :class="getStatusStyles(report.status)"
                                >
                                    {{ report.status ?? 'Pending Approval' }}
                                </span>
                                <span 
                                    v-if="report.actioned_by"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                    :class="getStatusStyles(report.status)"
                                >
                                    {{ report.actioned_by?.name ?? '' }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 text-right" @click.stop>
                                <div class="flex items-center justify-end gap-1">
                                    <button v-if="canEditReport(report.status)" @click="editReport(report.id)" class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <Icon icon="hugeicons:pencil-edit-02" class="text-sm" />
                                    </button>
                                    <button v-if="!report.status && user.access_levels.access_level === 4" @click="openStatusDialog(report)" class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <Icon icon="hugeicons:tick-01" class="text-sm" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="props.reports.last_page > 1" class="flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                <span class="text-[11px] text-slate-400 font-medium tracking-tight">
                    Showing {{ props.reports.from }}–{{ props.reports.to }} of {{ props.reports.total }}
                </span>
                <div class="flex gap-1">
                    <button
                        v-for="link in props.reports.links"
                        :key="link.label"
                        :disabled="!link.url || link.active"
                        @click="link.url && router.visit(link.url, { preserveState: true, preserveScroll: true })"
                        class="px-2.5 py-1.5 text-[10px] font-bold rounded-lg border transition-all disabled:opacity-40"
                        :class="link.active 
                            ? 'bg-indigo-600 text-white border-indigo-600' 
                            : 'bg-white border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600'"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>

        <Dialog v-model:visible="statusDialog" modal :draggable="false" header="Update Report Status" class="w-full max-w-sm mx-4">
            <div class="flex flex-col gap-5 py-2">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center">
                            <Icon icon="hugeicons:location-04" class="text-indigo-500 text-lg" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Selected Barangay</p>
                            <p class="text-sm font-bold text-slate-800 truncate">{{ selectedReport?.barangay?.name }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2.5">
                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-widest ml-1">Choose Status</p>
                    
                    <button
                        v-for="status in statusOptions"
                        :key="status.value"
                        @click="submitStatus(status.value)"
                        :disabled="processingStatus"
                        class="flex items-center justify-between px-4 py-3.5 rounded-xl border-2 transition-all active:scale-[0.98] group disabled:opacity-50"
                        :class="status.color"
                    >
                        <div class="flex items-center gap-3">
                            <Icon :icon="status.icon" class="text-xl" />
                            <span class="text-xs font-bold uppercase tracking-wide">{{ status.label }}</span>
                        </div>
                        <Icon icon="hugeicons:arrow-right-01" class="text-sm opacity-30 group-hover:opacity-100 group-hover:translate-x-1 transition-all" />
                    </button>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-center w-full pt-2">
                    <button @click="statusDialog = false" class="text-[10px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors">
                        Cancel Action
                    </button>
                </div>
            </template>
        </Dialog>
    </div>
</template>