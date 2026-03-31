<script setup>
    import { ref, computed, inject } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'

    import Main from '@/layouts/main.vue'
    import Dialog from 'primevue/dialog'
    import InputText from 'primevue/inputtext'
    import InputNumber from 'primevue/inputnumber'
    import Select from 'primevue/select'
    import ToggleSwitch from 'primevue/toggleswitch'

    const user = inject('user')
    const toast = useToast()

    defineOptions({ layout: Main })
    const props = defineProps({
        programs: { type: Array, default: () => [] },
        groups:   { type: Array, default: () => [] },
    })

    const showModal       = ref(false)
    const modalMode       = ref('create')
    const selectedProgram = ref(null)

    const search      = ref('')
    const filterGroup = ref(null)

    const filteredPrograms = computed(() =>
        props.programs.filter(p => {
            const matchSearch = p.name.toLowerCase().includes(search.value.toLowerCase())
            const matchGroup  = filterGroup.value ? p.parent_program_id === filterGroup.value : true
            return matchSearch && matchGroup
        })
    )

    const form = useForm({ name: '', parent_program_id: null, is_active: true })

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
        form.name              = program.name
        form.parent_program_id = program.parent_program_id
        form.is_active         = program.is_active === 1
        showModal.value = true
    }

    const submitForm = () => {
        if (modalMode.value === 'create') {
            form.post('/program', {
                onSuccess: () => {
                    showModal.value = false
                    form.reset()
                    toast.add({ severity: 'success', summary: 'Program Created', detail: 'The program has been successfully created.', life: 3000 })
                },
            })
        } else {
            form.put(`/program/${selectedProgram.value.id}`, {
                onSuccess: () => {
                    showModal.value = false
                    toast.add({ severity: 'success', summary: 'Program Updated', detail: 'The program has been successfully updated.', life: 3000 })
                },
            })
        }
    }

    const changeStatus = (program) => {
        useForm({}).delete(`/program/${program.id}`, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Status Updated', detail: 'Program status changed successfully.', life: 3000 })
            },
            preserveScroll: true,
        })
    }

    // ── Baseline modal ─────────────────────────────────────
    const showBaselineModal  = ref(false)
    const baselineProgram    = ref(null)
    const baselineForm = useForm({ 
        program_id: null,
        target: null 
    })

    const openBaseline = (program) => {
        baselineProgram.value = program
        baselineForm.program_id = program.id
        baselineForm.target = program.baseline?.target ?? null
        showBaselineModal.value = true
    }

    const submitBaseline = () => {
        baselineForm.post(`/program-baseline`, {
            onSuccess: () => {
                showBaselineModal.value = false
                baselineForm.reset()
                toast.add({ 
                    severity: 'success', 
                    summary: 'Baseline Set', 
                    detail: `Target for "${baselineProgram.value.name}" saved.`, 
                    life: 3000 
                })
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Error', detail: 'Please enter a valid target number.', life: 3000 })
            },
        })
    }
</script>

