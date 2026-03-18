<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { router, useForm } from '@inertiajs/vue3'
import Select from 'primevue/select'
import InputNumber from 'primevue/inputnumber'
import Button from 'primevue/button'
import { ref } from 'vue'
import { useToast } from 'primevue/usetoast'
import FloatLabel from 'primevue/floatlabel'
import Dialog from 'primevue/dialog'

defineOptions({ layout: Main })
const toast = useToast()

const isLoading = ref(false)
const editModalVisible = ref(false)

const props = defineProps({
    barangay: { type: Object, default: () => ({}) },
    programs:  { type: Array,  default: () => [] },
})

const addForm = useForm({
    barangay_id: props.barangay.id,
    program_id: null,
    target: null,
    order: null
})

const editForm = useForm({
    program_id: null,
    target: null,
    order: null
})

const openEditModal = (priority) => {
    editForm.id = priority.id
    editForm.program_id = priority.program_id
    editForm.target = priority.target
    editForm.order = priority.order
    editModalVisible.value = true
}

const handleAdd = () => {
    addForm.post('/barangay/priority-program', {
        onStart: () => isLoading.value = true,
        onFinish: () => isLoading.value = false,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Program Added',
                detail:   'Program has been added successfully.',
                life:     2000,
            })
            addForm.reset()
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary:  'Error',
                detail:   'Failed to add program. Please try again.',
                life:     2000,
            })
        },
    })
}

const handleEdit = () => {
    editForm.put(`/barangay/priority-program/${editForm.id}`, {
        onStart: () => isLoading.value = true,
        onFinish: () => isLoading.value = false,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Updated',
                detail:   'Program has been updated successfully.',
                life:     2000,
            })
            editModalVisible.value = false
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary:  'Error',
                detail:   'Failed to update. Please try again.',
                life:     2000,
            })
        },
    })
}

