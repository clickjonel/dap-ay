<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { ref, computed } from 'vue'
import Dialog from 'primevue/dialog'
import { router } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'

defineOptions({ layout: Main })

const props = defineProps({
    reports:     Object, // paginated
    stats:       Object, // { total, pending, approved, rejected }
    geoCoverage: Object, // { municipalities, barangays, gida }
})

const toast = useToast()
const today = new Date().toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' })

// ── Selection ────────────────────────────────────────────
const selectedIDs = ref([])

const allSelected = computed(() => {
    const ids = props.reports?.data?.map(r => r.id) ?? []
    return ids.length > 0 && ids.every(id => selectedIDs.value.includes(id))
})

const someSelected = computed(() => selectedIDs.value.length > 0 && !allSelected.value)

const toggleAll = () => {
    const ids = props.reports?.data?.map(r => r.id) ?? []
    selectedIDs.value = allSelected.value ? [] : [...ids]
}

const toggleOne = (id) => {
    if (selectedIDs.value.includes(id)) {
        selectedIDs.value = selectedIDs.value.filter(i => i !== id)
    } else {
        selectedIDs.value = [...selectedIDs.value, id]
    }
}

// ── Dialogs ──────────────────────────────────────────────
const viewReportDialog    = ref({ visible: false, report: null })
const confirmActionDialog = ref({ visible: false, remarks: '', action: '', reportIDs: [] })

const openViewReportDialog = (report) => {
    viewReportDialog.value.report  = report
    viewReportDialog.value.visible = true
}

// Single action
const openConfirmActionDialog = (action, reportID) => {
    confirmActionDialog.value = { visible: true, remarks: '', action, reportIDs: [reportID] }
}

// Bulk action
const openBulkActionDialog = (action) => {
    if (!selectedIDs.value.length) return
    confirmActionDialog.value = { visible: true, remarks: '', action, reportIDs: [...selectedIDs.value] }
}

// ── Submit ───────────────────────────────────────────────
const submitConfirm = () => {
    const status = confirmActionDialog.value.action === 'Approve' ? 'Approved' : 'Rejected'
    const isBulk = confirmActionDialog.value.reportIDs.length > 1

    router.post('/reports/bulk-action', {
        report_ids: confirmActionDialog.value.reportIDs,
        status,
        remarks: confirmActionDialog.value.remarks,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Reports Updated',
                detail:   isBulk
                    ? `${confirmActionDialog.value.reportIDs.length} reports ${status.toLowerCase()}`
                    : `Report ${status}`,
                life: 3000,
            })
            confirmActionDialog.value.visible = false
            confirmActionDialog.value.remarks = ''
            selectedIDs.value = []
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Something went wrong. Please try again.', life: 3000 })
        }
    })
}

// ── Helpers ──────────────────────────────────────────────
const gidaPercent    = () => !props.geoCoverage?.barangays ? 0 : Math.round((props.geoCoverage.gida / props.geoCoverage.barangays) * 100)
const approvalRate   = () => !props.stats?.total ? 0 : Math.round((props.stats.approved / props.stats.total) * 100)
const rejectionRate  = () => !props.stats?.total ? 0 : Math.round((props.stats.rejected / props.stats.total) * 100)
</script>

