<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import Select from 'primevue/select'
import MultiSelect from 'primevue/multiselect'
import { useToast } from 'primevue/usetoast'
import DatePicker from 'primevue/datepicker'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    report:     { type: Object, required: true },
    barangays:  { type: Array, default: () => [] },
    users:      { type: Array, default: () => [] },
    indicators: { type: Array, default: () => [] },
})


const form = useForm({
    barangay_id:             props.report.barangay_id ?? null,
    total_clients:           props.report.total_clients ?? '',
    total_returning_clients: props.report.total_returning_clients ?? '',
    date:                    props.report.date ? new Date(props.report.date) : null,
    user_ids:                props.report.users?.map(u => u.id) ?? [],
    indicator_values:        props.report.values ?? [],
})

const getIndicatorValue = (indicatorId) =>
    form.indicator_values.find(v => v.indicator_id === indicatorId)

const getDisaggregationValue = (indicatorId, disaggregationId) =>
    getIndicatorValue(indicatorId)?.disaggregations.find(d => d.disaggregation_id === disaggregationId)

const submit = () => {
    const payload = {
        ...form.data(),
        date: form.date ? new Date(form.date).toISOString().split('T')[0] : null,
    }

    form.transform(() => payload).put(`/reports/${props.report.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Report Updated',
                detail:   'Report has been updated successfully.',
                life:     2000,
            })
            router.visit('/reports')
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary:  'Error',
                detail:   'There was an error updating the report. Please check the form and try again.',
                life:     3000,
            })
        },
    })
    console.log(payload)
}
</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0">

        <!-- ── Header ─────────────────────────────────────── -->
        <div class="flex items-center gap-4 shrink-0">
            <button
                type="button"
                @click="router.visit('/reports')"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Edit Report</h1>
                <p class="text-xs text-slate-400 mt-1">
                    {{ props.report.barangay?.name }} —
                    {{ props.report.barangay?.municipality?.name }},
                    {{ props.report.barangay?.province?.name }}
                </p>
            </div>
            <span class="ml-auto text-[10px] font-mono font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                #{{ props.report.id }}
            </span>
        </div>

        <!-- ── Form ──────────────────────────────────────── -->
        <div class="flex-1 min-h-0 overflow-y-auto">
            <div class="grid grid-cols-3 gap-5">

                <!-- Main form -->
                <div class="col-span-2 flex flex-col gap-4">

                    <!-- Report Details -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-sm font-bold text-slate-700">Report Details</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Update the barangay, date, and client information.</p>
                        </div>

                        <!-- Barangay -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">
                                Barangay <span class="text-red-400">*</span>
                            </label>
                            <Select
                                v-model="form.barangay_id"
                                :options="props.barangays"
                                option-label="name"
                                option-value="id"
                                placeholder="Select barangay"
                                filter
                                class="!text-sm w-full"
                                :class="form.errors.barangay_id ? '!border-red-400' : ''"
                            />
                            <p v-if="form.errors.barangay_id" class="text-[11px] text-red-500">{{ form.errors.barangay_id }}</p>
                        </div>

                        <!-- Date -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">
                                Date <span class="text-red-400">*</span>
                            </label>
                            <DatePicker
                                v-model="form.date"
                                placeholder="Select date"
                                date-format="yy-mm-dd"
                                class="!text-sm w-full"
                                :class="form.errors.date ? '!border-red-400' : ''"
                            />
                            <p v-if="form.errors.date" class="text-[11px] text-red-500">{{ form.errors.date }}</p>
                        </div>

                        <!-- Clients -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Total Clients <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.total_clients"
                                    type="number"
                                    min="0"
                                    placeholder="e.g. 120"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.total_clients ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.total_clients" class="text-[11px] text-red-500">{{ form.errors.total_clients }}</p>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Returning Clients <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.total_returning_clients"
                                    type="number"
                                    min="0"
                                    placeholder="e.g. 45"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.total_returning_clients ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.total_returning_clients" class="text-[11px] text-red-500">{{ form.errors.total_returning_clients }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Report Values -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-sm font-bold text-slate-700">Report Values</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Update values per indicator. Leave blank if not applicable.</p>
                        </div>

                        <!-- Empty -->
                        <div v-if="props.report.values.length === 0" class="flex flex-col items-center gap-2 py-8">
                            <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                            <p class="text-sm text-slate-400">No indicators available.</p>
                        </div>

                        <!-- Report Values -->
                        <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                            <div class="border-b border-slate-100 pb-4">
                                <h2 class="text-sm font-bold text-slate-700">Report Values</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Update values per indicator. Leave blank if not applicable.</p>
                            </div>

                            <!-- Empty -->
                            <div v-if="form.indicator_values.length === 0" class="flex flex-col items-center gap-2 py-8">
                                <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                <p class="text-sm text-slate-400">No indicators available.</p>
                            </div>

                            <!-- Indicator rows -->
                            <div
                                v-for="value in form.indicator_values"
                                :key="value.indicator_id"
                                class="flex flex-col gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100"
                            >
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-slate-700">{{ value.indicator.indicator_name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            {{ value.disaggregations.length }} disaggregation(s)
                                        </p>
                                    </div>
                                    <div class="flex flex-col gap-1 items-end shrink-0">
                                        <label class="text-[10px] font-semibold text-slate-500">Total</label>
                                        <input
                                            v-model="value.total"
                                            type="number"
                                            min="0"
                                            placeholder="0"
                                            class="w-24 px-2 py-1.5 text-xs text-right bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                        />
                                    </div>
                                </div>

                                <!-- Disaggregations -->
                                <div v-if="value.disaggregations.length > 0" class="grid grid-cols-3 gap-2">
                                    <div
                                        v-for="disaggregation in value.disaggregations"
                                        :key="disaggregation.disaggregation_id"
                                        class="flex flex-col gap-1"
                                    >
                                        <label class="text-[10px] font-semibold text-slate-500 truncate">
                                            {{ disaggregation.disaggregation.disaggregation_name }}
                                        </label>
                                        <input
                                            v-model="disaggregation.value"
                                            type="number"
                                            min="0"
                                            placeholder="0"
                                            class="w-full px-2 py-1.5 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                        />
                                    </div>
                                </div>

                                <p v-else class="text-[11px] text-slate-400 italic">No disaggregations set.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pb-5">
                        <button
                            type="button"
                            @click="router.visit('/reports')"
                            class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="submit"
                            :disabled="form.processing"
                            class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                        >
                            <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                            Save Changes
                        </button>
                    </div>

                </div>

                <!-- Side -->
                <div class="flex flex-col gap-3">

                    <!-- Users -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                        <div class="border-b border-slate-100 pb-3">
                            <h2 class="text-sm font-bold text-slate-700">Reported By</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Select the users submitting this report.</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">
                                Users <span class="text-red-400">*</span>
                            </label>
                            <MultiSelect
                                v-model="form.user_ids"
                                :options="props.users"
                                option-label="name"
                                option-value="id"
                                placeholder="Select users"
                                filter
                                display="chip"
                                class="!text-sm w-full"
                                :class="form.errors.user_ids ? '!border-red-400' : ''"
                            />
                            <p v-if="form.errors.user_ids" class="text-[11px] text-red-500">{{ form.errors.user_ids }}</p>
                        </div>
                    </div>

                    <!-- Indicator totals summary -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Indicator Totals</p>
                        <div v-if="props.indicators.length === 0" class="text-[11px] text-slate-400">No indicators.</div>
                        <div v-else class="flex flex-col divide-y divide-slate-50">
                            <div
                                v-for="indicator in props.indicators"
                                :key="indicator.id"
                                class="flex items-center justify-between gap-2 py-1.5"
                            >
                                <span class="text-[11px] text-slate-500 truncate leading-tight">{{ indicator.indicator_name }}</span>
                                <span class="text-[11px] font-bold text-slate-700 shrink-0 tabular-nums">
                                    {{ getIndicatorValue(indicator.id)?.total || '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Meta info -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col gap-2.5">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Report Info</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Report ID</span>
                            <span class="text-[11px] font-mono font-semibold text-slate-600">#{{ props.report.id }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Created</span>
                            <span class="text-[11px] font-medium text-slate-600">
                                {{ props.report.created_at
                                    ? new Date(props.report.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                                    : '—' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">Last Updated</span>
                            <span class="text-[11px] font-medium text-slate-600">
                                {{ props.report.updated_at
                                    ? new Date(props.report.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                                    : '—' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>