const handleDelete = (priorityProgramID) => {
    editForm.delete(`/barangay/priority-program/${priorityProgramID}`, {
        onStart: () => isLoading.value = true,
        onFinish: () => isLoading.value = false,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Deleted',
                detail:   'Program has been removed successfully.',
                life:     2000,
            })
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary:  'Error',
                detail:   'Failed to remove program. Please try again.',
                life:     2000,
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
                <h1 class="text-lg font-bold text-slate-800 leading-none">Set Priority Programs</h1>
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

        <!-- ── Card ───────────────────────────────────────── -->
        <div class="flex-1 flex items-start justify-center overflow-y-auto">
            <div class="bg-white rounded-xl border border-slate-200 w-full max-w-5xl">

                <!-- Card header -->
                <div class="px-5 py-3.5 flex items-center gap-2 border-b border-slate-100">
                    <Icon icon="hugeicons:maps-location-02" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Priority Programs</span>
                </div>

                <!-- ── Add row ─────────────────────────────── -->
                <div class="w-full flex flex-col px-5 py-4 border-b border-slate-100 bg-slate-50/60">
                    <div class="flex items-end gap-3">

                        <FloatLabel variant="on" class="w-full">
                            <Select
                                v-model="addForm.program_id"
                                :options="props.programs"
                                option-label="name"
                                option-value="id"
                                class="w-full text-sm!"
                                :invalid="!!addForm.errors.program_id"
                                size="small"
                            />
                            <label for="value3">Select a Program</label>
                        </FloatLabel>

                        <FloatLabel variant="on" class="w-full">
                            <InputNumber
                                v-model="addForm.order"
                                :min="1"
                                :use-grouping="false"
                                placeholder="1 = highest"
                                class="w-full text-sm!"
                                input-class="w-full"
                                :invalid="!!addForm.errors.order"
                                size="small"
                            />
                            <label for="value3">Priority #</label>
                        </FloatLabel>

                        <FloatLabel variant="on" class="w-full">
                            <InputNumber
                                v-model="addForm.target"
                                :min="0"
                                :use-grouping="false"
                                placeholder="Target population or coverage"
                                class="w-full text-sm!"
                                input-class="w-full"
                                :invalid="!!addForm.errors.target"
                                size="small"
                            />
                            <label for="value3">Target</label>
                        </FloatLabel>

                        <!-- Add button -->
                        <Button
                            label="Add Program"
                            :loading="isLoading"
                            :disabled="isLoading"
                            size="small"
                            class="self-end shrink-0"
                            severity="info"
                            @click="handleAdd"
                        >
                            <template #icon>
                                <Icon icon="hugeicons:plus-sign" class="text-sm mr-1" />
                            </template>
                        </Button>
                    </div>
                </div>

                <!-- ── Programs table ──────────────────────── -->
                <div class="divide-y divide-slate-50">

                    <!-- Empty state -->
                    <div v-if="props.barangay?.priority_programs.length === 0" class="py-14 flex flex-col items-center gap-2 text-slate-400">
                        <Icon icon="hugeicons:maps-location-02" class="text-3xl opacity-30" />
                        <p class="text-xs">No priority programs added yet.</p>
                    </div>

                    <!-- Table -->
                    <table v-else class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3 text-left">Program</th>
                                <th class="px-5 py-3 text-left w-32">Priority #</th>
                                <th class="px-5 py-3 text-left w-32">Target</th>
                                <th class="px-5 py-3 text-right w-36">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr
                                v-for="row in props.barangay.priority_programs"
                                :key="row.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <!-- Program name -->
                                <td class="px-5 py-3">
                                    <span class="font-medium text-slate-700">{{ row.program?.name }}</span>
                                </td>

                                <!-- Priority # -->
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-bold">
                                        {{ row.order }}
                                    </span>
                                </td>

                                <!-- Target -->
                                <td class="px-5 py-3">
                                    <span class="text-slate-600">{{ row.target }}</span>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-3 flex justify-end gap-2">
                                    <!-- edit -->
                                    <button
                                        @click="openEditModal(row)"
                                        type="button"
                                        class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                        title="Edit"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-02" class="text-sm cursor-pointer text-sky-600" />
                                    </button>

                                    <!-- remove -->
                                    <button
                                        @click="handleDelete(row.id)"
                                        type="button"
                                        class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                        title="Remove"
                                    >
                                        <Icon icon="hugeicons:delete-03" class="text-sm cursor-pointer text-rose-600" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ── Footer ─────────────────────────────── -->
                <div class="px-5 py-4 flex items-center justify-end border-t border-slate-100">
                    <Button
                        label="Back to Barangays"
                        severity="secondary"
                        size="small"
                        text
                    />
                </div>

            </div>
        </div>

        <!-- ── Edit Modal ───────────────────────────────── -->
         <Dialog
            modal
            append-to="body"
            v-model:visible="editModalVisible"
            header="Edit Priority Program"
            :style="{ width: '440px' }"
            :pt="{
                header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
                title:   { class: '!text-sm !font-bold !text-slate-800'   },
                content: { class: '!p-5'                                   },
            }"
        >
            <form @submit.prevent="submit" class="flex flex-col gap-4">

                <FloatLabel variant="on" class="w-full">
                    <InputNumber
                        v-model="editForm.order"
                        :min="1"
                        :use-grouping="false"
                        placeholder="1 = highest"
                        class="w-full text-sm!"
                        input-class="w-full"
                        :invalid="!!editForm.errors.order"
                        size="small"
                    />
                    <label for="value3">Priority #</label>
                </FloatLabel>

                <FloatLabel variant="on" class="w-full">
                    <InputNumber
                        v-model="editForm.target"
                        :min="0"
                        :use-grouping="false"
                        placeholder="Target population or coverage"
                        class="w-full text-sm!"
                        input-class="w-full"
                        :invalid="!!editForm.errors.target"
                        size="small"
                    />
                    <label for="value3">Target</label>
                </FloatLabel>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="editModalVisible = false" 
                        class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>

                    <button 
                        @click="handleEdit"
                        type="submit" 
                        :disabled="editForm.processing" 
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                    >
                        <Icon v-if="editForm.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        Save
                    </button>

                </div>

            </form>
        </Dialog>

    </div>
</template>