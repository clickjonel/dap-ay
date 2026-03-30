<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { inject, ref, computed } from 'vue'
import Dialog from 'primevue/dialog'
import { router } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })
const props = defineProps({
  reports: Array,
  geoCoverage: Object
})

// const user = inject('user')
const toast = useToast()
const today = new Date().toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' })
const viewReportDialog = ref({
    visible: false,
    report: null,
})



const stats = computed(() => ({
  total: props.reports.length,
  pending: props.reports.filter(r => r.status === null).length,
  approved: props.reports.filter(r => r.status === 'approved').length,
  rejected: props.reports.filter(r => r.status === 'rejected').length,
}))

const pendingReports = computed(() => props.reports.filter(r => r.status === null))

const openViewReportDialog = (report) => {
    viewReportDialog.value.report = report
    viewReportDialog.value.visible = true
}

const submitStatus = (statusValue,reportID) => {
    router.delete(`/reports/${reportID}`, {
        data: { status: statusValue },
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ 
                severity: 'success', 
                summary: 'Report Updated', 
                detail: `Status set to ${statusValue}`, 
                life: 3000 
            })
        },
    })
}

</script>

<template>
  <div class="h-full flex flex-col gap-6 overflow-y-auto pb-10 bg-slate-50 px-6" style="font-family:'Plus Jakarta Sans',sans-serif; color: #1e293b;">
    <header class="pt-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-slate-900 text-2xl font-extrabold tracking-tight">System Overview</h1>
        <p class="text-slate-500 text-sm mt-1 flex items-center gap-2">
          {{ today }} · Overall Dashboard for DMO Access Level
        </p>
      </div>
    </header>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="(s, i) in [
        { label: 'Total Submissions', val: stats.total, color: 'bg-indigo-50' },
        { label: 'Pending Review', val: stats.pending, color: 'bg-amber-50' },
        { label: 'Approved Today', val: stats.approved, color: 'bg-teal-50' },
        { label: 'Revisions Needed', val: stats.rejected, color: 'bg-rose-50' },
      ]" :key="i" 
        :class="['p-5 rounded-2xl border border-slate-200 shadow-sm', s.color]">
        <p class="text-slate-600 text-[11px] font-bold uppercase tracking-wider">{{ s.label }}</p>
        <p class="text-slate-900 text-3xl font-extrabold mt-1">{{ s.val }}</p>
      </div>
    </div>

    <main class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Pending Reports Sections --> 
      <section class="lg:col-span-8 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 font-bold text-slate-800">Pending Reports</div>
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 font-bold">Report Date</th>
                    <th class="px-6 py-3 font-bold">Barangay - Municipality</th>
                    <th class="px-6 py-3 font-bold">Submitted By</th>
                    <th class="px-6 py-3 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-700">
                <tr v-for="report in pendingReports" :key="report.id" class="border-b border-slate-50 last:border-0 hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ report.date }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ report.barangay.name }} - {{ report.barangay.municipality.name }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <span v-for="user in report.users" :key="user.id" class="inline-block px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-tight bg-slate-100 text-slate-600 border border-slate-200">
                                {{ user.name }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <button @click="openViewReportDialog(report)" title="Review Report Details" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all active:scale-95">
                                <Icon icon="hugeicons:file-view" class="text-lg" />
                            </button>
                            <button @click="submitStatus('approved', report.id)" title="Approve Report" class="p-2 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-50 transition-all active:scale-95">
                                <Icon icon="hugeicons:checkmark-circle-02" class="text-lg" />
                            </button>
                            <button @click="submitStatus('rejected', report.id)" title="Reject Report" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all active:scale-95">
                                <Icon icon="hugeicons:cancel-circle" class="text-lg" />
                            </button>
                        </div>
                    </td>
                </tr>

                <tr v-if="pendingReports.length === 0">
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-slate-50 p-4 rounded-full mb-3">
                                <Icon icon="hugeicons:file-search" class="text-3xl text-slate-300" />
                            </div>
                            <p class="text-slate-500 font-medium">No pending approvals</p>
                            <p class="text-slate-400 text-xs mt-1">All reports have been reviewed and processed.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
      </section>

        <section class="lg:col-span-4 flex flex-col gap-6">

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Geographical Coverage</h4>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-2xl font-black text-slate-900 leading-none">{{ geoCoverage.municipalities || 0 }}</p>
                        <p class="text-[10px] text-slate-500 font-bold mt-1">MUNICIPALITIES</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-2xl font-black text-slate-900 leading-none">{{ geoCoverage.barangays || 0 }}</p>
                        <p class="text-[10px] text-slate-500 font-bold mt-1">BARANGAYS</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div>
                        <div class="flex justify-between text-[11px] mb-1 font-bold">
                            <span class="text-slate-600">GIDA Barangays</span>
                            <span class="text-indigo-600">{{ geoCoverage.gida || 0 }} / {{ geoCoverage.barangays || 0 }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500" :style="{ width: (geoCoverage.gida / geoCoverage.barangays * 100) + '%' }"></div>
                        </div>
                    </div>

                </div>
            </div>

        </section>

    </main>


    <!-- View Report Modal -->
    <Dialog v-model:visible="viewReportDialog.visible" header="View Report Details" class="w-[95%] h-full"> 
        <div v-if="viewReportDialog.report" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 print:hidden">
  
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
            <div class="flex items-center gap-2 mb-2">
                <Icon icon="hugeicons:location-01" class="text-slate-400 text-sm" />
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Barangay - Municipality</p>
            </div>
            <p class="text-xl font-extrabold text-slate-900 leading-tight">
                {{ viewReportDialog.report.barangay.name }}
            </p>
            <p class="text-sm text-slate-500 font-medium">
                {{ viewReportDialog.report.barangay.municipality.name }}
            </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
            <Icon icon="hugeicons:user-group" class="text-slate-400 text-sm" />
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Submitted By</p>
            </div>
            <div class="flex flex-wrap gap-1.5">
            <span 
                v-for="user in viewReportDialog.report.users" 
                :key="user.id" 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200"
            >
                {{ user.name }}
            </span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm border-t-4 border-t-indigo-500 relative overflow-hidden">
            <div class="flex items-center gap-2 mb-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Clients</p>
            </div>
            <div class="flex items-baseline gap-2">
            <p class="text-3xl font-black text-slate-900">
                {{ viewReportDialog.report.total_clients || 0 }}
            </p>
            <span class="text-xs font-bold text-slate-400 uppercase">Total</span>
            </div>
            <Icon icon="hugeicons:user-multiple" class="absolute -right-2 -bottom-2 text-slate-50 text-6xl" />
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm border-t-4 border-t-emerald-500 relative overflow-hidden">
            <div class="flex items-center gap-2 mb-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Returning Clients</p>
            </div>
            <div class="flex items-baseline gap-2">
            <p class="text-3xl font-black text-slate-900">
                {{ viewReportDialog.report.total_returning_clients || 0 }}
            </p>
            <span class="text-xs font-bold text-slate-400 uppercase">Records</span>
            </div>
            <Icon icon="hugeicons:arrow-reload-horizontal" class="absolute -right-2 -bottom-2 text-slate-50 text-6xl" />
        </div>

        </div>

         <div class="space-y-10">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden break-inside-avoid ring-1 ring-slate-100">

                    <div class="p-0 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Program Indicator Metric</th>
                                    <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-center w-32">Aggregate</th>
                                    <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Demographic Breakdown</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="value in viewReportDialog.report.values" :key="value.id" class="group hover:bg-slate-50/80 transition-all">
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">{{ value.indicator?.program?.name }}</span>
                                            <p class="text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">{{ value.indicator?.indicator_name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="inline-flex items-center justify-center min-w-[3rem] h-10 px-3 bg-slate-900 text-white rounded-xl font-black text-lg">
                                            {{ value.total }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-2">
                                            <div v-for="dis in value.disaggregations" :key="dis.id" class="flex justify-between items-center py-1.5 border-b border-slate-100 last:border-0 border-dotted">
                                                <span class="text-[11px] text-slate-500 font-medium">{{ dis.disaggregation?.disaggregation_name }}</span>
                                                <span class="text-[11px] font-black text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md ml-4">{{ dis.value }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-slate-300 italic">Report ID: #{{ report.id?.toString().padStart(6, '0') }}</div>
                        <div class="flex items-center gap-2">
                            <Icon icon="hugeicons:checkmark-circle-02" class="text-emerald-500 text-lg" />
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em]">Official Approved Record</span>
                        </div>
                    </div> -->
                </div>
            </div>
    </Dialog>

  </div>
</template>

<style scoped>
/* Custom Scrollbar for the list */
::-webkit-scrollbar {
  width: 5px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.1);
}
</style>