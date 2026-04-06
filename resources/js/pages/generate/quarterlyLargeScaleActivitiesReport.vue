<script setup>
import { Icon } from '@iconify/vue'

const props = defineProps({
    barangays: { type: Array, default: () => [] },
})

const print = () => window.print()

const today = new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })
const quarter = Math.ceil((new Date().getMonth() + 1) / 3)
const year = new Date().getFullYear()

const allPrograms = props.barangays.length
    ? props.barangays[0]?.pk_activity?.programs ?? []
    : []
</script>

<template>
    <div class="min-h-screen bg-slate-100 print:bg-white">

        <!-- Screen-only toolbar -->
        <div class="print:hidden sticky top-0 z-10 bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-sm font-bold text-slate-800 leading-none">Quarterly Large Scale Activities Report</h1>
                <p class="text-xs text-slate-400 mt-0.5">Barangay programme coverage matrix · Q{{ quarter }} {{ year }}</p>
            </div>
            <button
                type="button"
                @click="print"
                class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold rounded-lg transition-colors uppercase tracking-wide"
            >
                <Icon icon="hugeicons:printer" class="text-sm" />
                Print / Save PDF
            </button>
        </div>

        <!-- Page body -->
        <div class="p-6 print:p-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 print:shadow-none print:border-0 print:rounded-none">

                <!-- ── Header ── -->
                <div class="px-8 pt-8 pb-6 border-b border-slate-200 print:px-6 print:pt-6">
                    <div class="flex items-start justify-between gap-6">

                        <!-- Left: seal + branding -->
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="w-12 h-12 rounded-full border-2 border-slate-300 bg-slate-50 flex items-center justify-center shrink-0">
                                <Icon icon="hugeicons:government" class="text-xl text-slate-400" />
                            </div>
                            <div class="leading-tight">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-600">Republic of the Philippines</p>
                                <p class="text-xs text-slate-400 mt-0.5">Department of Health</p>
                            </div>
                        </div>

                        <!-- Center: title -->
                        <div class="flex-1 text-center px-4">
                            <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Official Report</p>
                            <h1 class="text-base font-bold text-slate-800 uppercase tracking-wider leading-snug">
                                Quarterly Large Scale Activities Report
                            </h1>
                            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest">Barangay Programme Coverage Matrix</p>
                        </div>

                        <!-- Right: meta box -->
                        <div class="shrink-0 w-52 bg-slate-50 border border-slate-200 rounded-lg px-4 py-3">
                            <div class="text-xs space-y-1.5">
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-400">Period</span>
                                    <span class="font-semibold text-slate-700">Q{{ quarter }} · {{ year }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-400">Type</span>
                                    <span class="font-semibold text-slate-700">Large Scale</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-400">Generated</span>
                                    <span class="font-semibold text-slate-700">{{ today }}</span>
                                </div>
                                <div class="flex justify-between gap-2 pt-1.5 border-t border-slate-200">
                                    <span class="text-slate-400">Barangays</span>
                                    <span class="font-bold text-slate-800">{{ props.barangays.length }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Table (scrollable on screen, full-width on print) ── -->
                <div class="overflow-x-auto print:overflow-visible px-8 py-6 print:px-6">
                    <table class="border-collapse text-xs w-max print:w-full">
                        <thead>
                            <!-- Group row -->
                            <tr class="bg-slate-700 text-white">
                                <th colspan="2" class="text-center px-3 py-2 font-semibold uppercase tracking-wider text-xs border border-slate-600">
                                    Location
                                </th>
                                <th :colspan="allPrograms.length || 1" class="text-center px-3 py-2 font-semibold uppercase tracking-wider text-xs border border-slate-600">
                                    Programme Coverage
                                </th>
                            </tr>
                            <!-- Column labels -->
                            <tr class="bg-slate-800 text-white">
                                <th class="text-center px-3 py-2.5 font-semibold uppercase tracking-wider text-xs border border-slate-600 w-8">#</th>
                                <th class="text-left px-3 py-2.5 font-semibold uppercase tracking-wider text-xs border border-slate-600 w-40">Barangay</th>
                                <th
                                    v-for="program in allPrograms"
                                    :key="program.id"
                                    class="text-center px-3 py-2.5 font-semibold uppercase tracking-wider text-xs border border-slate-600 w-28"
                                >
                                    {{ program.name }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(barangay, index) in props.barangays"
                                :key="barangay.id"
                                :class="barangay.pk_activity?.type === 'large' ? 'bg-slate-100' : 'bg-emerald-100'"
                            >
                                <td class="px-3 py-2.5 text-slate-400 border border-slate-200 text-center align-middle w-8">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-3 py-2.5 border border-slate-200 align-middle w-40">
                                    <p class="font-semibold text-slate-800 leading-tight whitespace-nowrap">{{ barangay.barangay?.name ?? '—' }}</p>
                                    <p class="text-slate-500 leading-tight mt-0.5 whitespace-nowrap">{{ barangay.barangay?.municipality?.name ?? '—' }}</p>
                                    <p class="text-slate-400 leading-tight whitespace-nowrap">{{ barangay.barangay?.province?.name ?? '—' }}</p>
                                </td>
                                <td
                                    v-for="program in barangay.pk_activity?.programs ?? []"
                                    :key="program.id"
                                    class="px-3 py-2.5 text-center border border-slate-200 align-middle w-28"
                                >
                                    <span
                                        v-if="program.total === 1"
                                        class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 font-bold"
                                    >✓</span>
                                    <span v-else class="text-slate-200">·</span>
                                </td>
                            </tr>

                            <tr v-if="!props.barangays.length">
                                <td colspan="99" class="px-4 py-10 text-center text-slate-400 italic border border-slate-200">
                                    No records found for this quarter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ── Legend ── -->
                <div class="px-8 pb-5 print:px-6">
                    <div class="flex items-center gap-4 border-t border-slate-100 pt-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Legend</p>
                        <span class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs">✓</span>
                            Covered
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                            <span class="text-slate-300 text-sm leading-none">·</span>
                            Not covered
                        </span>
                    </div>
                </div>

                <!-- ── Signature Block ── -->
                <div class="px-8 pb-8 print:px-6 print:pb-6">
                    <div class="border-t-2 border-slate-800 pt-7 grid grid-cols-3 gap-12">
                        <div
                            v-for="role in ['Prepared by', 'Reviewed by', 'Approved by']"
                            :key="role"
                            class="flex flex-col items-center"
                        >
                            <div class="w-full h-14"></div>
                            <div class="w-full border-t border-slate-400"></div>
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-widest mt-2">{{ role }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">Signature over Printed Name / Date</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<style>
@media print {
    @page {
        margin: 1cm;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    table {
        width: 100% !important;
    }
}
</style>