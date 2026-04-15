<script setup>
import { Icon } from '@iconify/vue'
import Button from 'primevue/button'
import FloatLabel from 'primevue/floatlabel'
import DatePicker from 'primevue/datepicker'
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

const props = defineProps({
    activities: { type: Array, default: () => [] },
    // totals: { type: Object, default: () => {} },
})
const now = new Date()
const filters = ref({
    start: new Date(now.getFullYear(), now.getMonth(), 1),
    end:   new Date(now.getFullYear(), now.getMonth() + 1, 0),
})

onMounted(()=>{
    console.log(props.activities)
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
    router.get('/generate/pk_activities', {
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

        <!-- Header -->
        <header class="w-full flex justify-between items-center print:hidden">
            <Button @click="router.visit('/pk-activities')" severity="help" outlined>
                <template #default>
                    <Icon icon="stash:arrow-left-solid" />
                </template>
            </Button>

            <div class="flex flex-col justify-center items-center gap-1">
                <h1 class="text-xl font-black text-slate-800 tracking-tight">PK Activities Generator</h1>
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] text-slate-400 uppercase tracking-[0.2em] font-bold">Consolidate PK Activities Submitted</p>
                </div>
            </div>

            <Button @click="print" severity="help" outlined>
                <template #default>
                    <Icon icon="hugeicons:printer"/>
                </template>
            </Button>
        </header>

        <!-- Filters -->
        <section class="w-full flex justify-between items-center gap-2 print:hidden bg-white rounded-2xl border border-slate-200 px-4 py-3">
            <div class="flex items-center gap-3">
                <FloatLabel variant="on" class="w-48">
                    <DatePicker v-model="filters.start" dateFormat="yy-mm-dd" showIcon iconDisplay="input" class="w-full text-xs" />
                    <label class="text-xs">Start Date</label>
                </FloatLabel>
                <span class="text-slate-300 font-light">—</span>
                <FloatLabel variant="on" class="w-48">
                    <DatePicker v-model="filters.end" :minDate="filters.start" dateFormat="yy-mm-dd" showIcon iconDisplay="input" class="w-full text-xs" />
                    <label class="text-xs">End Date</label>
                </FloatLabel>
                <Button @click="clearFilters" label="Clear" severity="secondary" size="small" outlined />
            </div>
            <Button @click="applyFilter" label="Get Activities" size="small" />
        </section>

        <!-- Grid Table -->
        <section class="w-full flex flex-col justify-start items-start bg-white rounded-2xl border border-slate-200 overflow-hidden">

            <div class="w-full grid grid-cols-7 bg-slate-50 border-b border-slate-200 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <span class="col-span-2 px-4 py-3 border-r border-slate-200">Activity</span>
                <span class="col-span-2 px-4 py-3 border-r border-slate-200">Programs</span>
                <span class="col-span-3 px-4 py-3">Barangays</span>
            </div>

            <div v-if="!activities || activities.length === 0" class="w-full py-16 flex flex-col items-center gap-2">
                <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                <p class="text-sm text-slate-400">No activities found</p>
            </div>

            <div
                v-for="activity in activities"
                :key="activity.id"
                class="w-full grid grid-cols-7 border-b border-slate-100 hover:bg-slate-50 transition-colors"
            >
                <div class="col-span-2 px-4 py-3 flex flex-col gap-0.5 border-r border-slate-100">
                    <span class="text-sm font-semibold text-slate-800">{{ activity.activity_name }}</span>
                    <span class="text-xs text-slate-400">{{ formatDate(activity.date_start) }} — {{ formatDate(activity.date_end) }}</span>
                    <span
                        class="text-[10px] font-medium px-2 py-0.5 rounded-full w-fit capitalize mt-1"
                        :class="activity.type === 'large scale' ? 'bg-indigo-100 text-indigo-600' : 'bg-emerald-100 text-emerald-600'"
                    >
                        {{ activity.type }}
                    </span>
                </div>
                <div class="col-span-2 px-4 py-3 flex flex-col gap-0.5 border-r border-slate-100">
                    <span v-for="prog in activity.programs" :key="prog.id" class="text-xs text-slate-600">{{ prog.name }}</span>
                </div>
                <div class="col-span-3 px-4 py-3 flex flex-col gap-0.5">
                    <span v-for="brgy in activity.barangays" :key="brgy.id" class="text-xs text-slate-600">
                        {{ brgy.name }} · {{ brgy.municipality.name }}, {{ brgy.province.name }}
                    </span>
                </div>
            </div>

        </section>

    </div>
</template>
