<script setup>
    import Main from '@/layouts/main.vue'
    import { Icon } from '@iconify/vue'
    import { usePage } from '@inertiajs/vue3'
    import { ref } from 'vue'

    defineOptions({ layout: Main })
    const props = defineProps({
        program: Object,
        team: Object,
        programIndicators: Array,
    })

    const page = usePage()
    const activeTab = ref('program')

    const tabs = [
        { key: 'program', label: 'Programs', icon: 'hugeicons:book-open-01' },
        { key: 'team',    label: 'Teams',    icon: 'hugeicons:user-group'   },
        { key: 'Programmatic Indicators',    label: 'Programmatic Indicators',    icon: 'hugeicons:chart-bar'   },
        // { key: 'Organizational Indicators',    label: 'Organizational Indicators',    icon: 'hugeicons:chart-pie'   },
        // { key: 'PK Activities',    label: 'PK Activities',    icon: 'hugeicons:user-group'   },
        // { key: 'Barangays',    label: 'Barangays',    icon: 'hugeicons:user-group'   },
    ]

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
    <div class="h-full flex flex-col gap-5 min-h-0 overflow-y-auto pb-10">

        <!-- Welcome banner -->
        <div class="shrink-0 relative overflow-hidden rounded-2xl bg-indigo-600 px-6 py-5">
            <div class="absolute inset-0 opacity-10"
                style="background-image: linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            <div class="relative flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 ring-2 ring-white/30 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ initials(page.props.auth.user.name) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-indigo-200 text-xs font-medium">{{ greeting() }},</p>
                    <h1 class="text-white text-base font-bold leading-tight truncate">{{ page.props.auth.user.name }}</h1>
                    <p class="text-indigo-300 text-[11px] mt-0.5">Human Resources for Health</p>
                </div>
                <div class="hidden sm:flex items-center gap-1.5 bg-white/10 border border-white/20 rounded-lg px-3 py-1.5">
                    <Icon icon="hugeicons:calendar-03" class="text-indigo-200 text-sm" />
                    <span class="text-white text-xs font-medium">{{ today }}</span>
                </div>
            </div>
        </div>

        <!-- Tab bar -->
        <div class="shrink-0 flex items-center gap-1 bg-slate-100 p-1 rounded-xl w-fit">
            <button
                v-for="tab in tabs"
                :key="tab.key"
                type="button"
                @click="activeTab = tab.key"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-all"
                :class="activeTab === tab.key
                    ? 'bg-white text-slate-800 shadow-sm'
                    : 'text-slate-500 hover:text-slate-700'"
            >
                <Icon :icon="tab.icon" class="text-sm" />
                {{ tab.label }}
            </button>
        </div>

        <!-- ── Program Tab ─────────────────────────────────── -->
        <div v-if="activeTab === 'program'" class="flex flex-col gap-5">

            <!-- Stat cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <Icon icon="hugeicons:book-open-01" class="text-indigo-600 text-base" />
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.program.total }}</p>
                    <p class="text-xs text-slate-400 mt-1">Health programs</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <Icon icon="hugeicons:checkmark-circle-01" class="text-emerald-600 text-base" />
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Active</span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.program.active }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ props.program.inactive }} disabled</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                            <Icon icon="hugeicons:target-02" class="text-violet-600 text-base" />
                        </div>
                        <span class="px-2 py-0.5 bg-violet-50 text-violet-700 text-[10px] font-bold rounded-full border border-violet-100">
                            {{ Math.round((props.program.withBaseline / props.program.total) * 100) }}%
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.program.withBaseline }}</p>
                    <p class="text-xs text-slate-400 mt-1">With target baselines</p>
                </div>

                <div class="bg-slate-900 rounded-xl p-4 relative overflow-hidden group cursor-pointer">
                    <div class="relative z-10">
                        <p class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest mb-2">Quick action</p>
                        <p class="text-white text-sm font-bold leading-snug">Manage Programs</p>
                        <p class="text-slate-500 text-[11px] mt-2 group-hover:text-slate-300 transition-colors">
                            View and update indicators →
                        </p>
                    </div>
                    <Icon icon="hugeicons:flow-chart-02" class="absolute -right-2 -bottom-2 text-white/10 text-6xl rotate-12" />
                </div>

            </div>

            <!-- Program registry -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Program registry</h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">All registered health programs</p>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">{{ props.program.total }} total</span>
                </div>
                <div class="divide-y divide-slate-50">
                    <div
                        v-for="item in props.program.programs"
                        :key="item.id"
                        class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/60 transition-colors"
                    >
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                            <Icon icon="hugeicons:health-container" class="text-indigo-500 text-sm" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700 truncate">{{ item.name }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ item.indicators?.length || 0 }} indicators defined</p>
                        </div>
                        <div v-if="item.baseline" class="text-right shrink-0">
                            <p class="text-xs font-bold text-slate-700">{{ item.baseline.target }}</p>
                            <p class="text-[10px] text-slate-400 uppercase">Target</p>
                        </div>
                        <span v-else class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100 shrink-0">
                            No baseline
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── Team Tab ────────────────────────────────────── -->
        <div v-if="activeTab === 'team'" class="flex flex-col gap-5">

            <!-- Stat cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                            <Icon icon="hugeicons:user-group" class="text-indigo-600 text-base" />
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.team.total }}</p>
                    <p class="text-xs text-slate-400 mt-1">Teams</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <Icon icon="hugeicons:checkmark-circle-01" class="text-emerald-600 text-base" />
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Active</span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.team.activeTeams }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ props.team.total - props.team.activeTeams }} inactive</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                            <Icon icon="hugeicons:package-01" class="text-teal-600 text-base" />
                        </div>
                        <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-[10px] font-bold rounded-full border border-teal-100">
                            {{ Math.round((props.team.withKits / props.team.total) * 100) }}%
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.team.withKits }}</p>
                    <p class="text-xs text-slate-400 mt-1">With PK kits</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                            <Icon icon="hugeicons:file-verified-02" class="text-violet-600 text-base" />
                        </div>
                        <span class="px-2 py-0.5 bg-violet-50 text-violet-700 text-[10px] font-bold rounded-full border border-violet-100">
                            {{ Math.round((props.team.withEO / props.team.total) * 100) }}%
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-slate-800">{{ props.team.withEO }}</p>
                    <p class="text-xs text-slate-400 mt-1">With executive order</p>
                </div>

            </div>

            <!-- Geographic distribution -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800">Geographic distribution</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Teams by province</p>
                </div>
                <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div
                        v-for="p in props.team.provinceBreakdown"
                        :key="p.province"
                        class="p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all group"
                    >
                        <p class="text-[10px] font-bold text-slate-400 group-hover:text-indigo-500 uppercase tracking-wider mb-2 truncate transition-colors">
                            {{ p.province }}
                        </p>
                        <p class="text-xl font-bold text-slate-800">{{ p.totalTeams }}</p>
                        <div class="w-full bg-slate-200 h-1 rounded-full mt-2.5 overflow-hidden">
                            <div
                                class="bg-indigo-500 h-full rounded-full transition-all"
                                :style="{ width: (p.totalTeams / props.team.total * 100) + '%' }"
                            ></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5">
                            {{ Math.round(p.totalTeams / props.team.total * 100) }}% of total
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ── Team Tab ────────────────────────────────────── -->
        <div v-if="activeTab === 'Programmatic Indicators'" class="flex flex-col gap-5">
            <span class="text-sm bg-slate-200 text-center rounded-full animate-pulse">
                Current overview of active programmatic indicators and aggregated performance data. 
                Provincial distributions will be available once a sufficient volume of records has been localized.
            </span>
            <!-- Stat cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                <div
                    v-for="indicator in props.programIndicators"
                    :key="indicator.indicator_name"
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden"
                >
                    <!-- Card header -->
                    <div class="px-4 py-3.5 border-b border-slate-100 flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <Icon icon="hugeicons:chart-bar-line" class="text-indigo-500 text-sm" />
                            </div>
                            <p class="text-xs font-semibold text-slate-700 leading-snug">{{ indicator.indicator_name }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-lg font-bold text-slate-800 leading-none">{{ indicator.total }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Total</p>
                        </div>
                    </div>

                    <!-- Disaggregations -->
                    <div class="px-4 py-3 grid grid-cols-1 gap-x-4 gap-y-2.5">
                        <div
                            v-for="disaggregation in indicator.disaggregations"
                            :key="disaggregation.disaggregation_id"
                            class="flex items-center justify-between gap-2 py-1.5 border-b border-slate-50 last:border-0"
                        >
                            <p class="text-[11px] text-slate-500 truncate">{{ disaggregation.name }}</p>
                            <span class="text-[11px] font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded shrink-0">
                                {{ disaggregation.total_value }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</template>