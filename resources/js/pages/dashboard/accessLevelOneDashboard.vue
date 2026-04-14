<script setup>
import { computed, onMounted } from 'vue'
import Main from '@/layouts/main.vue'

defineOptions({ layout: Main })
const props = defineProps({provinces:Array})

onMounted(()=>{
    console.log(props.provinces)
})

const totalBarangays    = computed(() => props.provinces.reduce((sum, p) => sum + p.pk_coverage?.barangays_count, 0))
const totalMunicipalities = computed(() => props.provinces.reduce((sum, p) => sum + p.pk_coverage?.municipalities_count, 0))
const totalPkSites      = computed(() => props.provinces.reduce((sum, p) => sum + p.pk_coverage?.pk_sites_count, 0))


</script>

<template>
    <div class="p-6 flex flex-col gap-8 bg-slate-50 min-h-screen">

        <!-- Header -->
        <div class="flex items-end justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">CAR Region</p>
                <h1 class="text-3xl font-bold text-slate-900 mt-1">Regional Dashboard</h1>
                <!-- <p class="text-sm text-slate-400 mt-1">AY 2024 – 2025 · 7 Provinces</p> -->
            </div>
            <span class="text-xs text-slate-400 capitalize">Last updated: today</span>
        </div>

        <!-- Region Stat Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-1">
                <p class="text-xs font-medium uppercase tracking-widest text-slate-400">Provinces</p>
                <p class="text-4xl font-bold text-blue-600">{{ props.provinces.length }}</p>
                <!-- <p class="text-xs text-slate-400">Provinces Within Region  CAR</p> -->
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-1">
                <p class="text-xs font-medium uppercase tracking-widest text-slate-400">Total Municipalities</p>
                <p class="text-4xl font-bold text-slate-800">{{ totalMunicipalities }}</p>
                <!-- <p class="text-xs text-slate-400">across all provinces</p> -->
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col gap-1">
                <p class="text-xs font-medium uppercase tracking-widest text-slate-400">Total Barangays</p>
                <p class="text-4xl font-bold text-slate-800">{{ totalBarangays }}</p>
                <!-- <p class="text-xs text-slate-400">active sites</p> -->
            </div>
        </div>

        <!-- Section: PK Site Coverage -->
        <section class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">PK Site Coverage</h2>
                <div class="flex-1 h-px bg-slate-200" />
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-400 font-medium border-b border-slate-100">
                            <th class="text-left px-6 py-3">Province</th>
                            <th class="text-left px-6 py-3">Barangays</th>
                            <th class="text-left px-6 py-3">PK Sites</th>
                            <th class="text-left px-6 py-3 w-40">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="province in props.provinces" :key="province.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-slate-800 text-left">{{ province.name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ province.pk_coverage?.barangays_count }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ province.pk_coverage?.pk_sites_count }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ ((province.pk_coverage?.pk_sites_count/province.pk_coverage?.barangays_count)*100).toFixed(2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section: Org Indicators Monitoring -->
        <section class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">Orgnizational Indicators</h2>
                <div class="flex-1 h-px bg-slate-200" />
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-400 font-medium border-b border-slate-100">
                            <th class="text-left px-6 py-3">Province</th>
                            <th class="text-left px-6 py-3">With Organizational Indicators</th>
                            <th class="text-left px-6 py-3">Without Organizational Indicators</th>
                            <th class="text-left px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="province in props.provinces" :key="province.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ province.name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ province.org_indicator_status?.with_org_indicators }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ province.org_indicator_status?.without_org_indicators }}</td>
                            <td class="px-6 py-3 text-sm text-left" :class="province.org_indicator_status?.without_org_indicators === 0 ? 'text-green-600' : 'text-red-600'">{{ province.org_indicator_status?.without_org_indicators === 0 ? 'Complete' : 'Incomplete' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section: Priority Programs Monitoring -->
        <section class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">Priority Programs</h2>
                <div class="flex-1 h-px bg-slate-200" />
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-400 font-medium border-b border-slate-100">
                            <th class="text-left px-6 py-3">Province</th>
                            <th class="text-left px-6 py-3">With Priority Programs</th>
                            <th class="text-left px-6 py-3">Without Priority Programs</th>
                            <th class="text-left px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="province in props.provinces" :key="province.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ province.name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ province.priority_programs_status?.with_priority_programs }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-left">{{ province.priority_programs_status?.without_priority_programs }}</td>
                            <td class="px-6 py-3 text-sm text-left" :class="province.priority_programs_status?.without_priority_programs === 0 ? 'text-green-600' : 'text-red-600'">{{ province.priority_programs_status?.without_priority_programs === 0 ? 'Complete' : 'Incomplete' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Section: PK Clients Reached -->
        <!-- <section class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">PK Clients Reached</h2>
                <div class="flex-1 h-px bg-slate-200" />
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-400 font-medium border-b border-slate-100">
                            <th class="text-left px-6 py-3">Province</th>
                            <th class="text-right px-6 py-3">Target</th>
                            <th class="text-right px-6 py-3">Reached</th>
                            <th class="text-right px-6 py-3">Male</th>
                            <th class="text-right px-6 py-3">Female</th>
                            <th class="text-right px-6 py-3">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="p in provinces" :key="p.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ p.name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ p.pkTarget }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ p.pkReached }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ p.male }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ p.female }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full" :class="badgeClass(pct(p.pkReached, p.pkTarget))">
                                    {{ pct(p.pkReached, p.pkTarget) }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section> -->

        <!-- Section: Priority Programs -->
        <!-- <section class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">Priority Programs</h2>
                <div class="flex-1 h-px bg-slate-200" />
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-400 font-medium border-b border-slate-100">
                            <th class="text-left px-6 py-3">Province</th>
                            <th class="text-left px-6 py-3">Program</th>
                            <th class="text-right px-6 py-3">Beneficiaries</th>
                            <th class="text-right px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="row in priorityPrograms" :key="row.province" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ row.province }}</td>
                            <td class="px-6 py-3 text-sm text-slate-500">{{ row.program }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ row.beneficiaries }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full" :class="statusClass(row.status)">
                                    {{ row.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section> -->

        <!-- Section: Program Accomplishment -->
        <!-- <section class="flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-base font-bold text-slate-800">Program Accomplishment</h2>
                <div class="flex-1 h-px bg-slate-200" />
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-slate-400 font-medium border-b border-slate-100">
                            <th class="text-left px-6 py-3">Province</th>
                            <th class="text-right px-6 py-3">Target</th>
                            <th class="text-right px-6 py-3">Accomplished</th>
                            <th class="px-6 py-3 w-40">Progress</th>
                            <th class="text-right px-6 py-3">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="p in provinces" :key="p.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-slate-800">{{ p.name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ p.programTarget }}</td>
                            <td class="px-6 py-3 text-sm text-slate-400 text-right">{{ p.accomplished }}</td>
                            <td class="px-6 py-3">
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full" :class="barColor(pct(p.accomplished, p.programTarget))" :style="{ width: pct(p.accomplished, p.programTarget) + '%' }" />
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full" :class="badgeClass(pct(p.accomplished, p.programTarget))">
                                    {{ pct(p.accomplished, p.programTarget) }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section> -->

    </div>
</template>