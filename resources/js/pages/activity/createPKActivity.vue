<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import MultiSelect from 'primevue/multiselect'
import DatePicker from 'primevue/datepicker'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    programs:  { type: Array, default: () => [] },
    barangays: { type: Array, default: () => [] },  // ← new
})

const activityTypes = [
    { label: 'Small Scale Activity', value: 'small' },
    { label: 'Large Scale Activity', value: 'large' },
]

const form = useForm({
    activity_name: '',
    type:          null,
    date_start:    null,
    date_end:      null,
    total_clients: '',
    program_ids:   [],
    barangay_ids:  [],
})

const formatDate = (date) => {
    if (!date) return null
    const d     = new Date(date)
    const year  = d.getFullYear()
    const month = String(d.getMonth() + 1).padStart(2, '0')
    const day   = String(d.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

const submit = () => {
    form
        .transform(data => ({
            ...data,
            date_start: formatDate(data.date_start),
            date_end:   formatDate(data.date_end),
        }))
        .post('/pk-activities', {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Activity Created', detail: 'Activity has been created successfully.', life: 2000 })
                router.visit('/pk-activities')
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Error', detail: 'Please check the form and try again.', life: 3000 })
            },
        })
}
</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0">

        <!-- ── Header ──────────────────────────────────────── -->
        <div class="flex items-center gap-4 shrink-0">
            <button
                type="button"
                @click="router.visit('/pk-activities')"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">New Activity</h1>
                <p class="text-xs text-slate-400 mt-1">Fill in the activity details to get started.</p>
            </div>
        </div>

        <!-- ── Form ───────────────────────────────────────── -->
        <div class="flex-1 min-h-0 overflow-y-auto">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-3 gap-5">

                    <!-- ── Col 1: Activity Details ── -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                        <div class="border-b border-slate-100 pb-3">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Activity Details</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Basic information about the activity.</p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">Activity Name <span class="text-red-400">*</span></label>
                            <InputText
                                v-model="form.activity_name"
                                placeholder="e.g. Barangay Health Day"
                                class="!text-sm !py-2"
                                :class="{ '!border-red-400': form.errors.activity_name }"
                            />
                            <p v-if="form.errors.activity_name" class="text-[11px] text-red-500">{{ form.errors.activity_name }}</p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">Type <span class="text-red-400">*</span></label>
                            <Select
                                v-model="form.type"
                                :options="activityTypes"
                                option-label="label"
                                option-value="value"
                                placeholder="Select type"
                                class="!text-sm"
                                :class="{ '!border-red-400': form.errors.type }"
                            />
                            <p v-if="form.errors.type" class="text-[11px] text-red-500">{{ form.errors.type }}</p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">Date Start <span class="text-red-400">*</span></label>
                            <DatePicker
                                v-model="form.date_start"
                                placeholder="Select start date"
                                date-format="yy-mm-dd"
                                class="!text-sm"
                                :class="{ '!border-red-400': form.errors.date_start }"
                            />
                            <p v-if="form.errors.date_start" class="text-[11px] text-red-500">{{ form.errors.date_start }}</p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">Date End <span class="text-red-400">*</span></label>
                            <DatePicker
                                v-model="form.date_end"
                                placeholder="Select end date"
                                date-format="yy-mm-dd"
                                :min-date="form.date_start"
                                class="!text-sm"
                                :class="{ '!border-red-400': form.errors.date_end }"
                            />
                            <p v-if="form.errors.date_end" class="text-[11px] text-red-500">{{ form.errors.date_end }}</p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">Total Clients <span class="text-red-400">*</span></label>
                            <InputText
                                v-model="form.total_clients"
                                type="number"
                                placeholder="e.g. 120"
                                class="!text-sm !py-2"
                                :class="{ '!border-red-400': form.errors.total_clients }"
                            />
                            <p v-if="form.errors.total_clients" class="text-[11px] text-red-500">{{ form.errors.total_clients }}</p>
                        </div>
                    </div>

                    <!-- ── Col 2: Programs ── -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                        <div class="border-b border-slate-100 pb-3">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Programs</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Select programs covered by this activity.</p>
                        </div>

                        <div class="flex flex-col gap-1.5 flex-1">
                            <p v-if="form.errors.program_ids" class="text-[11px] text-red-500">{{ form.errors.program_ids }}</p>

                            <div v-if="programs.length === 0" class="flex flex-col items-center justify-center py-10 gap-2">
                                <Icon icon="hugeicons:folder-02" class="text-2xl text-slate-300" />
                                <p class="text-xs text-slate-400">No programs available.</p>
                            </div>

                            <div
                                v-for="program in programs"
                                :key="program.id"
                                @click="form.program_ids.includes(program.id)
                                    ? form.program_ids = form.program_ids.filter(id => id !== program.id)
                                    : form.program_ids.push(program.id)"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg border cursor-pointer transition-all select-none"
                                :class="form.program_ids.includes(program.id)
                                    ? 'border-indigo-300 bg-indigo-50'
                                    : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
                            >
                                <div
                                    class="w-4 h-4 rounded flex items-center justify-center border flex-shrink-0 transition-all"
                                    :class="form.program_ids.includes(program.id)
                                        ? 'bg-indigo-600 border-indigo-600'
                                        : 'border-slate-300'"
                                >
                                    <Icon
                                        v-if="form.program_ids.includes(program.id)"
                                        icon="hugeicons:checkmark-01"
                                        class="text-white text-[10px]"
                                    />
                                </div>
                                <span class="text-xs font-medium text-slate-700">{{ program.name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── Col 3: Barangays ── -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-4">
                        <div class="border-b border-slate-100 pb-3">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Barangays</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Search and select covered barangays.
                                <span v-if="form.barangay_ids.length" class="text-indigo-500 font-semibold">
                                    {{ form.barangay_ids.length }} selected
                                </span>
                            </p>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <MultiSelect
                                v-model="form.barangay_ids"
                                :options="barangays"
                                option-label="name"
                                option-value="id"
                                placeholder="Search barangays..."
                                filter
                                filter-placeholder="Type to filter..."
                                :max-selected-labels="3"
                                selected-items-label="{0} barangays selected"
                                display="chip"
                                class="w-full !text-sm"
                                :class="{ '!border-red-400': form.errors.barangay_ids }"
                                :pt="{
                                    option: { class: 'text-xs' },
                                    label:  { class: 'text-xs' },
                                }"
                            >
                                <!-- Custom option slot to show municipality -->
                                <template #option="{ option }">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-slate-700">{{ option.name }}</span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ option.municipality?.name }}{{ option.province?.name ? ', ' + option.province.name : '' }}
                                        </span>
                                    </div>
                                </template>
                            </MultiSelect>
                            <p v-if="form.errors.barangay_ids" class="text-[11px] text-red-500">{{ form.errors.barangay_ids }}</p>
                        </div>
                    </div>

                </div>

                <!-- ── Actions ── -->
                <div class="flex items-center justify-end gap-2 py-4">
                    <button
                        type="button"
                        @click="router.visit('/pk-activities')"
                        class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                    >
                        <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        Create Activity
                    </button>
                </div>
            </form>
        </div>

    </div>
</template>