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
    import { router } from '@inertiajs/vue3'


    const toast = useToast()
    const search = ref('')
    defineOptions({ layout: Main })

    const props = defineProps({
        teams: { type: Object, default: () => [] },
        filters:   { type: Object, default: () => ({}) },
    })

    const teamModal = ref({
        visible:false,
        mode:'create',
        team:null
    })

    const form = useForm({
        name: '',
        is_active: true,
        pk_kit: false,
        eo_link: '',
    })

    const openTeamModal = (mode, team = null) => {
        teamModal.value.mode = mode
        teamModal.value.team = team

        if (mode === 'edit' && team) {
            form.name      = team.name
            form.is_active = team.is_active
            form.pk_kit    = team.pk_kit
            form.eo_link   = team.eo_link ?? ''
        } else {
            form.reset()
        }

        teamModal.value.visible = true
    }

    const submitTeam = () => {
        if(teamModal.value.mode === 'create') {
            form.post('/teams', {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Team Created', detail: 'Team has been created successfully. Update necessary details using the update form.', life: 2000 })
                    teamModal.value.visible = false
                    form.reset()
                },
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Failed to create team' })
                }
            })
        } 
        else if(teamModal.value.mode === 'edit' && teamModal.value.team) {
            form.put(`/teams/${teamModal.value.team.id}`, {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Team Updated', detail: 'Team has been updated successfully.', life: 2000 })
                    teamModal.value.visible = false
                    form.reset()
                },
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Failed to update team' })
                }
            })
        }
    }

</script>

<template>
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Teams</h1>
            <p class="text-xs text-slate-400 mt-1">Showing teams where you are a member of.</p>
            <p class="text-xs text-slate-400 mt-1">Showing teams which are on your jurisdiction.</p>
            <p class="text-xs text-slate-400 mt-1">Showing all teams within the region.</p>
        </div>
        <button
            type="button"
            @click="openTeamModal('create')"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            New Team
        </button>
    </div>

    <!-- ── Filters ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                v-model="search"
                type="text"
                placeholder="Search team..."
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>

        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ teams.length }} teams</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Team Name</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Members</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Scope</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="props.teams.data.length === 0">
                    <td colspan="5" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                            <p class="text-sm font-medium text-slate-400">No teams found</p>
                            <p class="text-xs text-slate-300">Try adjusting your search or create a new team.</p>
                        </div>
                    </td>
                </tr>

                <tr
                    v-for="(team, index) in props.teams.data"
                    :key="team.id"
                    class="hover:bg-slate-50/70 transition-colors"
                    :class="!team.is_active ? 'opacity-60' : ''"
                >
                    <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                        {{ String(index + 1).padStart(2, '0') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <Icon icon="hugeicons:add-team" class="text-sm text-indigo-500" />
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ team.name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <!-- <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-full text-[11px] font-medium text-slate-600">
                            <Icon icon="hugeicons:folder-library" class="text-xs text-slate-400" />
                            {{ team.group?.name }}
                        </span> -->
                    </td>
                    <td class="px-5 py-3.5">
                        <!-- <span
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
                        </span> -->
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">

                            <button
                                type="button"
                                @click="router.visit(`/teams/${team.id}/edit`)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                Edit
                            </button>

                            <button
                                type="button"
                                @click="router.visit(`/teams/${team.id}/members`)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:user-group" class="text-xs" />
                                Members
                            </button>

                            <button
                                type="button"
                                @click="router.visit(`/teams/${team.id}/barangays`)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:map-pinpoint-01" class="text-xs" />
                                Barangays
                            </button>

                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- ── Create / Edit Modal ────────────────────────── -->
    <Dialog
        v-model:visible="teamModal.visible"
        :header="teamModal.mode === 'create' ? 'New Team' : 'Edit Team'"
        modal
        :style="{ width: '420px' }"
        :pt="{
            header: { class: 'border-b border-slate-100 !py-4 !px-5' },
            title: { class: '!text-sm !font-bold !text-slate-800' },
            content: { class: '!p-5' },
        }"
    >
        <form @submit.prevent="submitTeam" class="flex flex-col gap-4">

            <!-- Name -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Team Name <span class="text-red-400">*</span></label>
                <InputText
                    v-model="form.name"
                    placeholder="e.g. Breast Cancer"
                    class="!text-sm !py-2"
                    :class="form.errors.name ? '!border-red-400' : ''"
                    autofocus
                />
                <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
            </div>

            <!-- Active toggle -->
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Active</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Team will be available for use</p>
                </div>
                <ToggleSwitch v-model="form.is_active" />
            </div>

            <!-- PK KIT toggle -->
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-700">PK KIT</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Team was given PK KIT</p>
                </div>
                <ToggleSwitch v-model="form.pk_kit" />
            </div>

            <!-- EO Link -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Execitive Order Link</label>
                <InputText
                    v-model="form.eo_link"
                    placeholder="e.g. https://example.com/eo"
                    class="!text-sm !py-2"
                    :class="form.errors.eo_link ? '!border-red-400' : ''"
                />
                <p v-if="form.errors.eo_link" class="text-[11px] text-red-500">{{ form.errors.eo_link }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="button"
                    @click="teamModal.visible = false"
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
                    {{ teamModal.mode === 'create' ? 'Create Team' : 'Save Changes' }}
                </button>
            </div>

        </form>
    </Dialog>
  

</div>


</template>
