<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { inject, ref, computed } from 'vue'
import Dialog from 'primevue/dialog'
import { router } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'

defineOptions({ layout: Main })
const props = defineProps({
  reports: Object,
  geoCoverage: Object
})

const toast = useToast()
const today = new Date().toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' })
const viewReportDialog = ref({
    visible: false,
    report: null,
})
const confirmActionDialog = ref({
    visible: false,
    remarks: '',
    action:'',
    reportID:null
})

const stats = computed(() => ({
  total: props.reports.length,
  pending: props.reports.data.filter(r => r.status === null).length,
  approved: props.reports.data.filter(r => r.status === 'Approved').length,
  rejected: props.reports.data.filter(r => r.status === 'Rejected').length,
}))

const pendingReports = computed(() => props.reports.data.filter(r => r.status === null))

const openViewReportDialog = (report) => {
    viewReportDialog.value.report = report
    viewReportDialog.value.visible = true
}

const openConfirmActionDialog = (action, reportID) => {
   confirmActionDialog.value.visible = true
   confirmActionDialog.value.action = action
   confirmActionDialog.value.reportID = reportID
}


const submitConfirm = () => {
  if(confirmActionDialog.value.action === 'Approve'){
    router.delete(`/reports/${confirmActionDialog.value.reportID}`, {
        data: {
          status: 'Approved',
          remarks: confirmActionDialog.value.remarks
        },
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Report Updated',
                detail: `Report Approved`,
                life: 3000
            })
            confirmActionDialog.value.visible = false
            confirmActionDialog.value.remarks = ''
        },
    })
  }
  else{
    router.delete(`/reports/${confirmActionDialog.value.reportID}`, {
        data: {
          status: 'Rejected',
          remarks: confirmActionDialog.value.remarks
        },
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Report Updated',
                detail: `Report Rejected`,
                life: 3000
            })
            confirmActionDialog.value.visible = false
            confirmActionDialog.value.remarks = ''
        },
    })
  }

}


</script>

