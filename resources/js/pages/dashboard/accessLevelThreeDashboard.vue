<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { usePage } from '@inertiajs/vue3'

defineOptions({ layout: Main })
const props = defineProps({
    province: Object,
    teams: Array,
    totals: Object
})

const user = {
    name: 'Maria Santos',
    role: 'Field Officer',
}
const page = usePage()

// Helper to handle status styles consistently
const getStatusClass = (status) => status ? 'text-emerald-600' : 'text-slate-300'
</script>

<template>
    <div class="h-full flex flex-col gap-8 p-6 bg-slate-50 min-h-0 overflow-y-auto">

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="(val, label) in { Municipalities: 'municipalities', Barangays: 'barangays', HRH: 'hrh', Teams: 'teams' }" 
                 :key="label" 
                 class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 transition-transform hover:scale-[1.02]">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">{{ label }}</p>
                <p class="text-4xl font-black text-slate-900">{{ props.totals[val] }}</p>
            </div>
        </div>

        <section class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                        <Icon icon="lucide:map-pin" class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-bold text-slate-800">Barangays Status Tracker</h2>
                </div>
                <span class="text-xs text-slate-400 font-medium">Showing {{ props.province.barangays.length }} locations</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                                <th class="px-6 py-4">Barangay Details</th>
                                <th class="px-4 py-4 text-center">Org Indicators</th>
                                <th class="px-4 py-4 text-center">Pk Profile</th>
                                <th class="px-4 py-4 text-center">Geography</th>
                                <th class="px-4 py-4 text-center">Population</th>
                                <th class="px-4 py-4 text-center">Programs</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="brgy in props.province.barangays" :key="brgy.id" class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700">{{ brgy.name }}</div>
                                    <div class="text-xs text-slate-400">{{ brgy.municipality }}</div>
                                </td>
                                <td v-for="key in ['org_indicator', 'pk_profile', 'geography', 'population', 'priority_programs']" 
                                    :key="key" 
                                    class="px-4 py-4 text-center">
                                    <div class="flex justify-center">
                                        <Icon 
                                            :icon="brgy[key] ? 'lucide:check-circle-2' : 'lucide:clock-3'" 
                                            :class="['w-5 h-5', brgy[key] ? 'text-emerald-500' : 'text-slate-200 group-hover:text-amber-400']" 
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="flex flex-col gap-4 pb-10">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-rose-100 rounded-lg text-rose-600">
                    <Icon icon="lucide:users" class="w-5 h-5" />
                </div>
                <h2 class="text-lg font-bold text-slate-800">Teams Status Tracker</h2>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                            <th class="px-6 py-4">Team Name</th>
                            <th class="px-4 py-4 text-center">Brgy</th>
                            <th class="px-4 py-4 text-center">Members</th>
                            <th class="px-4 py-4 text-center">DOH Deployed</th>
                            <th class="px-4 py-4 text-center">PK Kit</th>
                            <th class="px-4 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="team in props.teams" :key="team.name" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ team.name }}</td>
                            <td class="px-4 py-4 text-center font-medium">{{ team.barangay_count }}</td>
                            <td class="px-4 py-4 text-center font-medium">{{ team.member_count }}</td>
                            <td class="px-4 py-4 text-center text-slate-500">{{ team.doh_deployed }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-1 rounded bg-slate-100 text-[10px] font-bold uppercase">{{ team.pk_kit }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span :class="[
                                    'px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter',
                                    team.active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'
                                ]">
                                    {{ team.active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</template>