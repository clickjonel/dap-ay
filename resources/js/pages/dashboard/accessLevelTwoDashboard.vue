<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { usePage } from '@inertiajs/vue3'

defineOptions({ layout: Main })

const props = defineProps({
    teams:     { type: Array,  default: () => [] },
    barangays: { type: Array,  default: () => [] },
    reports:   { type: Array,  default: () => [] },
    stats:     { type: Object, default: () => ({}) },
})

const page = usePage()
const initials = (name) => name?.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase()
const greeting = () => {
    const h = new Date().getHours()
    if (h < 12) return 'Good morning'
    if (h < 18) return 'Good afternoon'
    return 'Good evening'
}
const today = new Date().toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' })
</script>

<template>
    <div class="h-full flex flex-col gap-6 min-h-0 overflow-y-auto">

        <!-- ── Welcome banner ─────────────────────────────── -->
        <div class="shrink-0 relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 px-6 py-5">
            <div class="absolute inset-0 opacity-10"
                style="background-image: linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 ring-2 ring-white/30 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ initials(page.props.auth.user.name) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-indigo-200 text-xs font-medium">{{ greeting() }},</p>
                    <h1 class="text-white text-lg font-bold leading-tight truncate">{{ page.props.auth.user.name }}</h1>
                    <p class="text-indigo-300 text-[11px] mt-0.5">Human Resources for Health</p>
                </div>
                <div class="hidden sm:flex items-center gap-1.5 bg-white/10 border border-white/20 rounded-lg px-3 py-1.5">
                    <Icon icon="hugeicons:calendar-03" class="text-indigo-200 text-sm" />
                    <span class="text-white text-xs font-medium">{{ today }}</span>
                </div>
            </div>
        </div>

        <!-- ── Stat cards ──────────────────────────────────── -->
        <div class="shrink-0 grid grid-cols-2 lg:grid-cols-4 gap-3">

            <div class="bg-white rounded-xl border border-slate-200 px-4 py-4 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Reports</span>
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <Icon icon="hugeicons:file-02" class="text-indigo-500 text-sm" />
                    </span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 leading-none">
                        {{ props.stats.totalReports ?? '—' }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">All time submitted</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 px-4 py-4 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">This Month</span>
                    <span class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <Icon icon="hugeicons:chart-up" class="text-emerald-500 text-sm" />
                    </span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 leading-none">
                        {{ props.stats.totalThisMonth ?? '—' }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">Reports this month</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 px-4 py-4 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Pending</span>
                    <span class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                        <Icon icon="hugeicons:clock-01" class="text-amber-500 text-sm" />
                    </span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 leading-none">
                        {{ props.stats.totalPending ?? '—' }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">Awaiting review</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 px-4 py-4 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Approved</span>
                    <span class="w-7 h-7 rounded-lg bg-sky-50 flex items-center justify-center">
                        <Icon icon="hugeicons:checkmark-circle-02" class="text-sky-500 text-sm" />
                    </span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800 leading-none">
                        {{ props.stats.totalApproved ?? '—' }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">This month approved</p>
                </div>
            </div>

        </div>

        <!-- ── Main grid ───────────────────────────────────── -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-4 min-h-0">

            <!-- Recent Reports -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 flex flex-col min-h-0">
                <div class="px-5 py-3.5 flex items-center gap-2 border-b border-slate-100 shrink-0">
                    <Icon icon="hugeicons:file-search" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Recent Reports</span>
                    <span class="ml-auto text-[11px] text-indigo-500 font-medium cursor-pointer hover:underline">View all</span>
                </div>

                <!-- Empty state -->
                <div v-if="!props.reports.length" class="flex-1 flex flex-col items-center justify-center gap-2 py-12 text-slate-400">
                    <Icon icon="hugeicons:file-search" class="text-3xl opacity-30" />
                    <p class="text-xs">No reports submitted yet.</p>
                </div>

                <div v-else class="flex-1 overflow-y-auto divide-y divide-slate-50">
                    <div
                        v-for="report in props.reports"
                        :key="report.id"
                        class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/60 transition-colors cursor-pointer"
                    >
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                            <Icon icon="hugeicons:file-02" class="text-indigo-400 text-sm" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ report.date ?? '—' }} PK Report</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <Icon icon="hugeicons:maps-location-02" class="text-[10px] text-slate-400" />
                                <span class="text-[11px] text-slate-400">
                                    {{ report.barangay?.municipality?.name ?? 'No municipality' }}
                                </span>
                                <span class="text-slate-200">·</span>
                                <span class="text-[11px] text-slate-500">
                                    {{ report.barangay?.name ?? 'No barangay' }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full shrink-0 bg-slate-100 text-slate-400 ring-1 ring-slate-200">
                            No Status
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="flex flex-col gap-4 min-h-0">

                <!-- Teams -->
                <div class="bg-white rounded-xl border border-slate-200 flex flex-col shrink-0">
                    <div class="px-5 py-3.5 flex items-center gap-2 border-b border-slate-100">
                        <Icon icon="hugeicons:user-group" class="text-sm text-slate-400" />
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">My Teams</span>
                        <span class="ml-auto text-[11px] text-slate-400">{{ props.teams.length }} team{{ props.teams.length !== 1 ? 's' : '' }}</span>
                    </div>

                    <!-- Empty state -->
                    <div v-if="!props.teams.length" class="flex flex-col items-center justify-center gap-2 py-8 text-slate-400">
                        <Icon icon="hugeicons:user-group" class="text-2xl opacity-30" />
                        <p class="text-xs">Not assigned to any team.</p>
                    </div>

                    <div v-else class="divide-y divide-slate-50">
                        <div
                            v-for="team in props.teams"
                            :key="team.id"
                            class="px-5 py-3 flex items-center gap-3 hover:bg-slate-50/60 transition-colors"
                        >
                            <div class="w-2 h-2 rounded-full shrink-0 bg-sky-400" />
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 truncate uppercase">
                                    {{ team.name ?? 'Unnamed Team' }}
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    {{ team.pk_kit ? 'PK Kit Assigned' : 'No PK Kit Assigned' }}
                                </p>
                            </div>
                            <span
                                v-if="team.role"
                                class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 bg-sky-50 text-sky-600 ring-1 ring-sky-200"
                            >
                                {{ team.role }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Barangays -->
                <div class="bg-white rounded-xl border border-slate-200 flex flex-col flex-1 min-h-0">
                    <div class="px-5 py-3.5 flex items-center gap-2 border-b border-slate-100 shrink-0">
                        <Icon icon="hugeicons:maps-location-02" class="text-sm text-slate-400" />
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Barangays Handled</span>
                        <span class="ml-auto text-[11px] text-slate-400">{{ props.barangays.length }}</span>
                    </div>

                    <!-- Empty state -->
                    <div v-if="!props.barangays.length" class="flex-1 flex flex-col items-center justify-center gap-2 py-8 text-slate-400">
                        <Icon icon="hugeicons:maps-location-02" class="text-2xl opacity-30" />
                        <p class="text-xs">No barangays assigned yet.</p>
                    </div>

                    <div v-else class="flex-1 overflow-y-auto divide-y divide-slate-50">
                        <div
                            v-for="brgy in props.barangays"
                            :key="brgy.id"
                            class="px-5 py-3 flex items-center gap-3 hover:bg-slate-50/60 transition-colors"
                        >
                            <div class="w-6 h-6 rounded-md bg-indigo-50 flex items-center justify-center shrink-0">
                                <Icon icon="hugeicons:maps-location-02" class="text-indigo-400 text-xs" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 truncate uppercase">
                                    {{ brgy.name ?? 'Unnamed Barangay' }}
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    {{ brgy.municipality?.name ?? 'No municipality' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>