<template>
  <div class="h-full flex flex-col gap-5 overflow-y-auto pb-10 px-5 lg:px-7 bg-slate-50">

    <!-- Header -->
    <header class="pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1">DMO Dashboard</p>
        <h1 class="text-xl font-bold text-slate-800 leading-tight">System Overview</h1>
      </div>
      <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs text-slate-500 font-medium self-start sm:self-auto">
        <Icon icon="hugeicons:calendar-03" class="text-slate-400 text-sm" />
        {{ today }}
      </span>
    </header>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Total Submissions</p>
        <p class="text-3xl font-bold text-slate-800">{{ stats.total }}</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Review</p>
          <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
        </div>
        <p class="text-3xl font-bold text-amber-500">{{ stats.pending }}</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Approved</p>
          <span class="w-2 h-2 rounded-full bg-teal-400 shrink-0"></span>
        </div>
        <p class="text-3xl font-bold text-teal-500">{{ stats.approved }}</p>
      </div>
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rejected (Needed Revisions) </p>
          <span class="w-2 h-2 rounded-full bg-rose-400 shrink-0"></span>
        </div>
        <p class="text-3xl font-bold text-rose-500">{{ stats.rejected }}</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

      <!-- Pending Reports Table -->
      <section class="lg:col-span-8 bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
          <div>
            <h2 class="text-sm font-bold text-slate-800">Pending Reports</h2>
            <p class="text-[11px] text-slate-400 mt-0.5">Reports awaiting your review</p>
          </div>
          <span v-if="pendingReports.length > 0" class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-600 text-[10px] font-bold">
            {{ pendingReports.length }}
          </span>
        </div>

        <table class="w-full text-left">
          <thead class="border-b border-slate-100">
            <tr>
              <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Report Date</th>
              <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Location</th>
              <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Submitted By</th>
              <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="pendingReports.length === 0">
              <td colspan="4" class="px-5 py-16 text-center">
                <div class="flex flex-col items-center gap-2">
                  <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center">
                    <Icon icon="hugeicons:file-search" class="text-lg text-slate-300" />
                  </div>
                  <p class="text-sm font-medium text-slate-500 mt-1">No pending approvals</p>
                  <p class="text-xs text-slate-400">All reports have been reviewed.</p>
                </div>
              </td>
            </tr>
            <tr
              v-else
              v-for="report in pendingReports"
              :key="report.id"
              class="border-b border-slate-50 last:border-0 hover:bg-slate-50/60 transition-colors"
            >
              <td class="px-5 py-3.5">
                <span class="text-xs font-semibold text-slate-700">{{ report.date }}</span>
              </td>
              <td class="px-5 py-3.5">
                <div>
                  <p class="text-xs font-semibold text-slate-700">{{ report.barangay.name }}</p>
                  <p class="text-[11px] text-slate-400 mt-0.5">{{ report.barangay.municipality.name }}</p>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <div class="flex flex-wrap gap-1.5">
                  <span
                    v-for="user in report.users"
                    :key="user.id"
                    class="inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200"
                  >
                    {{ user.name }}
                  </span>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <div class="flex items-center justify-end gap-0.5">
                  <button
                    @click="openViewReportDialog(report)"
                    title="Review"
                    class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                  >
                    <Icon icon="hugeicons:file-view" class="text-base" />
                  </button>
                  <button
                    @click="openConfirmActionDialog('Approve',report.id)"
                    title="Approve"
                    class="p-2 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-50 transition-all"
                  >
                    <Icon icon="hugeicons:checkmark-circle-02" class="text-base" />
                  </button>
                  <button
                    @click="openConfirmActionDialog('Rejected',report.id)"
                    title="Reject"
                    class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"
                  >
                    <Icon icon="hugeicons:cancel-circle" class="text-base" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Sidebar -->
      <section class="lg:col-span-4 flex flex-col gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Geographical Coverage</p>

          <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 text-center">
              <p class="text-2xl font-bold text-slate-800 leading-none">{{ geoCoverage.municipalities || 0 }}</p>
              <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1.5">Municipalities</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 text-center">
              <p class="text-2xl font-bold text-slate-800 leading-none">{{ geoCoverage.barangays || 0 }}</p>
              <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1.5">Barangays</p>
            </div>
          </div>

          <div>
            <div class="flex justify-between text-[11px] mb-2">
              <span class="font-semibold text-slate-600">GIDA Barangays</span>
              <span class="font-bold text-indigo-600">{{ geoCoverage.gida || 0 }} / {{ geoCoverage.barangays || 0 }}</span>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
              <div
                class="h-full bg-indigo-500 rounded-full transition-all"
                :style="{ width: (geoCoverage.gida / geoCoverage.barangays * 100) + '%' }"
              ></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5 text-right">
              {{ Math.round(geoCoverage.gida / geoCoverage.barangays * 100) }}% coverage
            </p>
          </div>
        </div>
      </section>
    </div>

    <!-- View Report Dialog -->
    <Dialog v-model:visible="viewReportDialog.visible" header="Report Details" class="w-[95vw] lg:w-4/5 xl:w-3/4">
      <div v-if="viewReportDialog.report">

        <!-- Meta Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
          <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Location</p>
            <p class="text-sm font-bold text-slate-800 leading-snug">{{ viewReportDialog.report.barangay.name }}</p>
            <p class="text-xs text-slate-500 mt-0.5">{{ viewReportDialog.report.barangay.municipality.name }}</p>
          </div>

          <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Submitted By</p>
            <div class="flex flex-wrap gap-1">
              <span
                v-for="user in viewReportDialog.report.users"
                :key="user.id"
                class="inline-block px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white text-slate-600 border border-slate-200"
              >
                {{ user.name }}
              </span>
            </div>
          </div>

          <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-4">
            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-2">Total Clients</p>
            <p class="text-2xl font-bold text-indigo-700 leading-none">{{ viewReportDialog.report.total_clients || 0 }}</p>
          </div>

          <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-4">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider mb-2">Returning Clients</p>
            <p class="text-2xl font-bold text-emerald-700 leading-none">{{ viewReportDialog.report.total_returning_clients || 0 }}</p>
          </div>
        </div>

        <!-- Report Values Table -->
        <div class="rounded-xl border border-slate-200 overflow-hidden">
          <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Indicator</th>
                <th class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-center w-28">Total</th>
                <th class="px-6 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Breakdown</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="value in viewReportDialog.report.values"
                :key="value.id"
                class="hover:bg-slate-50/60 transition-colors"
              >
                <td class="px-6 py-4">
                  <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider block mb-1">
                    {{ value.indicator?.program?.name }}
                  </span>
                  <p class="text-xs font-semibold text-slate-700">{{ value.indicator?.indicator_name }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center justify-center min-w-[2.5rem] h-9 px-3 bg-slate-900 text-white rounded-lg font-bold text-base">
                    {{ value.total }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-1.5">
                    <div
                      v-for="dis in value.disaggregations"
                      :key="dis.id"
                      class="flex justify-between items-center gap-3"
                    >
                      <span class="text-[11px] text-slate-500">{{ dis.disaggregation?.disaggregation_name }}</span>
                      <span class="text-[11px] font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded shrink-0">{{ dis.value }}</span>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </Dialog>

    <!-- Confirm Action -->
    <Dialog v-model:visible="confirmActionDialog.visible" header="Confirm Action" class="w-[95vw] lg:w-4/5 xl:w-3/4">
      <div class="w-full flex flex-col justify-start items-start gap-4">
          <span class="w-full text-center font-bold text-2xl">Are you sure to {{ confirmActionDialog.action }} this report? Please Indicate remarks if neccesary.</span>
          <div class="w-full flex flex-col gap-2">
            <Textarea
              v-model="confirmActionDialog.remarks"
              placeholder="Remarks"
              rows="4"
              class="w-full text-xs resize-none"
            />
          </div>
      </div>

      <template #footer>
        <div class="flex items-center justify-end gap-2 pt-1">
          <Button
            label="Cancel"
            severity="secondary"
            text
            size="small"
            @click="confirmActionDialog.visible = false"
          />
          <Button
            :label="confirmActionDialog.action === 'Approve' ? 'Approve' : 'Rejected'"
            :severity="confirmActionDialog.action === 'Approve' ? 'success' : 'danger'"
            size="small"
            :icon="confirmActionDialog.action === 'Approve' ? 'pi pi-check' : 'pi pi-times'"
            @click="submitConfirm"
          />
        </div>
      </template>

    </Dialog>

  </div>
</template>

<style scoped>
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
