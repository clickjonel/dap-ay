<script setup>
    import Main from '@/layouts/main.vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import InputText from 'primevue/inputtext'
    import Select from 'primevue/select'
    import MultiSelect from 'primevue/multiselect'
    import DatePicker from 'primevue/datepicker'
    import Panel from 'primevue/panel'
    import Button from 'primevue/button'
    import FloatLabel from 'primevue/floatlabel'
    import InputNumber from 'primevue/inputnumber'
    import { formatDate } from '@/utils/dateHelper'

    defineOptions({ layout: Main })

    const toast = useToast()

    const props = defineProps({
        programs:  { type: Array,  default: () => [] },
        barangays: { type: Array,  default: () => [] },
        hrh:       { type: Array,  default: () => [] },
        activity:  { type: Object, default: () => {} },
    })

    const parseDate = (dateStr) => dateStr ? new Date(dateStr) : null

    const pkActivityForm = useForm({
        activity: {
            activity_name: props.activity.activity_name ?? '',
            type:          props.activity.type          ?? null,
            date_start:    parseDate(props.activity.date_start),
            date_end:      parseDate(props.activity.date_end),
            total_clients: props.activity.total_clients ?? null,
        },
        programs:  props.activity.programs?.map(p => p.id)  ?? [],
        barangays: props.activity.barangays?.map(b => b.id) ?? [],
        hrh:       props.activity.participants?.map(h => h.id) ?? [],
    })

    const multiSelectPt = {
        root:           { class: 'min-w-0 w-full' },
        labelContainer: { class: 'overflow-hidden min-w-0' },
        label:          { class: 'flex flex-wrap gap-1 overflow-hidden min-w-0' },
        chipItem:       { class: 'max-w-[150px] sm:max-w-full min-w-0' },
        chip:           { class: 'max-w-full truncate' },
        overlay:        { class: 'w-full max-w-[calc(100vw-2rem)] sm:max-w-full' },
    }

    const submitActivity = () => {
        const payload = {
            ...pkActivityForm.activity,
            date_start: formatDate(pkActivityForm.activity.date_start),
            date_end:   formatDate(pkActivityForm.activity.date_end),
            programs:   pkActivityForm.programs,
            barangays:  pkActivityForm.barangays,
            hrh:        pkActivityForm.hrh,
        }

        router.put(`/pk-activities/${props.activity.id}`, payload, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Success', detail: 'Activity updated!', life: 3000 })
                router.visit('/pk-activities')
            },
            onError: (errors) => {
                pkActivityForm.setError(errors)
                toast.add({ severity: 'error', summary: 'Something Went Wrong', detail: 'Please check the form for required fields and try again or contact the system administrator.', life: 3000 })
            }
        })
    }
</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0">

        <!-- ── Header ──────────────────────────────────────── -->
        <div class="flex items-center gap-4">
            <button
                type="button"
                @click="router.visit('/pk-activities')"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Update Activity</h1>
                <p class="text-xs text-slate-400 mt-1">Update the activity details below.</p>
            </div>
        </div>

        <!-- Panels -->
        <form class="w-full flex flex-col justify-start items-start gap-4 p-4">

            <Panel header="Purokalusugan Activity Details" toggleable class="w-full">
                <div class="w-full flex flex-col justify-start items-start gap-6 p-4">
                    <FloatLabel variant="on" class="w-full">
                        <InputText v-model="pkActivityForm.activity.activity_name" size="small" class="w-full" />
                        <label>Activity Name</label>
                        <p v-if="pkActivityForm.errors.activity_name" class="text-[11px] text-red-500">{{ pkActivityForm.errors.activity_name }}</p>
                    </FloatLabel>

                    <FloatLabel variant="on" class="w-full">
                        <Select v-model="pkActivityForm.activity.type" size="small" :options="[{ label: 'large' }, { label: 'small' }]" option-value="label" option-label="label" class="w-full" />
                        <label>Activity Type</label>
                        <p v-if="pkActivityForm.errors.type" class="text-[11px] text-red-500">{{ pkActivityForm.errors.type }}</p>
                    </FloatLabel>

                    <FloatLabel variant="on" class="w-full">
                        <DatePicker v-model="pkActivityForm.activity.date_start" size="small" class="w-full" />
                        <label>Start Date</label>
                        <p v-if="pkActivityForm.errors.date_start" class="text-[11px] text-red-500">{{ pkActivityForm.errors.date_start }}</p>
                    </FloatLabel>

                    <FloatLabel variant="on" class="w-full">
                        <DatePicker v-model="pkActivityForm.activity.date_end" size="small" class="w-full" />
                        <label>End Date</label>
                        <p v-if="pkActivityForm.errors.date_end" class="text-[11px] text-red-500">{{ pkActivityForm.errors.date_end }}</p>
                    </FloatLabel>

                    <FloatLabel variant="on" class="w-full">
                        <InputNumber v-model="pkActivityForm.activity.total_clients" size="small" class="w-full" />
                        <label>Total Clients</label>
                        <p v-if="pkActivityForm.errors.total_clients" class="text-[11px] text-red-500">{{ pkActivityForm.errors.total_clients }}</p>
                    </FloatLabel>
                </div>
            </Panel>

            <Panel header="Participant Programs" toggleable class="w-full">
                <div class="w-full flex flex-col justify-start items-start gap-6 p-4">
                    <FloatLabel variant="on" class="w-full">
                        <MultiSelect
                            v-model="pkActivityForm.programs"
                            :options="props.programs"
                            option-label="name"
                            option-value="id"
                            display="chip"
                            size="large"
                            class="w-full"
                            filter
                            :pt="multiSelectPt"
                        />
                        <label>Select Programs Participating</label>
                        <p v-if="pkActivityForm.errors.programs" class="text-[11px] text-red-500">{{ pkActivityForm.errors.programs }}</p>
                    </FloatLabel>
                </div>
            </Panel>

            <Panel header="Participant Barangays" toggleable class="w-full">
                <div class="w-full flex flex-col justify-start items-start gap-6 p-4">
                    <FloatLabel variant="on" class="w-full">
                        <MultiSelect
                            v-model="pkActivityForm.barangays"
                            :options="props.barangays"
                            option-label="name"
                            option-value="id"
                            display="chip"
                            size="large"
                            class="w-full"
                            filter
                            :pt="multiSelectPt"
                        />
                        <label>Select Barangays Participating</label>
                        <p v-if="pkActivityForm.errors.barangays" class="text-[11px] text-red-500">{{ pkActivityForm.errors.barangays }}</p>
                    </FloatLabel>
                </div>
            </Panel>

            <Panel header="Participant HRH" toggleable class="w-full">
                <div class="w-full flex flex-col justify-start items-start gap-6 p-4">
                    <FloatLabel variant="on" class="w-full">
                        <MultiSelect
                            v-model="pkActivityForm.hrh"
                            :options="props.hrh"
                            option-label="name"
                            option-value="id"
                            display="chip"
                            size="large"
                            class="w-full"
                            filter
                            :pt="multiSelectPt"
                        />
                        <label>Select HRH Participating</label>
                        <p v-if="pkActivityForm.errors.hrh" class="text-[11px] text-red-500">{{ pkActivityForm.errors.hrh }}</p>
                    </FloatLabel>
                </div>
            </Panel>

            <Button @click="submitActivity" label="Update Activity" />
        </form>

    </div>
</template>