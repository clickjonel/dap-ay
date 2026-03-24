<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, computed } from 'vue'
    import { useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import ManageOrgIndicatorModal from '@/pages/indicator/organizational/components/OrganizationalIndicatorModal.vue'

    const toast = useToast()
    defineOptions({ layout: Main })

    const props = defineProps({
        orgIndicators: { type: Array, default: () => [] },
    })

    // ── Search ─────────────────────────────────────────────
    const search = ref('')
    const filteredIndicators = computed(() =>
        props.orgIndicators.filter(p =>
            p.indicator_name.toLowerCase().includes(search.value.toLowerCase())
        )
    )

    // ── Modal ──────────────────────────────────────────────
    const indicatorModal = ref({ visible: false, indicator: null })

    const openCreate = () => {
        indicatorModal.value.indicator = null
        indicatorModal.value.visible   = true
    }

    const openEdit = (indicator) => {
        indicatorModal.value.indicator = indicator
        indicatorModal.value.visible   = true
    }

    const changeStatus = (indicator) => {
        useForm({}).delete(`/indicator/organizational/${indicator.id}`, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Status Updated',
                    detail: 'The status of the organizational indicator is changed successfully.',
                    life: 3000,
                })
            },
            preserveScroll: true,
        })
    }
</script>

<template>
    <div class="h-full flex flex-col gap-4 lg:gap-5">

        <!-- ── Page header ──────────────────────────────── -->
        <div class="flex items-start sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-base lg:text-lg font-bold text-slate-800 leading-none">Organizational Indicators</h1>
                <p class="text-xs text-slate-400 mt-1">Manage organizational indicators and their statuses.</p>
            </div>
            <button
                type="button"
                @click="openCreate"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">New Indicator</span>
                <span class="sm:hidden">New</span>
            </button>
        </div>

        <!-- ── Filters ───────────────────────────────────── -->
        <div class="flex items-center gap-3">
            <div class="relative flex-1 sm:max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search indicators..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400 shrink-0">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredIndicators.length }} <span class="hidden sm:inline">indicators</span></span>
            </div>
        </div>

        <!-- ── Mobile cards / Desktop table ──────────────── -->
        <div class="flex-1 min-h-0 overflow-y-auto">

            <!-- Mobile card list -->
            <div class="flex flex-col gap-3 md:hidden">

                <div v-if="filteredIndicators.length === 0" class="flex flex-col items-center gap-2 py-16">
                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                    <p class="text-sm font-medium text-slate-400">No indicators found</p>
                    <p class="text-xs text-slate-300">Try adjusting your search or create a new indicator.</p>
                </div>

                <div
                    v-for="indicator in filteredIndicators"
                    :key="indicator.id"
                    class="bg-white rounded-xl border border-slate-200 px-4 py-3.5 flex items-center gap-3"
                    :class="!indicator.is_active ? 'opacity-60' : ''"
                >
                    <!-- Icon -->
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:chart-increase" class="text-base text-indigo-500" />
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ indicator.indicator_name }}</p>
                        <span
                            class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                            :class="indicator.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                        >
                            <span class="w-1.5 h-1.5 rounded-full" :class="indicator.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                            {{ indicator.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button
                            type="button"
                            @click="openEdit(indicator)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                        >
                            <Icon icon="hugeicons:pencil-edit-01" class="text-sm" />
                        </button>
                        <button
                            type="button"
                            @click="changeStatus(indicator)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border transition-all"
                            :class="indicator.is_active
                                ? 'border-slate-200 text-slate-400 hover:border-red-300 hover:text-red-500 hover:bg-red-50'
                                : 'border-slate-200 text-slate-400 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50'"
                        >
                            <Icon :icon="indicator.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'" class="text-sm" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop table -->
            <div class="hidden md:block bg-white rounded-xl border border-slate-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 sticky top-0">
                        <tr>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Indicator Name</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

                        <tr v-if="filteredIndicators.length === 0">
                            <td colspan="4" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                    <p class="text-sm font-medium text-slate-400">No indicators found</p>
                                    <p class="text-xs text-slate-300">Try adjusting your search or create a new indicator.</p>
                                </div>
                            </td>
                        </tr>

                        <tr
                            v-for="(indicator, index) in filteredIndicators"
                            :key="indicator.id"
                            class="hover:bg-slate-50/70 transition-colors"
                            :class="!indicator.is_active ? 'opacity-60' : ''"
                        >
                            <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                                {{ String(index + 1).padStart(2, '0') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                        <Icon icon="hugeicons:chart-increase" class="text-sm text-indigo-500" />
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">{{ indicator.indicator_name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                    :class="indicator.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="indicator.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                                    {{ indicator.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        @click="openEdit(indicator)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        @click="changeStatus(indicator)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border transition-all"
                                        :class="indicator.is_active
                                            ? 'border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50'
                                            : 'border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50'"
                                    >
                                        <Icon :icon="indicator.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'" class="text-xs" />
                                        {{ indicator.is_active ? 'Disable' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>

        <ManageOrgIndicatorModal
            v-model:visible="indicatorModal.visible"
            :indicator="indicatorModal.indicator"
        />

    </div>
</template>