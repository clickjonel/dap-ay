<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, computed } from 'vue'
    import { useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import { router } from '@inertiajs/vue3'
    import Dialog from 'primevue/dialog';
    import ToggleSwitch from 'primevue/toggleswitch'
    import ProgramIndicatorModal from '@/pages/indicator/program/components/ProgramIndicatorModal.vue'

    const toast = useToast()
    defineOptions({ layout: Main })

    const props = defineProps({
        programIndicators: { 
            type: Array, 
            default: () => [] 
        },
        programs:{ 
            type: Array,
             default: () => [] 
        },
        disaggregations: {
            type: Array,
            default: () => []
        },
    })

    // ── Search / filter ────────────────────────────────────
    const search = ref('')
    const filteredIndicators = computed(() =>
        props.programIndicators.filter(p =>
            p.indicator_name.toLowerCase().includes(search.value.toLowerCase())
        )
    )

    // ── Modal ──────────────────────────────────────────────
    const indicatorModal = ref({
        visible:   false,
        indicator: null,
    })

    const disaggregationsModal = ref({
        visible:   false,
        indicator: null,
    })

    const disaggregationForm = useForm({
        program_indicator_id: null,
        disaggregation_ids: [],
    })

    const groupedDisaggregations = computed(() => {
        return props.disaggregations.reduce((acc, d) => {
            const key = d.group ?? 'Uncategorized'
            if (!acc[key]) acc[key] = []
            acc[key].push(d)
            return acc
        }, {})
    })

    const openCreate = () => {
        indicatorModal.value.indicator = null
        if (props.programs.length === 0) {
            router.reload({
                only: ['programs'],
                preserveScroll: true,
                onSuccess: () => { indicatorModal.value.visible = true },
            })
        } else {
            indicatorModal.value.visible = true
        }
    }

    const openEdit = (indicator) => {
        indicatorModal.value.indicator = indicator
        if (props.programs.length === 0) {
            router.reload({
                only: ['programs'],
                preserveScroll: true,
                onSuccess: () => { indicatorModal.value.visible = true },
            })
        } else {
            indicatorModal.value.visible = true
        }
    }

    const openDisaggregationsModal = (indicator) => {
        disaggregationsModal.value.indicator = indicator
        disaggregationForm.program_indicator_id = indicator.id
        disaggregationForm.disaggregation_ids = indicator.disaggregations?.map(d => d.id) ?? []

        if (props.disaggregations.length === 0) {
            router.reload({
                only: ['disaggregations'],
                preserveScroll: true,
                onSuccess: () => { disaggregationsModal.value.visible = true },
            })
        } else {
            disaggregationsModal.value.visible = true
        }
    }

    const submitDisaggregations = () => {
        disaggregationForm.post('/program-indicator-disaggregation', {
            preserveScroll: true,
            onSuccess: () => {
                disaggregationsModal.value.visible = false
                toast.add({
                    severity: 'success',
                    summary:  'Saved',
                    detail:   'Disaggregations updated successfully.',
                    life:     2000,
                })
            },
        })
    }

    // ── Toggle status ──────────────────────────────────────
    const changeStatus = (indicator) => {
        useForm({}).delete(`/indicator/program/${indicator.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary:  'Status Updated',
                    detail:   `Indicator has been ${indicator.is_active ? 'disabled' : 'activated'}.`,
                    life:     2000,
                })
            },
        })
    }
</script>

<template>
    <div class="h-full flex flex-col gap-5">

        <!-- ── Page header ──────────────────────────────── -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Program Indicators</h1>
                <p class="text-xs text-slate-400 mt-1">Manage Program indicators and their statuses.</p>
            </div>
            <button
                type="button"
                @click="openCreate"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                New Indicator
            </button>
        </div>

        <!-- ── Filters ───────────────────────────────────── -->
        <div class="flex items-center gap-3">
            <div class="relative flex-1 max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search indicators..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>

            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredIndicators.length }} indicators</span>
            </div>
        </div>

        <!-- ── Table ─────────────────────────────────────── -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                    <tr>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Indicator Name</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Program</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Scope</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    <!-- Empty state -->
                    <tr v-if="filteredIndicators.length === 0">
                        <td colspan="5" class="px-5 py-16 text-center">
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
                        <!-- Index -->
                        <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                            {{ String(index + 1).padStart(2, '0') }}
                        </td>

                        <!-- Indicator Name -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                    <Icon icon="hugeicons:book-open-01" class="text-sm text-indigo-500" />
                                </div>
                                <span class="text-sm font-light text-slate-700">{{ indicator.indicator_name }}</span>
                            </div>
                        </td>

                        <!-- Program -->
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 rounded-full text-[11px] font-medium text-indigo-600">
                                {{ indicator.program?.name ?? '—' }}
                            </span>
                        </td>

                        <!-- Scope -->
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 rounded-full text-[11px] font-medium text-indigo-600">
                                {{ indicator.scope }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-3.5">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                :class="indicator.is_active
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : 'bg-slate-100 text-slate-500'"
                            >
                                <span
                                    class="w-1.5 h-1.5 rounded-full"
                                    :class="indicator.is_active ? 'bg-emerald-500' : 'bg-slate-400'"
                                />
                                {{ indicator.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                <button
                                    type="button"
                                    @click="openDisaggregationsModal(indicator)"
                                    class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                >
                                    <Icon icon="hugeicons:filter-horizontal" class="text-xs" />
                                    Set Disaggregations
                                </button>

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
                                    <Icon
                                        :icon="indicator.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'"
                                        class="text-xs"
                                    />
                                    {{ indicator.is_active ? 'Disable' : 'Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- ── Modals ──────────────────────────────────────── -->
        <ProgramIndicatorModal
            v-model:visible="indicatorModal.visible"
            :indicator="indicatorModal.indicator"
            :programs="props.programs"
        />

        <!-- Disaggregations Modal -->
        <Dialog
            modal
            append-to="body"
            v-model:visible="disaggregationsModal.visible"
            :style="{ width: '480px' }"
            :pt="{
                header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
                title:   { class: '!text-sm !font-bold !text-slate-800' },
                content: { class: '!p-5' },
            }"
        >
            <template #header>
                <div class="flex flex-col gap-0.5">
                    <span class="text-sm font-bold text-slate-800">Set Disaggregations</span>
                    <span class="text-[11px] text-slate-400 font-normal">{{ disaggregationsModal.indicator?.indicator_name }}</span>
                </div>
            </template>

            <form @submit.prevent="submitDisaggregations" class="flex flex-col gap-5">

                <!-- Empty state -->
                <div v-if="props.disaggregations.length === 0" class="flex flex-col items-center gap-2 py-8">
                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                    <p class="text-sm text-slate-400">No disaggregations available.</p>
                </div>

                <!-- Grouped disaggregations -->
                <div
                    v-for="(items, group) in groupedDisaggregations"
                    :key="group"
                    class="flex flex-col gap-2"
                >
                    <!-- Group label -->
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                        <span>{{ group }}</span>
                        <span class="flex-1 border-t border-slate-100" />
                    </p>

                    <!-- Checkboxes -->
                    <div class="grid grid-cols-2 gap-1.5">
                        <label
                            v-for="d in items"
                            :key="d.id"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg border cursor-pointer transition-all select-none"
                            :class="disaggregationForm.disaggregation_ids.includes(d.id)
                                ? 'border-indigo-300 bg-indigo-50'
                                : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
                        >
                            <input
                                type="checkbox"
                                :value="d.id"
                                v-model="disaggregationForm.disaggregation_ids"
                                class="w-3.5 h-3.5 rounded accent-indigo-600"
                            />
                            <span class="text-xs text-slate-700">{{ d.disaggregation_name }}</span>
                        </label>
                    </div>
                </div>

                <!-- Selected count -->
                <p class="text-[11px] text-slate-400 -mt-2">
                    {{ disaggregationForm.disaggregation_ids.length }} disaggregation(s) selected
                </p>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100">
                    <button
                        type="button"
                        @click="disaggregationsModal.visible = false"
                        class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="disaggregationForm.processing"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                    >
                        <Icon
                            v-if="disaggregationForm.processing"
                            icon="hugeicons:loading-01"
                            class="text-sm animate-spin"
                        />
                        Save Changes
                    </button>
                </div>

            </form>
        </Dialog>

    </div>
</template>