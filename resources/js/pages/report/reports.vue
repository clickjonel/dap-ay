<script setup>
import Main from '@/layouts/main.vue'
import { ref } from 'vue'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import Dialog from 'primevue/dialog'
import Select from 'primevue/select'

const toast = useToast()
const search = ref('')
defineOptions({ layout: Main })

const props = defineProps({
    reports: { type: Object, default: () => ({ data: [] }) },
    filters: { type: Object, default: () => ({}) },
})

const onSearch = debounce((e) => {
    router.get('/reports', { search: e.target.value }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}, 400)

function editReport(id) {
    router.visit(`reports/${id}/edit`)
}

function deleteReport(id) {
    if (!confirm('Delete this report?')) return
    router.delete(`reports/${id}`, {
        onSuccess: () => toast.add({ severity: 'success', summary: 'Deleted', life: 3000 }),
    })
}

// ── Status dialog ──────────────────────────────────────
const statusDialog = ref(false)
const selectedReport = ref(null)
const selectedStatus = ref(null)
const statusOptions = [
    // add your statuses here, e.g.:
    // { label: 'Pending',  value: 'pending'  },
    // { label: 'Approved', value: 'approved' },
    // { label: 'Rejected', value: 'rejected' },
]

// function openStatusDialog(report) {
//     selectedReport.value = report
//     selectedStatus.value = report.status ?? null
//     statusDialog.value = true
// }

// function submitStatus() {
//     if (!selectedStatus.value) return
//     router.patch(`reports/${selectedReport.value.id}/status`, { status: selectedStatus.value }, {
//         preserveScroll: true,
//         onSuccess: () => {
//             toast.add({ severity: 'success', summary: 'Status updated', life: 3000 })
//             statusDialog.value = false
//         },
//         onError: () => {
//             toast.add({ severity: 'error', summary: 'Failed to update status', life: 3000 })
//         },
//     })
// }
</script>

<template>
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Reports</h1>
            <p class="text-xs text-slate-400 mt-1">Manage reports and their data.</p>
        </div>
        <button
            type="button"
            @click="router.visit('reports/create')"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            Create Report
        </button>
    </div>

    <!-- ── Filters ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                v-model="search"
                type="text"
                placeholder="Search report..."
                @input="onSearch"
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>
        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ props.reports.total }} reports</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 w-10">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Barangay</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Clients</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Returning Clients</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Submitted By</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Date</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="props.reports.data.length === 0">
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <Icon icon="hugeicons:file-02" class="text-3xl text-slate-300" />
                            <p class="text-sm font-medium text-slate-400">No reports found</p>
                            <p class="text-xs text-slate-300">Try adjusting your search or create a new report.</p>
                        </div>
                    </td>
                </tr>

                <!-- Data rows -->
                <tr
                    v-for="(report, index) in props.reports.data"
                    :key="report.id"
                    class="group hover:bg-slate-50/60 transition-colors cursor-pointer"
                    @click="editReport(report.id)"
                >
                    <!-- # -->
                    <td class="px-5 py-3.5 text-xs text-slate-300 font-mono">
                        {{ props.reports.from + index }}
                    </td>

                    <!-- Barangay -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <Icon icon="hugeicons:location-04" class="text-indigo-400 text-sm" />
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors leading-tight">
                                    {{ report.barangay?.name ?? '—' }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ [report.barangay?.municipality?.name, report.barangay?.province?.name].filter(Boolean).join(', ') }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <!-- Total Clients -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold text-slate-700">{{ report.total_clients ?? '—' }}</span>
                            <span v-if="report.total_clients" class="text-[10px] text-slate-400">clients</span>
                        </div>
                    </td>

                    <!-- Returning Clients -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold text-slate-700">{{ report.total_returning_clients ?? '—' }}</span>
                            <span v-if="report.total_returning_clients" class="text-[10px] text-slate-400">returning clients</span>
                        </div>
                    </td>

                    <!-- Users -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <div
                                    v-for="user in report.users?.slice(0, 3)"
                                    :key="user.id"
                                    class="w-6 h-6 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[9px] font-bold text-indigo-600 uppercase shrink-0"
                                    :title="user.name"
                                >
                                    {{ user.name?.charAt(0) }}
                                </div>
                                <div
                                    v-if="report.users?.length > 3"
                                    class="w-6 h-6 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[9px] font-bold text-slate-500 shrink-0"
                                >
                                    +{{ report.users.length - 3 }}
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400">
                                {{ report.users?.length === 1 ? '1 user' : `${report.users?.length ?? 0} users` }}
                            </span>
                        </div>
                    </td>

                    <!-- Date -->
                    <td class="px-5 py-3.5">
                        <span class="text-xs text-slate-400">
                            {{ report.date
                                ? new Date(report.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                                : '—' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-3.5 text-right" @click.stop>
                        <div class="flex items-center justify-end gap-1">
                            <button
                                @click="editReport(report.id)"
                                class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                title="Edit"
                            >
                                <Icon icon="hugeicons:pencil-edit-02" class="text-sm" />
                            </button>
                            <!-- <button
                                @click="openStatusDialog(report)"
                                class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                title="Set Status"
                            >
                                <Icon icon="hugeicons:task-done-01" class="text-sm" />
                            </button> -->
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- ── Pagination ─────────────────────────────── -->
        <div
            v-if="props.reports.last_page > 1"
            class="flex items-center justify-between px-5 py-3 border-t border-slate-100 mt-auto"
        >
            <span class="text-xs text-slate-400">
                Showing {{ props.reports.from }}–{{ props.reports.to }} of {{ props.reports.total }}
            </span>
            <div class="flex gap-1">
                <button
                    v-for="link in props.reports.links"
                    :key="link.label"
                    :disabled="!link.url || link.active"
                    @click="link.url && router.visit(link.url, { preserveState: true, preserveScroll: true })"
                    class="px-3 py-1.5 text-[11px] font-medium rounded-lg border transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                    :class="link.active
                        ? 'bg-indigo-600 text-white border-indigo-600'
                        : 'border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50'"
                    v-html="link.label"
                />
            </div>
        </div>
    </div>

    <!-- ── Status Dialog ──────────────────────────────── -->
    <Dialog
        v-model:visible="statusDialog"
        modal
        :draggable="false"
        header="Set Report Status"
        class="w-full max-w-sm"
    >
        <div class="flex flex-col gap-4 pt-1">

            <!-- Report info -->
            <div class="flex items-center gap-2.5 p-3 bg-slate-50 rounded-lg border border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                    <Icon icon="hugeicons:location-04" class="text-indigo-400 text-sm" />
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-700 truncate">
                        {{ selectedReport?.barangay?.name ?? '—' }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        {{ selectedReport?.date
                            ? new Date(selectedReport.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                            : '—' }}
                    </p>
                </div>
            </div>

            <!-- Status dropdown -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Status</label>
                <Select
                    v-model="selectedStatus"
                    :options="statusOptions"
                    option-label="label"
                    option-value="value"
                    placeholder="Select a status..."
                    class="w-full text-xs"
                />
            </div>

        </div>

        <!-- Footer -->
        <template #footer>
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="statusDialog = false"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    :disabled="!selectedStatus"
                    @click="submitStatus"
                    class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    Save Status
                </button>
            </div>
        </template>
    </Dialog>

</div>
</template>