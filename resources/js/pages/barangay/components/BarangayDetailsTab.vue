<script setup>
    import { ref } from 'vue'
    import { useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import Dialog from 'primevue/dialog'
    import InputText from 'primevue/inputtext'
    import Select from 'primevue/select'
    import { useToast } from 'primevue/usetoast'

    const toast = useToast()

    const props = defineProps({
        barangay:  { type: Object, default: () => ({}) },
        provinces: { type: Array,  default: () => []   },
    })

    const modalVisible = ref(false)

    const form = useForm({
        psgc_code:    '',
        name:         '',
        province:     null,
        municipality: null,
    })

    const onShow = () => {
        form.psgc_code    = props.barangay.psgc_code ?? ''
        form.name         = props.barangay.name      ?? ''
        form.province     = props.provinces.find(p => p.id === props.barangay.province_id) ?? null
        form.municipality = form.province?.municipalities?.find(m => m.id === props.barangay.municipality_id) ?? null
    }

    const onProvinceChange = () => { form.municipality = null }

    const close = () => {
        modalVisible.value = false
        form.reset()
    }

    const submit = () => {
        form
            .transform(data => ({
                psgc_code:       data.psgc_code        || null,
                name:            data.name,
                province_id:     data.province?.id     ?? null,
                municipality_id: data.municipality?.id ?? null,
            }))
            .put(`/barangays/${props.barangay.id}`, {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Barangay Updated', detail: 'Barangay details have been updated successfully.', life: 2000 })
                    close()
                },
            })
    }
</script>

<template>
    <div>

        <!-- ── Details card ───────────────────────────────── -->
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-50">
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <Icon icon="hugeicons:information-circle" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Basic Information</span>
                </div>
                <button
                    type="button"
                    @click="modalVisible = true"
                    class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors"
                >
                    <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                    Edit Details
                </button>
            </div>

            <div class="grid grid-cols-2 divide-x divide-slate-50">
                <div class="px-5 py-4 flex flex-col gap-1">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Barangay Name</span>
                    <span class="text-sm font-semibold text-slate-700">{{ barangay.name }}</span>
                </div>
                <div class="px-5 py-4 flex flex-col gap-1">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">PSGC Code</span>
                    <code class="text-sm font-mono text-slate-700">{{ barangay.psgc_code ?? '—' }}</code>
                </div>
            </div>

            <div class="grid grid-cols-2 divide-x divide-slate-50">
                <div class="px-5 py-4 flex flex-col gap-1">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Municipality</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <div class="w-5 h-5 rounded bg-indigo-50 flex items-center justify-center shrink-0">
                            <Icon icon="hugeicons:building-03" class="text-[10px] text-indigo-500" />
                        </div>
                        <span class="text-sm font-semibold text-slate-700">{{ barangay.municipality?.name ?? '—' }}</span>
                    </div>
                </div>
                <div class="px-5 py-4 flex flex-col gap-1">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Province</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <div class="w-5 h-5 rounded bg-indigo-50 flex items-center justify-center shrink-0">
                            <Icon icon="hugeicons:map-01" class="text-[10px] text-indigo-500" />
                        </div>
                        <span class="text-sm font-semibold text-slate-700">{{ barangay.province?.name ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Modal ──────────────────────────────────────── -->
        <Dialog
            modal
            append-to="body"
            :visible="modalVisible"
            header="Edit Barangay Details"
            :style="{ width: '440px' }"
            :pt="{
                header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
                title:   { class: '!text-sm !font-bold !text-slate-800'   },
                content: { class: '!p-5'                                   },
            }"
            @update:visible="modalVisible = $event"
            @show="onShow"
            @hide="form.reset()"
        >
            <form @submit.prevent="submit" class="flex flex-col gap-4">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">Barangay Name <span class="text-red-400">*</span></label>
                    <InputText v-model="form.name" placeholder="e.g. Poblacion" class="text-sm! py-2!" :class="form.errors.name ? 'border-red-400!' : ''" autofocus />
                    <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">PSGC Code</label>
                    <InputText v-model="form.psgc_code" placeholder="e.g. 1400101001" class="text-sm! py-2! font-mono!" :class="form.errors.psgc_code ? 'border-red-400!' : ''" />
                    <p v-if="form.errors.psgc_code" class="text-[11px] text-red-500">{{ form.errors.psgc_code }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">Province <span class="text-red-400">*</span></label>
                    <Select v-model="form.province" :options="provinces" option-label="name" placeholder="Select province..." class="w-full text-sm!" :class="form.errors.province_id ? 'border-red-400!' : ''" @change="onProvinceChange" />
                    <p v-if="form.errors.province_id" class="text-[11px] text-red-500">{{ form.errors.province_id }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">Municipality <span class="text-red-400">*</span></label>
                    <Select v-model="form.municipality" :options="form.province?.municipalities ?? []" option-label="name" placeholder="Select municipality..." :disabled="!form.province" class="w-full text-sm!" :class="form.errors.municipality_id ? 'border-red-400!' : ''" />
                    <p v-if="form.errors.municipality_id" class="text-[11px] text-red-500">{{ form.errors.municipality_id }}</p>
                    <p v-else-if="!form.province" class="text-[11px] text-slate-400">Select a province first.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" @click="close" class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" :disabled="form.processing" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors">
                        <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        Save Changes
                    </button>
                </div>

            </form>
        </Dialog>

    </div>
</template>