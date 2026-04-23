<script setup>
    import { Button } from 'primevue'
    import { Icon } from '@iconify/vue'
    import { router } from '@inertiajs/vue3'

    defineProps({
        indicators: Array
    })

    const print = () => {
        window.print()
    }
</script>

<template>
    <main class="w-full h-full flex flex-col gap-5 p-6 overflow-auto bg-slate-50">

        <!-- Header -->
        <header class="w-full flex justify-between items-center print:hidden">
            <Button @click="router.visit('/reports')" severity="secondary" outlined size="small">
                <template #default>
                    <Icon icon="stash:arrow-left-solid" />
                </template>
            </Button>
            <Button @click="print" severity="secondary" outlined size="small">
                <template #default>
                    <Icon icon="hugeicons:printer" />
                </template>
            </Button>
        </header>

        <!-- Form Card -->
        <div class="w-full bg-white border border-slate-200 rounded-xl overflow-hidden text-xs print:border-none print:rounded-none print:shadow-none">

            <!-- Banner -->
            <div class="bg-[#1e3a5f] px-6 py-4 flex items-start justify-between rounded-t-xl">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-blue-300 uppercase tracking-widest font-medium">
                        Department of Health — Field Report
                    </span>
                    <h2 class="text-sm font-semibold text-white">Report Form</h2>
                    <p class="text-[11px] text-blue-300">Purokalusugan Program · Put data on availability of it. </p>
                </div>
                <span class="text-[11px] text-blue-300 bg-white/10 px-3 py-1 rounded shrink-0">Reference Report</span>
            </div>

            <!-- Site Visit Details -->
            <div class="px-6 py-4 border-b border-slate-100">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
                    Site visit details
                    <span class="flex-1 border-t border-slate-100" />
                </p>

                <div class="border border-slate-200 rounded-lg overflow-hidden divide-y divide-slate-100">
                    <div class="grid grid-cols-2 divide-x divide-slate-100">
                        <div class="grid grid-cols-[130px_1fr] divide-x divide-slate-100">
                            <span class="px-3 py-2.5 text-slate-400 bg-slate-50 flex items-center">Date of Visit</span>
                            <span class="px-3 py-2.5 min-h-[36px] border-b border-dotted border-slate-300"></span>
                        </div>
                        <div class="grid grid-cols-[130px_1fr] divide-x divide-slate-100">
                            <span class="px-3 py-2.5 text-slate-400 bg-slate-50 flex items-center">Barangay</span>
                            <span class="px-3 py-2.5 min-h-[36px] border-b border-dotted border-slate-300"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-slate-100">
                        <div class="grid grid-cols-[130px_1fr] divide-x divide-slate-100">
                            <span class="px-3 py-2.5 text-slate-400 bg-slate-50 flex items-center">Total Clients</span>
                            <span class="px-3 py-2.5 min-h-[36px] border-b border-dotted border-slate-300"></span>
                        </div>
                        <div class="grid grid-cols-[130px_1fr] divide-x divide-slate-100">
                            <span class="px-3 py-2.5 text-slate-400 bg-slate-50 flex items-center">Returning Clients</span>
                            <span class="px-3 py-2.5 min-h-[36px] border-b border-dotted border-slate-300"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-[130px_1fr] divide-x divide-slate-100">
                        <span class="px-3 py-2.5 text-slate-400 bg-slate-50 flex items-center">Submitted by</span>
                        <span class="px-3 py-2.5 min-h-[36px] border-b border-dotted border-slate-300"></span>
                    </div>
                </div>
            </div>

            <!-- Indicators -->
            <div class="px-6 py-4 border-b border-slate-100">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
                    Program indicators & disaggregations
                    <span class="flex-1 border-t border-slate-100" />
                </p>

                <div class="flex flex-col gap-3">
                    <div
                        v-for="indicator in indicators"
                        :key="indicator.id"
                        class="border border-slate-200 rounded-lg overflow-hidden"
                    >
                        <!-- Indicator Header -->
                        <div class="grid divide-x divide-slate-200"
                            :style="{ gridTemplateColumns: '1fr 110px' }">
                            <div class="px-4 py-3 bg-slate-50">
                                <p class="text-[10px] text-slate-400 mb-0.5">Indicator name</p>
                                <p class="text-[12px] font-semibold text-slate-700">{{ indicator.indicator_name }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ indicator.program?.name ?? '' }}</p>
                            </div>
                            <div class="px-4 py-3 flex flex-col items-center justify-center gap-1.5">
                                <p class="text-[10px] text-slate-400">Total count</p>
                                <div class="w-full border-b-2 border-dotted border-slate-400 min-h-[20px]"></div>
                            </div>
                        </div>

                        <!-- Disaggregations -->
                        <div
                            v-if="indicator.disaggregations?.length"
                            class="border-t border-slate-200 divide-x divide-slate-200"
                            :class="[
                                'grid',
                                indicator.disaggregations.length <= 2 ? 'grid-cols-2' :
                                indicator.disaggregations.length === 3 ? 'grid-cols-3' : 'grid-cols-4'
                            ]"
                        >
                            <div
                                v-for="dis in indicator.disaggregations"
                                :key="dis.id"
                                class="px-3 py-2.5 flex flex-col gap-2"
                            >
                                <span class="text-[10px] text-slate-400">{{ dis.disaggregation_name }}</span>
                                <div class="w-full border-b-2 border-dotted border-slate-400 min-h-[18px]"></div>
                            </div>
                        </div>

                        <!-- No disaggregations -->
                        <div v-else class="border-t border-slate-100 px-4 py-2 bg-slate-50/50">
                            <span class="text-[10px] text-slate-300 italic">No disaggregations</span>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div v-if="!indicators?.length" class="border border-dashed border-slate-200 rounded-lg py-8 flex flex-col items-center gap-2">
                        <Icon icon="hugeicons:folder-02" class="text-slate-300 text-2xl" />
                        <p class="text-[11px] text-slate-300">No indicators assigned</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3 bg-slate-50 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <p class="text-[11px] text-slate-400">
                    This is a reference form only for encoding on the DAP-AY IS.
                </p>
            </div>

        </div>

    </main>
</template>

<style>
    @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background: white !important;
        }
    }
</style>