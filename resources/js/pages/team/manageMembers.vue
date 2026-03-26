<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, computed } from 'vue'
    import { router, useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import InputText from 'primevue/inputtext'
    import ToggleSwitch from 'primevue/toggleswitch'
    import Dialog from 'primevue/dialog'
    import Select from 'primevue/select'

    defineOptions({ layout: Main })

    const toast = useToast()

    const props = defineProps({
        team:    { type: Object, default: () => ({}) },
        members: { type: Array,  default: () => []   },
        users:   { type: Array,  default: () => []   },
    })

    const roles = [
        'Nurse (NHWSS)', 
        'Midwife (NHWSS)', 
        'DOH-deployed HRH', 
        'LGU Nurse', 
        'LGU Midwife',
        'Nutritionist Dietician',
        'Medical Technologist',
        'Barangay Health Worker (BHW)',
        'BLGU',
        'BNS',
        'BVSI',
        'BHERT',
        'Purok Leader', 
        'Parent Leader', 
        'Barangay Councilor',
        'Committee Chairperson on Health',
        'Medical Officer',
    ]

    const search = ref('')
    const filteredMembers = computed(() =>
        props.members.filter(m =>
            m.name.toLowerCase().includes(search.value.toLowerCase())
        )
    )

    const memberModal = ref({ visible: false, mode: 'create', member: null })

    const form = useForm({ name: '', role: null, position: '', user_id: null, pk_oriented: false })

    const openCreate = () => {
        form.reset()
        memberModal.value = { visible: true, mode: 'create', member: null }
    }

    const openEdit = (member) => {
        form.name        = member.name
        form.role        = member.role
        form.position    = member.position
        form.user_id     = member.user_id
        form.pk_oriented = member.pk_oriented
        memberModal.value = { visible: true, mode: 'edit', member }
    }

    const submit = () => {
        if (memberModal.value.mode === 'create') {
            form.post(`/teams/${props.team.id}/members`, {
                preserveScroll: true,
                onSuccess: () => {
                    memberModal.value.visible = false
                    form.reset()
                    toast.add({ severity: 'success', summary: 'Member Added', detail: 'Member has been added successfully.', life: 2000 })
                },
            })
        } else {
            form.put(`/teams/${props.team.id}/members/${memberModal.value.member.id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    memberModal.value.visible = false
                    form.reset()
                    toast.add({ severity: 'success', summary: 'Member Updated', detail: 'Member has been updated successfully.', life: 2000 })
                },
            })
        }
    }

    const removeMember = (member) => {
        useForm({}).delete(`/teams/${props.team.id}/members/${member.id}`, {
            preserveScroll: true,
            onSuccess: () => toast.add({ severity: 'success', summary: 'Member Removed', detail: 'Member has been removed successfully.', life: 2000 }),
        })
    }

    const linkedUser = (member) => member.user ? member.user.name : 'No linked user'
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
                    <h1 class="text-base lg:text-lg font-bold text-slate-800 leading-none">{{ team.name }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Manage team members.</p>
                </div>
            </div>
            <button
                type="button"
                @click="openCreate"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">Add Member</span>
                <span class="sm:hidden">Add</span>
            </button>
        </div>

        <!-- ── Filters ────────────────────────────────────────── -->
        <div class="flex items-center gap-3 shrink-0">
            <div class="relative flex-1 sm:max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search members..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400 shrink-0">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredMembers.length }} <span class="hidden sm:inline">members</span></span>
            </div>
        </div>

        <!-- ── Mobile cards / Desktop table ──────────────── -->
        <div class="flex-1 min-h-0 overflow-y-auto">

            <!-- Mobile card list -->
            <div class="flex flex-col gap-3 md:hidden">

                <div v-if="filteredMembers.length === 0" class="flex flex-col items-center gap-2 py-16">
                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                    <p class="text-sm font-medium text-slate-400">No members found</p>
                    <p class="text-xs text-slate-300">Try adjusting your search or add a new member.</p>
                </div>

                <div
                    v-for="member in filteredMembers"
                    :key="member.id"
                    class="bg-white rounded-xl border border-slate-200 px-4 py-3.5"
                >
                    <!-- Member info -->
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                            <Icon icon="hugeicons:user-circle" class="text-base text-indigo-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ member.name }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ linkedUser(member) }}</p>
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 rounded-full text-[10px] font-medium text-indigo-600">
                                    {{ member.role }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 rounded-full text-[10px] font-medium text-slate-500">
                                    {{ member.position }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                    :class="member.pk_oriented ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="member.pk_oriented ? 'bg-emerald-500' : 'bg-slate-400'" />
                                    PK {{ member.pk_oriented ? 'Oriented' : 'Not Oriented' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                        <button
                            type="button"
                            @click="openEdit(member)"
                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                        >
                            <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                            Edit
                        </button>
                        <button
                            type="button"
                            @click="removeMember(member)"
                            class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50 transition-all"
                        >
                            <Icon icon="hugeicons:cancel-01" class="text-xs" />
                            Remove
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
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Name</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Role</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Position</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">PK Oriented</th>
                            <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

                        <tr v-if="filteredMembers.length === 0">
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                    <p class="text-sm font-medium text-slate-400">No members found</p>
                                    <p class="text-xs text-slate-300">Try adjusting your search or add a new member.</p>
                                </div>
                            </td>
                        </tr>

                        <tr
                            v-for="(member, index) in filteredMembers"
                            :key="member.id"
                            class="hover:bg-slate-50/70 transition-colors"
                        >
                            <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                                {{ String(index + 1).padStart(2, '0') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                                        <Icon icon="hugeicons:user-circle" class="text-sm text-indigo-400" />
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-700">{{ member.name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ linkedUser(member) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 rounded-full text-[11px] font-medium text-indigo-600">
                                    {{ member.role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">{{ member.position }}</td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                    :class="member.pk_oriented ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="member.pk_oriented ? 'bg-emerald-500' : 'bg-slate-400'" />
                                    {{ member.pk_oriented ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        @click="openEdit(member)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        @click="removeMember(member)"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50 transition-all"
                                    >
                                        <Icon icon="hugeicons:cancel-01" class="text-xs" />
                                        Remove
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ── Add / Edit Member Modal ────────────────────────── -->
    <Dialog
        v-model:visible="memberModal.visible"
        modal
        append-to="body"
        :style="{ width: '95vw', maxWidth: '460px' }"
        :pt="{
            header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
            title:   { class: '!text-sm !font-bold !text-slate-800' },
            content: { class: '!p-5' },
        }"
    >
        <template #header>
            <div class="flex flex-col gap-0.5">
                <span class="text-sm font-bold text-slate-800">{{ memberModal.mode === 'create' ? 'Add Member' : 'Edit Member' }}</span>
                <span class="text-[11px] text-slate-400 font-normal">{{ team.name }}</span>
            </div>
        </template>

        <form @submit.prevent="submit" class="flex flex-col gap-4">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Name <span class="text-red-400">*</span></label>
                <InputText
                    v-model="form.name"
                    placeholder="e.g. Juan Dela Cruz"
                    class="!text-sm !py-2"
                    :class="form.errors.name ? '!border-red-400' : ''"
                    autofocus
                />
                <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
            </div>

            <!-- Role -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Role <span class="text-red-400">*</span></label>
                <Select
                    v-model="form.role"
                    :options="roles"
                    placeholder="Select role"
                    filter
                    class="!text-sm !w-full !max-w-full"
                    :class="form.errors.role ? '!border-red-400' : ''"
                    :pt="{ 
                        root: { class: 'w-full flex overflow-hidden' },
                        label: { class: 'truncate flex-1' } 
                    }"
                />
                <p v-if="form.errors.role" class="text-[11px] text-red-500">{{ form.errors.role }}</p>
            </div>

            <!-- Position -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Position <span class="text-red-400">*</span></label>
                <InputText
                    v-model="form.position"
                    placeholder="e.g. Nurse"
                    class="!text-sm !py-2 w-full"
                    :class="form.errors.position ? '!border-red-400' : ''"
                />
                <p v-if="form.errors.position" class="text-[11px] text-red-500">{{ form.errors.position }}</p>
            </div>

            <!-- Linked User -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">
                    Linked User <span class="text-slate-300 font-normal">(optional)</span>
                </label>
                <Select
                    v-model="form.user_id"
                    :options="props.users"
                    option-label="name"
                    option-value="id"
                    placeholder="Select user"
                    filter
                    show-clear
                    class="!text-sm !w-full !max-w-full"
                    :pt="{ root: { class: 'w-full' } }"
                    @change="(e) => { const u = props.users.find(u => u.id === e.value); if (u) form.name = u.name }"
                />
            </div>

            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-700">PK Oriented</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Member has received PK orientation</p>
                </div>
                <ToggleSwitch v-model="form.pk_oriented" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100">
                <button
                    type="button"
                    @click="memberModal.visible = false"
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
                    {{ memberModal.mode === 'create' ? 'Add Member' : 'Save Changes' }}
                </button>
            </div>

        </form>
    </Dialog>
</template>