<template>
    <div class="h-full flex flex-col gap-4 lg:gap-5">

        <!-- Header -->
        <div class="flex items-start sm:items-center justify-between gap-3">
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Configuration</p>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Programs</h1>
                <p class="text-xs text-slate-400 mt-1">Manage health programs and their groups.</p>
            </div>
            <button
                v-if="user.access_levels?.access_level === 1"
                type="button"
                @click="openCreate"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">New Program</span>
                <span class="sm:hidden">New</span>
            </button>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-3">
            <div class="relative flex-1 sm:max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search programs..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400 shrink-0">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredPrograms.length }} <span class="hidden sm:inline">programs</span></span>
            </div>
        </div>

        <!-- Table / Cards -->
        <div class="flex-1 min-h-0 overflow-y-auto">

            <!-- Mobile cards -->
            <div class="flex flex-col gap-3 md:hidden">
                <div v-if="filteredPrograms.length === 0" class="flex flex-col items-center gap-2 py-16">
                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                    <p class="text-sm font-medium text-slate-400">No programs found</p>
                    <p class="text-xs text-slate-300">Try adjusting your search or create a new program.</p>
                </div>

                <div
                    v-for="program in filteredPrograms"
                    :key="program.id"
                    class="bg-white rounded-xl border border-slate-200 px-4 py-3.5 flex items-center gap-3"
                    :class="!program.is_active ? 'opacity-60' : ''"
                >
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:book-open-01" class="text-base text-indigo-500" />
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ program.name }}</p>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 rounded-full text-[10px] font-medium text-slate-500">
                                <Icon icon="hugeicons:folder-library" class="text-xs" />
                                {{ program.group?.name ?? '—' }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                :class="program.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                            >
                                <span class="w-1.5 h-1.5 rounded-full" :class="program.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                                {{ program.is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </div>
                    </div>

                    <div v-if="user.access_levels?.access_level === 1" class="flex items-center gap-1.5 shrink-0">
                        <button type="button" @click="openBaseline(program)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-violet-300 hover:text-violet-600 hover:bg-violet-50 transition-all"
                            title="Set Baseline"
                        >
                            <Icon icon="hugeicons:target-02" class="text-sm" />
                        </button>
                        <button type="button" @click="openEdit(program)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                        >
                            <Icon icon="hugeicons:pencil-edit-01" class="text-sm" />
                        </button>
                        <button type="button" @click="changeStatus(program)"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border transition-all"
                            :class="program.is_active
                                ? 'border-slate-200 text-slate-400 hover:border-red-300 hover:text-red-500 hover:bg-red-50'
                                : 'border-slate-200 text-slate-400 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50'"
                        >
                            <Icon :icon="program.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'" class="text-sm" />
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
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Program Name</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Group</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                            <th v-if="user.access_levels?.access_level === 1" class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

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
                            :class="!program.is_active ? 'opacity-55' : ''"
                        >
                            <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                                {{ String(index + 1).padStart(2, '0') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                        <Icon icon="hugeicons:book-open-01" class="text-sm text-indigo-500" />
                                    </div>
                                    <span class="text-sm font-medium text-slate-900">{{ program.name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-full text-[11px] font-medium text-slate-600">
                                    <Icon icon="hugeicons:folder-library" class="text-xs text-slate-400" />
                                    {{ program.group?.name ?? '—' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                    :class="program.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="program.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                                    {{ program.is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td v-if="user.access_levels?.access_level === 1" class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" @click="openBaseline(program)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-violet-300 hover:text-violet-600 hover:bg-violet-50 transition-all"
                                    >
                                        <Icon icon="hugeicons:target-02" class="text-xs" />
                                        Set Baseline
                                    </button>
                                    <button type="button" @click="openEdit(program)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                        Edit
                                    </button>
                                    <button type="button" @click="changeStatus(program)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border transition-all"
                                        :class="program.is_active
                                            ? 'border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50'
                                            : 'border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50'"
                                    >
                                        <Icon :icon="program.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'" class="text-xs" />
                                        {{ program.is_active ? 'Disable' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>

        <!-- Create / Edit Modal -->
        <Dialog
            v-model:visible="showModal"
            :header="modalMode === 'create' ? 'New Program' : 'Edit Program'"
            modal
            :style="{ width: '95vw', maxWidth: '420px' }"
            :pt="{
                header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
                title:   { class: '!text-sm !font-bold !text-slate-800' },
                content: { class: '!p-5' },
            }"
        >
            <form @submit.prevent="submitForm" class="flex flex-col gap-4">
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

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                    <div>
                        <p class="text-xs font-semibold text-slate-700">Active</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Program will be available for use</p>
                    </div>
                    <ToggleSwitch v-model="form.is_active" />
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button type="submit" :disabled="form.processing"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                    >
                        <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        {{ modalMode === 'create' ? 'Create Program' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </Dialog>

        <!-- Set Baseline Modal -->
        <Dialog
            v-model:visible="showBaselineModal"
            header="Set Baseline Target"
            modal
            :style="{ width: '95vw', maxWidth: '380px' }"
            :pt="{
                header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
                title:   { class: '!text-sm !font-bold !text-slate-800' },
                content: { class: '!p-5' },
            }"
        >
            <form @submit.prevent="submitBaseline" class="flex flex-col gap-4">

                <!-- Program context pill -->
                <div class="flex items-center gap-2.5 px-3 py-2.5 bg-violet-50 border border-violet-100 rounded-lg">
                    <div class="w-6 h-6 rounded-md bg-violet-100 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:book-open-01" class="text-xs text-violet-600" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold text-violet-400 uppercase tracking-wider leading-none mb-0.5">Program</p>
                        <p class="text-xs font-semibold text-violet-700 truncate">{{ baselineProgram?.name }}</p>
                    </div>
                </div>

                <!-- Target input -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">
                        Target Number <span class="text-red-400">*</span>
                    </label>
                    <InputNumber
                        v-model="baselineForm.target"
                        placeholder="e.g. 500"
                        :min="0"
                        :useGrouping="false"
                        class="w-full"
                        inputClass="!text-sm !py-2 w-full"
                        :class="baselineForm.errors.target ? '!border-red-400' : ''"
                        autofocus
                    />
                    <p v-if="baselineForm.errors.target" class="text-[11px] text-red-500">{{ baselineForm.errors.target }}</p>
                    <p class="text-[11px] text-slate-400">Enter the total number of clients targeted for this program.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" @click="showBaselineModal = false"
                        class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button type="submit" :disabled="baselineForm.processing"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 disabled:opacity-60 rounded-lg transition-colors"
                    >
                        <Icon v-if="baselineForm.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        Save Baseline
                    </button>
                </div>

            </form>
        </Dialog>

    </div>
</template>