<template>
  <div class="min-h-full flex flex-col gap-6 overflow-y-auto pb-12 px-4 sm:px-6 lg:px-8 bg-[#f8f9fb]">

    <!-- ── Header ─────────────────────────────────────────── -->
    <header class="pt-7 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
      <div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1.5">DMO · System Overview</p>
        <h1 class="text-2xl font-black text-slate-900 leading-none tracking-tight">Dashboard</h1>
      </div>
      <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-500 font-semibold shadow-sm self-start sm:self-auto">
        <Icon icon="hugeicons:calendar-03" class="text-slate-400" />
        {{ today }}
      </span>
    </header>

    <!-- ── Stat Cards ─────────────────────────────────────── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
      <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</p>
          <span class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center">
            <Icon icon="hugeicons:file-02" class="text-slate-500 text-sm" />
          </span>
        </div>
        <p class="text-4xl font-black text-slate-900 leading-none tabular-nums">{{ stats?.total ?? 0 }}</p>
        <p class="text-[11px] text-slate-400 font-medium">Submissions</p>
      </div>
      <div class="bg-amber-50 rounded-2xl border border-amber-100 p-5 shadow-sm flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Pending</p>
          <span class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
            <Icon icon="hugeicons:clock-01" class="text-amber-500 text-sm" />
          </span>
        </div>
        <p class="text-4xl font-black text-amber-600 leading-none tabular-nums">{{ stats?.pending ?? 0 }}</p>
        <p class="text-[11px] text-amber-500 font-medium">Awaiting review</p>
      </div>
      <div class="bg-teal-50 rounded-2xl border border-teal-100 p-5 shadow-sm flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-black text-teal-500 uppercase tracking-widest">Approved</p>
          <span class="w-7 h-7 rounded-lg bg-teal-100 flex items-center justify-center">
            <Icon icon="hugeicons:checkmark-circle-02" class="text-teal-500 text-sm" />
          </span>
        </div>
        <p class="text-4xl font-black text-teal-600 leading-none tabular-nums">{{ stats?.approved ?? 0 }}</p>
        <p class="text-[11px] text-teal-500 font-medium">Reports approved</p>
      </div>
      <div class="bg-rose-50 rounded-2xl border border-rose-100 p-5 shadow-sm flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Rejected</p>
          <span class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center">
            <Icon icon="hugeicons:cancel-circle" class="text-rose-500 text-sm" />
          </span>
        </div>
        <p class="text-4xl font-black text-rose-600 leading-none tabular-nums">{{ stats?.rejected ?? 0 }}</p>
        <p class="text-[11px] text-rose-500 font-medium">Need revisions</p>
      </div>
    </div>

    <!-- ── Main Grid ──────────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

      <!-- Pending Reports Table -->
      <section class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">

        <!-- Section Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
          <div>
            <h2 class="text-sm font-black text-slate-800 tracking-tight">Pending Reports</h2>
            <p class="text-[11px] text-slate-400 mt-0.5">Reports awaiting your approval</p>
          </div>
          <span v-if="stats?.pending > 0" class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2 rounded-full bg-amber-100 text-amber-600 text-[10px] font-black">
            {{ stats.pending }}
          </span>
        </div>

        <!-- Bulk Action Bar -->
        <Transition
          enter-active-class="transition-all duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition-all duration-150 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div v-if="selectedIDs.length > 0" class="px-6 py-3 bg-indigo-50 border-b border-indigo-100 flex items-center justify-between gap-3">
            <span class="text-xs font-bold text-indigo-700">
              {{ selectedIDs.length }} report{{ selectedIDs.length > 1 ? 's' : '' }} selected
            </span>
            <div class="flex items-center gap-2">
              <button
                @click="openBulkActionDialog('Approve')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-500 hover:bg-teal-600 text-white text-xs font-bold transition-colors shadow-sm"
              >
                <Icon icon="hugeicons:checkmark-circle-02" class="text-sm" />
                Approve All
              </button>
              <button
                @click="openBulkActionDialog('Reject')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold transition-colors shadow-sm"
              >
                <Icon icon="hugeicons:cancel-circle" class="text-sm" />
                Reject All
              </button>
              <button
                @click="selectedIDs = []"
                class="p-1.5 rounded-lg text-indigo-400 hover:text-indigo-600 hover:bg-indigo-100 transition-colors"
                title="Clear selection"
              >
                <Icon icon="hugeicons:cancel-01" class="text-sm" />
              </button>
            </div>
          </div>
        </Transition>

        <!-- Table -->
        <div class="overflow-x-auto flex-1">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50/60">
                <!-- Select All -->
                <th class="pl-6 pr-3 py-3 w-10">
                  <button
                    @click="toggleAll"
                    class="w-4 h-4 rounded border flex items-center justify-center transition-colors"
                    :class="allSelected
                        ? 'bg-indigo-500 border-indigo-500'
                        : someSelected
                            ? 'bg-indigo-100 border-indigo-300'
                            : 'bg-white border-slate-300 hover:border-indigo-400'"
                  >
                    <Icon
                      v-if="allSelected"
                      icon="hugeicons:tick-01"
                      class="text-white text-[10px]"
                    />
                    <Icon
                      v-else-if="someSelected"
                      icon="hugeicons:minus-sign"
                      class="text-indigo-500 text-[10px]"
                    />
                  </button>
                </th>
                <th class="px-3 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Date</th>
                <th class="px-3 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Location</th>
                <th class="px-3 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 hidden sm:table-cell">Submitted By</th>
                <th class="px-3 pr-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>

              <!-- Empty State -->
              <tr v-if="!reports?.data?.length">
                <td colspan="5" class="px-6 py-20 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center">
                      <Icon icon="hugeicons:file-search" class="text-xl text-slate-300" />
                    </div>
                    <div>
                      <p class="text-sm font-bold text-slate-600">All caught up!</p>
                      <p class="text-xs text-slate-400 mt-0.5">No pending reports to review.</p>
                    </div>
                  </div>
                </td>
              </tr>

              <!-- Rows -->
              <tr
                v-else
                v-for="report in reports.data"
                :key="report.id"
                class="border-b border-slate-50 last:border-0 hover:bg-slate-50/70 transition-colors"
                :class="selectedIDs.includes(report.id) ? 'bg-indigo-50/40' : ''"
              >
                <!-- Checkbox -->
                <td class="pl-6 pr-3 py-4">
                  <button
                    @click="toggleOne(report.id)"
                    class="w-4 h-4 rounded border flex items-center justify-center transition-colors shrink-0"
                    :class="selectedIDs.includes(report.id)
                        ? 'bg-indigo-500 border-indigo-500'
                        : 'bg-white border-slate-300 hover:border-indigo-400'"
                  >
                    <Icon v-if="selectedIDs.includes(report.id)" icon="hugeicons:tick-01" class="text-white text-[10px]" />
                  </button>
                </td>
                <td class="px-3 py-4">
                  <span class="text-xs font-bold text-slate-700 tabular-nums">{{ report.date }}</span>
                </td>
                <td class="px-3 py-4">
                  <p class="text-xs font-bold text-slate-800">{{ report.barangay?.name }}</p>
                  <p class="text-[11px] text-slate-400 mt-0.5">{{ report.barangay?.municipality?.name }}</p>
                </td>
                <td class="px-3 py-4 hidden sm:table-cell">
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="user in report.users"
                      :key="user.id"
                      class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600"
                    >{{ user.name }}</span>
                  </div>
                </td>
                <td class="px-3 pr-6 py-4">
                  <div class="flex items-center justify-end gap-1">
                    <button @click="openViewReportDialog(report)" title="View" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                      <Icon icon="hugeicons:file-view" class="text-base" />
                    </button>
                    <button @click="openConfirmActionDialog('Approve', report.id)" title="Approve" class="p-1.5 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-50 transition-all">
                      <Icon icon="hugeicons:checkmark-circle-02" class="text-base" />
                    </button>
                    <button @click="openConfirmActionDialog('Reject', report.id)" title="Reject" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                      <Icon icon="hugeicons:cancel-circle" class="text-base" />
                    </button>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="reports?.last_page > 1" class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-between bg-slate-50/40">
          <p class="text-[11px] text-slate-400 font-medium">
            Showing <span class="font-bold text-slate-600">{{ reports.from }}–{{ reports.to }}</span> of <span class="font-bold text-slate-600">{{ reports.total }}</span>
          </p>
          <div class="flex items-center gap-1.5">
            <button
              :disabled="!reports.prev_page_url"
              @click="router.get(reports.prev_page_url, {}, { preserveScroll: true })"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed shadow-sm"
            >
              <Icon icon="hugeicons:arrow-left-01" class="text-xs" /> Prev
            </button>
            <span class="text-xs font-bold text-slate-400 tabular-nums px-1">{{ reports.current_page }} / {{ reports.last_page }}</span>
            <button
              :disabled="!reports.next_page_url"
              @click="router.get(reports.next_page_url, {}, { preserveScroll: true })"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed shadow-sm"
            >
              Next <Icon icon="hugeicons:arrow-right-01" class="text-xs" />
            </button>
          </div>
        </div>

      </section>

      <!-- ── Sidebar ──────────────────────────────────────── -->
      <section class="lg:col-span-4 flex flex-col gap-4">

        <!-- Geo Coverage -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col gap-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Geographic Coverage</p>
              <p class="text-xs text-slate-500 mt-0.5 font-medium">Province overview</p>
            </div>
            <span class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
              <Icon icon="hugeicons:maps-location-01" class="text-indigo-500 text-sm" />
            </span>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex flex-col gap-1">
              <p class="text-3xl font-black text-slate-900 leading-none tabular-nums">{{ geoCoverage?.municipalities ?? 0 }}</p>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Municipalities</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex flex-col gap-1">
              <p class="text-3xl font-black text-slate-900 leading-none tabular-nums">{{ geoCoverage?.barangays ?? 0 }}</p>
              <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Barangays</p>
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
              <p class="text-xs font-bold text-slate-600">GIDA Barangays</p>
              <p class="text-xs font-black text-indigo-600 tabular-nums">{{ geoCoverage?.gida ?? 0 }} / {{ geoCoverage?.barangays ?? 0 }}</p>
            </div>
            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
              <div class="h-full bg-indigo-500 rounded-full transition-all duration-500" :style="{ width: gidaPercent() + '%' }" />
            </div>
            <p class="text-[10px] font-bold text-slate-400 text-right">{{ gidaPercent() }}% of barangays</p>
          </div>
        </div>

        <!-- Report Summary -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col gap-4">
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Report Summary</p>
          <div class="flex flex-col divide-y divide-slate-50">
            <div class="flex items-center justify-between py-2.5">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                <span class="text-xs font-semibold text-slate-600">Pending</span>
              </div>
              <span class="text-xs font-black text-amber-600 tabular-nums">{{ stats?.pending ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-teal-400 shrink-0"></span>
                <span class="text-xs font-semibold text-slate-600">Approved</span>
              </div>
              <span class="text-xs font-black text-teal-600 tabular-nums">{{ stats?.approved ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between py-2.5">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-400 shrink-0"></span>
                <span class="text-xs font-semibold text-slate-600">Rejected</span>
              </div>
              <span class="text-xs font-black text-rose-600 tabular-nums">{{ stats?.rejected ?? 0 }}</span>
            </div>
          </div>
          <div v-if="stats?.total > 0" class="flex flex-col gap-1.5 pt-1">
            <div class="flex justify-between text-[10px] font-bold text-slate-400">
              <span>Approval rate</span>
              <span class="text-teal-600">{{ approvalRate() }}%</span>
            </div>
            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden flex">
              <div class="h-full bg-teal-500 transition-all duration-500 rounded-l-full" :style="{ width: approvalRate() + '%' }" />
              <div class="h-full bg-rose-400 transition-all duration-500" :style="{ width: rejectionRate() + '%' }" />
            </div>
            <div class="flex justify-between text-[10px] font-medium text-slate-400">
              <span class="text-teal-500">{{ approvalRate() }}% approved</span>
              <span class="text-rose-400">{{ rejectionRate() }}% rejected</span>
            </div>
          </div>
        </div>

      </section>
    </div>

    <!-- ── View Report Dialog ─────────────────────────────── -->
    <Dialog v-model:visible="viewReportDialog.visible" header="Report Details" class="w-[95vw] lg:w-4/5 xl:w-3/4">
      <div v-if="viewReportDialog.report">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
          <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Location</p>
            <p class="text-sm font-black text-slate-800 leading-snug">{{ viewReportDialog.report.barangay?.name }}</p>
            <p class="text-xs text-slate-500 mt-0.5">{{ viewReportDialog.report.barangay?.municipality?.name }}</p>
          </div>
          <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Submitted By</p>
            <div class="flex flex-wrap gap-1">
              <span v-for="user in viewReportDialog.report.users" :key="user.id" class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-white text-slate-600 border border-slate-200">{{ user.name }}</span>
            </div>
          </div>
          <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-4">
            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-wider mb-2">Total Clients</p>
            <p class="text-3xl font-black text-indigo-700 leading-none tabular-nums">{{ viewReportDialog.report.total_clients ?? 0 }}</p>
          </div>
          <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-4">
            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-wider mb-2">Returning Clients</p>
            <p class="text-3xl font-black text-emerald-700 leading-none tabular-nums">{{ viewReportDialog.report.total_returning_clients ?? 0 }}</p>
          </div>
        </div>
        <div class="rounded-xl border border-slate-200 overflow-hidden">
          <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Indicator</th>
                <th class="px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center w-24">Total</th>
                <th class="px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Breakdown</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="value in viewReportDialog.report.values" :key="value.id" class="hover:bg-slate-50/60 transition-colors">
                <td class="px-5 py-4">
                  <span class="text-[10px] font-black text-indigo-500 uppercase tracking-wider block mb-1">{{ value.indicator?.program?.name }}</span>
                  <p class="text-xs font-semibold text-slate-700">{{ value.indicator?.indicator_name }}</p>
                </td>
                <td class="px-5 py-4 text-center">
                  <span class="inline-flex items-center justify-center min-w-[2.5rem] h-9 px-3 bg-slate-900 text-white rounded-xl font-black text-base tabular-nums">{{ value.total }}</span>
                </td>
                <td class="px-5 py-4">
                  <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-5 gap-y-1.5">
                    <div v-for="dis in value.disaggregations" :key="dis.id" class="flex justify-between items-center gap-2">
                      <span class="text-[11px] text-slate-500">{{ dis.disaggregation?.disaggregation_name }}</span>
                      <span class="text-[11px] font-black text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded-md shrink-0 tabular-nums">{{ dis.value }}</span>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </Dialog>

    <!-- ── Confirm Action Dialog ──────────────────────────── -->
    <Dialog v-model:visible="confirmActionDialog.visible" header="Confirm Action" class="w-[95vw] sm:w-[460px]">
      <div class="flex flex-col items-center gap-4 py-2">
        <div
          class="w-14 h-14 rounded-2xl flex items-center justify-center"
          :class="confirmActionDialog.action === 'Approve' ? 'bg-teal-50 border border-teal-100' : 'bg-rose-50 border border-rose-100'"
        >
          <Icon
            :icon="confirmActionDialog.action === 'Approve' ? 'hugeicons:checkmark-circle-02' : 'hugeicons:cancel-circle'"
            class="text-2xl"
            :class="confirmActionDialog.action === 'Approve' ? 'text-teal-500' : 'text-rose-500'"
          />
        </div>
        <div class="text-center">
          <p class="text-sm font-black text-slate-800">
            <template v-if="confirmActionDialog.reportIDs.length > 1">
              {{ confirmActionDialog.action === 'Approve' ? 'Approve' : 'Reject' }} {{ confirmActionDialog.reportIDs.length }} reports?
            </template>
            <template v-else>
              {{ confirmActionDialog.action === 'Approve' ? 'Approve this report?' : 'Reject this report?' }}
            </template>
          </p>
          <p class="text-xs text-slate-400 mt-1">Please add remarks if necessary before confirming.</p>
        </div>
        <Textarea
          v-model="confirmActionDialog.remarks"
          placeholder="Remarks (optional)"
          rows="3"
          class="w-full text-xs resize-none"
        />
      </div>
      <template #footer>
        <div class="flex items-center justify-end gap-2">
          <Button label="Cancel" severity="secondary" text size="small" @click="confirmActionDialog.visible = false" />
          <Button
            :label="confirmActionDialog.action === 'Approve' ? 'Approve' : 'Reject'"
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