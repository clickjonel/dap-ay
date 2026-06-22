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
const confirmResubmitDialog = ref(false)
const confirmDeleteReportDialog = ref(false)
const selectedReport = ref(null)


function openStatusDialog(report) {
    selectedReport.value = report
    statusDialog.value = true
}

function openConfirmActionDialog(report) {
    selectedReport.value = report
    confirmResubmitDialog.value = true
}

function openConfirmDeleteReportDialog(report) {
    selectedReport.value = report
    confirmDeleteReportDialog.value = true
}

const deleteReport = () => {
    router.delete(`/report/${selectedReport.value.id}`, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Report Deleted', detail: 'Report has been deleted successfully.', life: 2000 })
            confirmDeleteReportDialog.value = false
        },
        onError: () => toast.add({ severity: 'error', summary: 'Failed to delete report', life: 2000 }),
    })
}

const resubmitReport = () => {
    router.delete(`/reports/${selectedReport.value.id}`, {
        data: { 
          status: null,
          remarks: null
        },
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ 
                severity: 'success', 
                summary: 'Report Submitted', 
                detail: `Awaiting for DMO review of the report`, 
                life: 3000 
            })
            confirmResubmitDialog.value = false
        },
    })
}

const goToPage = (url) => {
    if (!url) return
    router.get(url, { search: search.value }, {
        preserveState:  true,
        preserveScroll: true,
    })
}

</script>

