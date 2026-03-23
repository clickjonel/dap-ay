<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    provinces: { type: Array,  default: () => [] },
    barangay:  { type: Object, default: () => ({}) },
})

const form = useForm({
    psgc_code:    props.barangay.psgc_code    || '',
    name:         props.barangay.name         || '',
    province:     props.barangay.province     || null,
    municipality: props.barangay.municipality || null,
    mov_link:     props.barangay.mov_link     || '',
})

const onProvinceChange = () => {
    form.municipality = null
}

const submit = () => {
    form
        .transform(data => ({
            psgc_code:       data.psgc_code        || null,
            name:            data.name,
            province_id:     data.province?.id     ?? null,
            municipality_id: data.municipality?.id ?? null,
            mov_link:          data.mov_link       || null,
        }))
        .put(`/barangays/${props.barangay.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary:  'Barangay Updated',
                    detail:   'Barangay has been updated successfully.',
                    life:     2000,
                })
                router.visit('/barangays')
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary:  'Error',
                    detail:   'Please check the form and try again.',
                    life:     3000,
                })
            },
        })
}
</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0">

        <!-- ── Header ─────────────────────────────────────── -->
        <div class="flex items-center gap-4 shrink-0">
            <button
                type="button"
                @click="router.visit('/barangays')"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Update Barangay</h1>
                <p class="text-xs text-slate-400 mt-1">
                    {{ props.barangay.name }} —
                    {{ props.barangay.municipality?.name }},
                    {{ props.barangay.province?.name }}
                </p>
            </div>
            <span class="ml-auto text-[10px] font-mono font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                #{{ props.barangay.id }}
            </span>
        </div>

        <!-- ── Form card ──────────────────────────────────── -->
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-50 w-full max-w-lg">

                <div class="px-5 py-3.5 flex items-center gap-2">
                    <Icon icon="hugeicons:information-circle" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Basic Information</span>
                </div>

                <form @submit.prevent="submit" class="px-5 py-5 flex flex-col gap-4">

                    <!-- Name -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">
                            Barangay Name <span class="text-red-400">*</span>
                        </label>
                        <InputText
                            v-model="form.name"
                            placeholder="e.g. Poblacion"
                            class="text-sm! py-2!"
                            :class="form.errors.name ? '!border-red-400' : ''"
                            autofocus
                        />
                        <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <!-- PSGC Code -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">PSGC Code</label>
                        <InputText
                            v-model="form.psgc_code"
                            placeholder="e.g. 1400101001"
                            class="text-sm! py-2! font-mono!"
                            :class="form.errors.psgc_code ? '!border-red-400' : ''"
                        />
                        <p v-if="form.errors.psgc_code" class="text-[11px] text-red-500">{{ form.errors.psgc_code }}</p>
                    </div>

                    <!-- Province -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">
                            Province <span class="text-red-400">*</span>
                        </label>
                        <Select
                            v-model="form.province"
                            :options="props.provinces"
                            option-label="name"
                            placeholder="Select province..."
                            class="w-full text-sm!"
                            :class="form.errors.province ? '!border-red-400' : ''"
                            @change="onProvinceChange"
                        />
                        <p v-if="form.errors.province" class="text-[11px] text-red-500">{{ form.errors.province }}</p>
                    </div>

                    <!-- Municipality -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">
                            Municipality <span class="text-red-400">*</span>
                        </label>
                        <Select
                            v-model="form.municipality"
                            :options="form.province?.municipalities ?? []"
                            option-label="name"
                            placeholder="Select municipality..."
                            :disabled="!form.province"
                            class="w-full text-sm!"
                            :class="form.errors.municipality ? '!border-red-400' : ''"
                        />
                        <p v-if="form.errors.municipality" class="text-[11px] text-red-500">{{ form.errors.municipality }}</p>
                        <p v-else-if="!form.province" class="text-[11px] text-slate-400">Select a province first.</p>
                    </div>

                    <!-- MOV Link -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">MOV Link</label>
                        <InputText
                            v-model="form.mov_link"
                            placeholder="e.g. https://example.com/mov"
                            class="!text-sm !py-2"
                            :class="form.errors.mov_link ? '!border-red-400' : ''"
                        />
                        <p v-if="form.errors.mov_link" class="text-[11px] text-red-500">{{ form.errors.mov_link }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button
                            type="button"
                            @click="router.visit('/barangays')"
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
                            Update Barangay
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>