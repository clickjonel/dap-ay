<script setup>
import { ref, inject } from 'vue'
import { Icon } from '@iconify/vue';
import { useForm, router } from '@inertiajs/vue3'
import Main from '@/layouts/main.vue'
import Dialog from 'primevue/dialog';
import Checkbox from 'primevue/checkbox';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';

defineOptions({
    layout: Main
})

const toast = useToast()
const props = defineProps({
    municipalities: Array,
    assignedMunicipalities: Array
})

// Pre-select already assigned municipality IDs
const preSelected = props.assignedMunicipalities.map(a => a.municipality.id)

const selectMunicipalitiesModal = ref({
    visible: false,
})

const form = useForm({
    municipalities: [...preSelected],
    dcpo_no: ''
})

const openSelectMunicipalitiesModal = () => {
    selectMunicipalitiesModal.value.visible = true
}

const submitMunicipalities = () => {
    form.post('/users/handled-municipalities', {
        onSuccess: () => {
            selectMunicipalitiesModal.value.visible = false
            toast.add({
                severity: 'success',
                summary:  'Municipalities Saved',
                detail:   'Municipalities have been assigned successfully.',
                life:     2000,
            })
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary:  'Error',
                detail:   'There was an error saving the municipalities. Please check the form and try again.',
                life:     3000,
            })
        }
    })
}
</script>

<template>
    <div class="h-full flex flex-col gap-4 lg:gap-5 min-h-0">
        <!-- ── Header ─────────────────────────────────────────── -->
        <div class="flex items-start sm:items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="router.visit('/teams')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
                >
                    <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
                </button>
                <div>
                    <h1 class="text-base lg:text-lg font-bold text-slate-800 leading-none">Manage Municipalities</h1>
                    <p class="text-xs text-slate-400 mt-1">Manage the municipalities assigned to this user.</p>
                </div>
            </div>
            <button
                type="button"
                @click="openSelectMunicipalitiesModal"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">Select Municipality</span>
                <span class="sm:hidden">Add</span>
            </button>
        </div>

        <!-- ── Filters ────────────────────────────────────────── -->
        <div class="flex items-center gap-3 shrink-0">
            <div class="relative flex-1 sm:max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    type="text"
                    placeholder="Search members..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
        </div>

        <div class="flex-1 min-h-0 overflow-y-auto">
            <div class="hidden md:block bg-white rounded-xl border border-slate-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 sticky top-0">
                        <tr>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Municipality</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">DCPO Number</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="assignedMunicipalities.length === 0">
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                    <p class="text-sm font-medium text-slate-400">No assigned municipalities yet</p>
                                    <p class="text-xs text-slate-300">Try adjusting your search or add assign a new one.</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-else v-for="(assignedMunicipality, index) in assignedMunicipalities" :key="assignedMunicipality.id">
                            <td class="px-5 py-3 text-[10px] font-light uppercase tracking-wider text-slate-600">{{ index + 1 }}</td>
                            <td class="px-5 py-3 text-[10px] font-light uppercase tracking-wider text-slate-600">{{ assignedMunicipality.municipality.name }}</td>
                            <td class="px-5 py-3 text-[10px] font-light uppercase tracking-wider text-slate-600">{{ assignedMunicipality.dcpo_no }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Dialog v-model:visible="selectMunicipalitiesModal.visible" header="Select Municipalities Handled" class="w-1/2">
            <div class="flex flex-col gap-6">

                <!-- Municipality Checkboxes -->
                <div class="w-full grid grid-cols-4 gap-4">
                    <div
                        v-for="mun in props.municipalities"
                        :key="mun.id"
                        class="flex justify-start items-center gap-2 bg-slate-100 rounded-lg shadow-sm p-4"
                    >
                        <Checkbox
                            v-model="form.municipalities"
                            :inputId="`dmo-municipality-${mun.id}`"
                            :name="`municipality-${mun.id}`"
                            :value="mun.id"
                            size="large"
                        />
                        <label class="text-sm uppercase font-medium cursor-pointer" :for="`dmo-municipality-${mun.id}`">
                            {{ mun.name }}
                        </label>
                    </div>
                </div>
                <small v-if="form.errors.municipalities" class="text-red-500">{{ form.errors.municipalities }}</small>

                <!-- DCPO Field -->
                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-slate-700">DCPO Number</label>
                    <InputText
                        v-model="form.dcpo_no"
                        placeholder="Enter DCPO number..."
                        class="w-full"
                        :invalid="!!form.errors.dcpo_no"
                    />
                    <small v-if="form.errors.dcpo_no" class="text-red-500">{{ form.errors.dcpo_no }}</small>
                </div>

            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="selectMunicipalitiesModal.visible = false"
                        class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="submitMunicipalities"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-lg transition-colors"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Municipalities' }}
                    </button>
                </div>
            </template>
        </Dialog>
    </div>
</template>