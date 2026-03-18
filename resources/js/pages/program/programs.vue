<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, computed } from 'vue'
    import { useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import Dialog from 'primevue/dialog'
    import InputText from 'primevue/inputtext'
    import Select from 'primevue/select'
    import ToggleSwitch from 'primevue/toggleswitch'
    import { useToast } from 'primevue/usetoast'

    const toast = useToast()
    defineOptions({ layout: Main })

    const props = defineProps({
        programs: { type: Array, default: () => [] },
        groups: { type: Array, default: () => [] },
    })

    // ── Modal state ────────────────────────────────────────
    const showModal    = ref(false)
    const modalMode    = ref('create') // 'create' | 'edit'
    const selectedProgram = ref(null)

    // ── Search / filter ────────────────────────────────────
    const search   = ref('')
    const filterGroup = ref(null)

    const filteredPrograms = computed(() => {
        return props.programs.filter(p => {
            const matchSearch = p.name.toLowerCase().includes(search.value.toLowerCase())
            const matchGroup  = filterGroup.value ? p.parent_program_id === filterGroup.value : true
            return matchSearch && matchGroup
        })
    })

    // ── Form ───────────────────────────────────────────────
    const form = useForm({
        name: '',
        parent_program_id: null,
        is_active: true,
    })

    const openCreate = () => {
        modalMode.value = 'create'
        selectedProgram.value = null
        form.reset()
        form.is_active = true
        showModal.value = true
    }

    const openEdit = (program) => {
        modalMode.value = 'edit'
        selectedProgram.value = program
        form.name             = program.name
        form.parent_program_id = program.parent_program_id
        form.is_active        = program.is_active === 1 ? true : false
        showModal.value = true
    }

    const submitForm = () => {
        if (modalMode.value === 'create') {
            form.post('/program', {
                onSuccess: () => {
                    showModal.value = false
                    form.reset()
                    toast.add({
                        severity: 'success',
                        summary: 'Program Created',
                        detail: 'The program has been successfully created.',
                        life: 3000,
                    })
                },
            })
        } else {
            form.put(`/program/${selectedProgram.value.id}`, {
                onSuccess: () => {
                    showModal.value = false
                    toast.add({
                        severity: 'success',
                        summary: 'Program Updated',
                        detail: 'The program has been successfully updated.',
                        life: 3000,
                    })
                },
            })
        }
    }

    const changeStatus = (program) => {
        useForm({}).delete(`/program/${program.id}`, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Status Updated',
                    detail: 'The status of the program is changed successfully.',
                    life: 3000,
                })
            },
            preserveScroll: true,
        })
    }

</script>

<template>
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Programs</h1>
            <p class="text-xs text-slate-400 mt-1">Manage health programs and their groups.</p>
        </div>
        <button
            type="button"
            @click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            New Program
        </button>
    </div>

    <!-- ── Filters ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                v-model="search"
                type="text"
                placeholder="Search programs..."
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>

        <!-- <Select
            v-model="filterGroup"
            :options="[{ id: null, name: 'All Groups' }, ...programGroups]"
            option-label="name"
            option-value="id"
            placeholder="All Groups"
            class="text-xs !text-xs w-44"
        /> -->

        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ filteredPrograms.length }} programs</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Program Name</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Group</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="filteredPrograms.length === 0">
                    <td colspan="5" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                            <p class="text-sm font-medium text-slate-400">No programs found</p>
                            <p class="text-xs text-slate-300">Try adjusting your search or create a new program.</p>
                        </div>
                    </td>
                </tr>

                <tr
                    v-for="(program, index) in filteredPrograms"
                    :key="program.id"
                    class="hover:bg-slate-50/70 transition-colors"
                    :class="!program.is_active ? 'opacity-60' : ''"
                >
                    <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                        {{ String(index + 1).padStart(2, '0') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <Icon icon="hugeicons:book-open-01" class="text-sm text-indigo-500" />
                            </div>
                            <span class="text-sm font-semibold text-slate-700">{{ program.name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-full text-[11px] font-medium text-slate-600">
                            <Icon icon="hugeicons:folder-library" class="text-xs text-slate-400" />
                            {{ program.group?.name }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                            :class="program.is_active
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-slate-100 text-slate-500'"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full"
                                :class="program.is_active ? 'bg-emerald-500' : 'bg-slate-400'"
                            />
                            {{ program.is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            <!-- Edit -->
                            <button
                                type="button"
                                @click="openEdit(program)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                Edit
                            </button>
                            <!-- Disable / Enable -->
                            <button
                                type="button"
                                @click="changeStatus(program)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border transition-all"
                                :class="program.is_active
                                    ? 'border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50'
                                    : 'border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50'"
                            >
                                <Icon
                                    :icon="program.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'"
                                    class="text-xs"
                                />
                                {{ program.is_active ? 'Disabled' : 'Activate' }}
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- ── Create / Edit Modal ────────────────────────── -->
    <Dialog
        v-model:visible="showModal"
        :header="modalMode === 'create' ? 'New Program' : 'Edit Program'"
        modal
        :style="{ width: '420px' }"
        :pt="{
            header: { class: 'border-b border-slate-100 !py-4 !px-5' },
            title: { class: '!text-sm !font-bold !text-slate-800' },
            content: { class: '!p-5' },
        }"
    >
        <form @submit.prevent="submitForm" class="flex flex-col gap-4">

            <!-- Name -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Program Name <span class="text-red-400">*</span></label>
                <InputText
                    v-model="form.name"
                    placeholder="e.g. Breast Cancer"
                    class="!text-sm !py-2"
                    :class="form.errors.name ? '!border-red-400' : ''"
                    autofocus
                />
                <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
            </div>

            <!-- Group -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Program Group</label>
                <Select
                    v-model="form.parent_program_id"
                    :options="groups"
                    option-label="name"
                    option-value="id"
                    placeholder="Select a group..."
                    class="!text-sm w-full"
                    :class="form.errors.parent_program_id ? '!border-red-400' : ''"
                />
                <p v-if="form.errors.parent_program_id" class="text-[11px] text-red-500">{{ form.errors.parent_program_id }}</p>
            </div>

            <!-- Active toggle -->
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Active</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Program will be available for use</p>
                </div>
                <ToggleSwitch v-model="form.is_active" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="button"
                    @click="showModal = false"
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
                    {{ modalMode === 'create' ? 'Create Program' : 'Save Changes' }}
                </button>
            </div>

        </form>
    </Dialog>

</div>


</template>