<template>
    <div class="h-full flex flex-col gap-4">
        <div class="w-full flex justify-between items-center">
            <div class="w-full flex justify-between items-center">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Search</label>
                    <input @input="onSearch" :value="search" type="text" class="w-full lg:w-[300px] border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400 text-xs" placeholder="Input Keyword">
                </div>

                <button @click="router.visit('reports/create')" class="px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-emerald-900 transition-colors cursor-pointer">New Report</button>

            </div>
        </div>

        <!-- ── Table ─────────────────────────────────────── -->
        <div class="overflow-y-auto px-2 pb-2">
            <div class="w-full grid grid-cols-12 divide-x divide-gray-400 text-xs font-semibold tracking-widest uppercase text-gray-600 bg-sky-100 border border-gray-400 sticky top-0">
                <span class="col-span-2 p-3">Barangay</span>
                <span class="col-span-2 p-3">Total Clients</span>
                <span class="col-span-2 p-3">Submitted By</span>
                <span class="col-span-2 p-3">Date</span>
                <span class="col-span-2 p-3">Status</span>
                <span class="col-span-2 p-3">Actions</span>
            </div>
            <div v-for="rep in reports.data" class="w-full grid grid-cols-12 divide-x divide-gray-400 text-xs tracking-widest text-gray-600  border-b border-x border-gray-400">
                <span class="col-span-2 p-3 flex flex-col">
                    <div class="flex flex-col bg-purple-100 p-2 shadow-sm">
                        <span class="text-md uppercase font-semibold">{{ rep.barangay.name }}</span>
                        <span class="text-[10px]">{{ `${rep.barangay.municipality.name} - ${rep.barangay.province.name}` }}</span>
                    </div>
                </span>
                <span class="col-span-2 p-3">
                    {{ rep.total_clients }} Total Clients
                </span>
                <span class="col-span-2 p-3 flex flex-col gap-1">
                    <div v-for="user in rep.users" class="flex flex-col bg-purple-100 p-1 shadow-sm">
                        <span class="text-[10px]">{{ user.name }}</span>
                    </div>
                </span>
                <span class="col-span-2 p-3 font-medium">
                    {{ rep.date }}
                </span>
                <span class="col-span-2 p-3 flex flex-col gap-2">
                    <div class="flex flex-col p-1 shadow-sm font-medium" :class="rep.status === 'Approved' ? 'bg-emerald-200' : rep.status === 'Rejected' ? 'bg-rose-300' : 'bg-slate-200'">
                        <span class="text-[10px]">{{ rep.status ?? 'Pending' }}</span>
                    </div>
                    <span v-if="rep.remarks" @click="openStatusDialog(rep)" class="text-[10px] bg-sky-200 p-1 shadow-sm hover:bg-sky-300 cursor-pointer">View Remarks</span>
                </span>
                <span class="col-span-2 p-3">
                    <button v-if="canEditReport(rep.status)" @click="editReport(rep.id)" class="p-1 text-gray-400 hover:text-green-800 hover:bg-green-50 border border-transparent hover:border-green-200 transition-all" title="Edit Barangay">
                        <Icon icon="boxicons:edit" class="text-base" />
                    </button>
                    <button @click="openConfirmDeleteReportDialog(rep)" class="p-1 text-gray-400 hover:text-red-400 hover:bg-red-50 border border-transparent hover:border-red-200 transition-all" title="Delete Barangay">
                        <Icon icon="mdi:trash-can-outline" class="text-base" />
                    </button>
                    <button v-if="rep.status === 'Rejected'" @click="openConfirmActionDialog(rep)" class="p-1 text-gray-400 hover:text-purple-400 hover:bg-purple-50 border border-transparent hover:border-purple-200 transition-all" title="Resubmit Report">
                        <Icon icon="hugeicons:reset-password" class="text-base" />
                    </button>
                </span>
            </div>

        </div>

        <!-- ── Pagination ─────────────────────────────── -->
        <div class="w-full flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-white shrink-0">

            <span class="text-xs text-slate-400">
                Showing {{ reports.data.length }} Records Per Page
            </span>

            <div class="flex items-center gap-1.5">
                <button
                    @click="goToPage(reports.prev_page_url)"
                    :disabled="!reports.prev_page_url"
                    class="h-8 p-2.5 flex items-center gap-1 text-sm shadow-md text-slate-900 border border-slate-400 transition-all disabled:opacity-30 cursor-pointer hover:bg-sky-400 hover:text-white disabled:cursor-not-allowed"
                >
                    <Icon icon="lucide:chevron-left" class="text-sm"/>
                    
                </button>
                <button
                    @click="goToPage(reports.next_page_url)"
                    :disabled="!reports.next_page_url"
                    class="h-8 p-2.5 flex items-center gap-1 text-sm shadow-md text-slate-900 border border-slate-400 transition-all disabled:opacity-30 cursor-pointer hover:bg-sky-400 hover:text-white disabled:cursor-not-allowed"
                >
                    <Icon icon="lucide:chevron-right" class="text-sm"/>
                </button>
            </div>

        </div>

        <Dialog v-model:visible="statusDialog" modal :draggable="false" header="Update Report Status" class="w-full max-w-sm mx-4">
            <div class="w-full flex flex-col gap-5 py-2 bg-gray-100 p-2 shadow-sm">
                <p>{{ selectedReport.remarks }}</p>
            </div>
        </Dialog>

        <Dialog v-model:visible="confirmResubmitDialog" modal :draggable="false" header="Confirm Report Resubmission" class="w-full max-w-sm mx-4">
            <div class="flex flex-col gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:delete-02" class="text-base text-red-500" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">
                            Are you sure you want to resubmit this report?
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            This will be sent to the DMO again for review. Please make sure to edit the report based on remarks of the DMO or needed updated from the report.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button
                        type="button"
                        @click="confirmResubmitDialog = false"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="resubmitReport"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors"
                    >
                        <Icon icon="hugeicons:reset-password" class="text-sm" />
                        Resubmit
                    </button>
                </div>
            </div>
        </Dialog>

        <!-- confirm delete report -->
        <Dialog v-model:visible="confirmDeleteReportDialog" modal :draggable="false" header="Confirm Report Resubmission" class="w-full max-w-sm mx-4">
            <div class="flex flex-col gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:delete-02" class="text-base text-red-500" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">
                            Are you sure you want to delete this report?
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            This will delete all report related values, disaggregations, users and report record. Click Delete to delete record.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button
                        type="button"
                        @click="confirmResubmitDialog = false"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="deleteReport"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                    >
                        <Icon icon="hugeicons:delete-03" class="text-sm" />
                        Delete Report
                    </button>
                </div>
            </div>
        </Dialog>

    </div>
</template>