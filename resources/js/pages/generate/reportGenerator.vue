<script setup>
import { Icon } from '@iconify/vue'
import Button from 'primevue/button'
import FloatLabel from 'primevue/floatlabel'
import DatePicker from 'primevue/datepicker'
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

const props = defineProps({
    reports: { type: Array, default: () => [] },
    totals: { type: Object, default: () => {} },
})
const now = new Date()
const filters = ref({
    start: new Date(now.getFullYear(), now.getMonth(), 1),
    end:   new Date(now.getFullYear(), now.getMonth() + 1, 0),
})

onMounted(()=>{
    console.log(props.reports)
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'Asia/Manila'
    })
}

const applyFilter = () => {
    router.get('/generate/report', {
        start: filters.value.start?.toISOString().split('T')[0],
        end: filters.value.end?.toISOString().split('T')[0]
    }, { preserveState: true })
}

const clearFilters = () => {
    filters.value = {
        start: new Date(now.getFullYear(), now.getMonth(), 1),
        end:   new Date(now.getFullYear(), now.getMonth() + 1, 0),
    }
    applyFilter()
}

const print = () => {
    window.print()
}

</script>

<template>
    <div class="min-h-screen bg-slate-100 print:bg-white p-2 flex flex-col justify-start items-start gap-4">

        <header class="w-full flex justify-between items-center print:hidden">
            <Button @click="router.visit('/reports')" severity="help" outlined>
                <template #default>
                    <Icon icon="stash:arrow-left-solid" />
                </template>
            </Button>

            <div class="flex flex-col justify-center items-center gap-2">
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Report Generator</h1>
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-bold">Consolidate Reports Submitted and Approved</p>
                </div>
            </div> 

            <Button @click="print" severity="help" outlined>
                <template #default>
                    <Icon icon="hugeicons:printer"/>
                </template>
            </Button>
        </header>

        <section class="w-full flex justify-between items-center gap-2 print:hidden">
            <div class="flex items-center gap-2 px-2">
                <FloatLabel variant="on" class="w-48">
                    <DatePicker v-model="filters.start" dateFormat="yy-mm-dd" showIcon iconDisplay="input" class="w-full text-xs" />
                    <label class="text-xs">Start Date</label>
                </FloatLabel>
                <span class="text-slate-300 font-light">—</span>
                <FloatLabel variant="on" class="w-48">
                    <DatePicker v-model="filters.end" :minDate="filters.start" dateFormat="yy-mm-dd" showIcon iconDisplay="input" class="w-full text-xs" />
                    <label class="text-xs">End Date</label>
                </FloatLabel>
                <Button @click="clearFilters" label="Clear Filters" severity="info"/>
            </div>
            <Button @click="applyFilter" label="Get Reports"/>
        </section>

        <section class="w-full flex justify-center items-center">
            <div class="w-full bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Reporting Period</p>
                <p class="text-2xl font-black text-slate-800">
                    {{ formatDate(filters.start) }} — {{ formatDate(filters.end) }}
                </p>
            </div>
        </section>

        <section class="w-full grid grid-cols-5 gap-2">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Submissions</p>
                <p class="text-2xl font-black text-slate-800">{{ props.totals.total_submissions }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Clients</p>
                <p class="text-2xl font-black text-slate-800">{{ props.totals.total_clients }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Returning Clients</p>
                <p class="text-2xl font-black text-slate-800">{{ props.totals.total_returning_clients }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Barangays</p>
                <p class="text-2xl font-black text-slate-800">{{ props.totals.total_barangays }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Submitters</p>
                <p class="text-2xl font-black text-slate-800">{{ props.totals.total_users }}</p>
            </div>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-4">
            <!-- <div class="inset w-full h-screen flex justify-center items-center">
                <span>Loading</span>
            </div> -->
            <div v-for="report in props.reports" class="w-full flex flex-col justify-start items-start bg-white rounded-xl shadow-md">
                <div class="w-full flex justify-between items-center border-b border-gray-400 p-2">
                    <div class="flex flex-col">
                        <div class="flex flex-col justify-start items-start">
                            <span class="text-xl uppercase font-bold">{{ report.barangay.name }}</span>
                            <div class="flex justify-start items-start gap-2">
                                <span class="text-sm text-gray-500">{{ report.barangay.municipality.name }}, </span>
                                <span class="text-sm text-gray-500">{{ report.barangay.province.name }}</span>
                            </div>
                        </div>
                        <span class="uppercase text-sm text-gray-500">Submitted: {{ formatDate(report.date) }}</span>
                    </div>
                    <div class="flex justify-center items-start gap-2 divide-x-2 divide-gray-300">
                        <div class="flex flex-col justify-start items-center p-2">
                            <span class="font-semibold uppercase">Clients:</span>
                            <span class="text-xl text-gray-500">{{ report.total_clients }}</span>
                        </div>
                        <div class="flex flex-col justify-start items-center p-2">
                            <span class="font-semibold uppercase">Returning:</span>
                            <span class="text-xl text-gray-500">{{ report.total_returning_clients }}</span>
                        </div>
                        <div class="flex flex-col justify-start items-center p-2">
                            <span class="font-semibold uppercase">Submitted By:</span>
                            <span v-for="user in report.users" class="font-light text-xs text-gray-500">{{ user.name }}</span>
                        </div>
                    </div>
                </div>

                <div class="w-full flex flex-col justify-start items-start divide-y border border-gray-100 divide-gray-100">
                    <div class="w-full grid grid-cols-6 flex justify-center items-center bg-sky-100 font-bold uppercase">
                        <span class="col-span-3 p-2 text-center">Indicator</span>
                        <span class="col-span-1 p-2 text-center">Total</span>
                        <span class="col-span-2 p-2 text-left">Disaggregations</span>
                    </div>

                    <div v-for="value in report.values" class="w-full grid grid-cols-6 flex justify-center items-stretch bg-white-100">
                        <span class="col-span-3 text-center flex justify-center items-center text-sm">{{ value.indicator.indicator_name }}</span>
                        <span class="col-span-1 flex justify-center items-center text-xl font-bold">{{ value.total }}</span>
                        <div class="col-span-2 flex flex-col justify-start items-start p-2 text-xs">
                            <span v-for="disaggregation in value.disaggregations">{{ `${disaggregation.disaggregation.disaggregation_name}: ${disaggregation.value}` }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </section>

    </div>
</template>
