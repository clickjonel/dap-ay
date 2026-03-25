<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import DatePicker from 'primevue/datepicker'
import FloatLabel from 'primevue/floatlabel'
import Button from 'primevue/button'
import { Icon } from '@iconify/vue'

const props = defineProps({
    reports: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) }
})

const start = ref(props.filters.start ? new Date(props.filters.start) : null)
const end = ref(props.filters.end ? new Date(props.filters.end) : null)

const applyFilter = () => {
    router.get('/generate/approved-report-submissions', 
        { 
            start: start.value?.toISOString().split('T')[0], 
            end: end.value?.toISOString().split('T')[0] 
        }, 
        { preserveState: true }
    )
}

const printReport = () => {
    window.print()
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col">
        
        <header class="w-full bg-white border-b border-slate-200 px-6 py-4 sticky top-0 z-30 print:hidden shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-100">
                        <Icon icon="hugeicons:file-search-02" class="text-white text-2xl" />
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-800 tracking-tight">Report Generator</h1>
                        <div class="flex items-center gap-2">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-bold">Approved Submissions Database</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-2 px-2">
                        <FloatLabel variant="on" class="w-48">
                            <DatePicker v-model="start" dateFormat="yy-mm-dd" showIcon iconDisplay="input" class="w-full text-xs custom-datepicker" />
                            <label class="text-xs">Start Date</label>
                        </FloatLabel>
                        <span class="text-slate-300 font-light">—</span>
                        <FloatLabel variant="on" class="w-48">
                            <DatePicker v-model="end" :minDate="start" dateFormat="yy-mm-dd" showIcon iconDisplay="input" class="w-full text-xs custom-datepicker" />
                            <label class="text-xs">End Date</label>
                        </FloatLabel>
                    </div>

                    <div class="h-8 w-[1px] bg-slate-200 mx-1 hidden sm:block"></div>

                    <div class="flex items-center gap-2">
                        <Button @click="applyFilter" class="bg-indigo-600 hover:bg-indigo-700 border-none shadow-md shadow-indigo-100 px-6 transition-all active:scale-95">
                            <template #default>
                                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wide">
                                    <Icon icon="hugeicons:refresh-active" class="text-lg" />
                                    <span>Update Results</span>
                                </div>
                            </template>
                        </Button>
                        <Button @click="printReport" class="p-button-outlined p-button-secondary bg-white border-slate-200 hover:bg-slate-100 active:scale-95 transition-all">
                            <template #default>
                                <Icon icon="hugeicons:printer" class="text-lg text-slate-600" />
                            </template>
                        </Button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full px-6 py-8">
            
            <div v-if="reports.length" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8 print:hidden">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Submissions</p>
                    <p class="text-2xl font-black text-slate-800">{{ reports.length }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-indigo-500">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Coverage</p>
                    <p class="text-2xl font-black text-slate-800">{{ reports.reduce((acc, r) => acc + (r.total_clients || 0), 0) }} <small class="text-xs text-slate-400 font-normal">Clients</small></p>
                </div>
            </div>

            <div v-if="!reports.length" class="flex flex-col items-center justify-center py-32 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="p-6 bg-slate-50 rounded-full mb-4">
                    <Icon icon="hugeicons:folder-not-found" class="text-6xl text-slate-300" />
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Data Matches Your Range</h3>
                <p class="text-slate-400 max-w-xs text-center text-sm">Adjust the start or end dates above to fetch approved report submissions from the system.</p>
            </div>

            <div v-else class="space-y-10">
                <div v-for="report in reports" :key="report.id" class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden break-inside-avoid ring-1 ring-slate-100">
                    
                    <div class="p-8 border-b border-slate-100 flex flex-wrap justify-between items-center gap-6 bg-gradient-to-r from-white to-slate-50/50">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-100">
                                <Icon icon="hugeicons:location-04" class="text-white text-3xl" />
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ report.barangay?.name }}</h2>
                                <p class="flex items-center gap-2 text-xs text-slate-500 font-semibold uppercase tracking-wider mt-1">
                                    <Icon icon="hugeicons:calendar-03" />
                                    Submitted: {{ new Date(report.date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 lg:gap-8">
                            <div class="text-center px-6 border-r border-slate-100">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Reach</p>
                                <p class="text-2xl font-black text-slate-800">{{ report.total_clients }}</p>
                            </div>
                            <div class="text-center px-6 border-r border-slate-100">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Returning</p>
                                <p class="text-2xl font-black text-indigo-600">{{ report.total_returning_clients }}</p>
                            </div>
                            <div class="max-w-[200px]">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Health Personnel</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span v-for="user in report.users" :key="user.id" class="px-2.5 py-1 bg-white border border-slate-200 text-slate-700 text-[10px] font-bold rounded-lg shadow-sm">
                                        {{ user.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <tr v-for="value in report.values" :key="value.id" class="group hover:bg-slate-50/80 transition-all">
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

                    <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                        <div class="text-[10px] font-bold text-slate-300 italic">Report ID: #{{ report.id?.toString().padStart(6, '0') }}</div>
                        <div class="flex items-center gap-2">
                            <Icon icon="hugeicons:checkmark-circle-02" class="text-emerald-500 text-lg" />
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.15em]">Official Approved Record</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Styling for PrimeVue DatePicker to match the professional theme */
:deep(.custom-datepicker .p-inputtext) {
    border: none !important;
    background: transparent !important;
    font-weight: 700 !important;
}

@media print {
    /* Set page margins for full-width print */
    @page {
        size: auto;
        margin: 10mm;
    }
    
    body { background: white !important; }
    .bg-slate-50, .bg-slate-50\/50, .bg-slate-50\/80 { background: white !important; }
    .shadow-sm, .shadow-md, .shadow-xl { box-shadow: none !important; }
    .rounded-3xl { border-radius: 0 !important; }
    .border { border-color: #f1f5f9 !important; }
    
    /* Ensure tables don't break mid-row */
    table { page-break-inside: auto; width: 100% !important; }
    tr { page-break-inside: avoid; page-break-after: auto; }
}
</style>