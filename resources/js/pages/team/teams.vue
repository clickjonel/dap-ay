<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, inject } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import Dialog from 'primevue/dialog'
    import InputText from 'primevue/inputtext'
    import ToggleSwitch from 'primevue/toggleswitch'
    import { useToast } from 'primevue/usetoast'

    const user = inject('user')
    const toast = useToast()
    const search = ref('')
    defineOptions({ layout: Main })

    const props = defineProps({
        teams:   { type: Object, default: () => ({}) },
        filters: { type: Object, default: () => ({}) },
    })

    const teamModal = ref({ visible: false, mode: 'create', team: null })
    const form = useForm({ name: '', is_active: true, pk_kit: false, eo_link: '' })

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
        if (teamModal.value.mode === 'create') {
            form.post('/teams', {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Team Created', detail: 'Team has been created successfully.', life: 2000 })
                    teamModal.value.visible = false
                    form.reset()
                },
                onError: () => toast.add({ severity: 'error', summary: 'Failed to create team' }),
            })
        } else if (teamModal.value.mode === 'edit' && teamModal.value.team) {
            form.put(`/teams/${teamModal.value.team.id}`, {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Team Updated', detail: 'Team has been updated successfully.', life: 2000 })
                    teamModal.value.visible = false
                    form.reset()
                },
                onError: () => toast.add({ severity: 'error', summary: 'Failed to update team' }),
            })
        }
    }
</script>

<template>
<div class="h-full flex flex-col gap-5 lg:gap-6">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-start sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-base lg:text-lg font-bold text-slate-800 leading-none tracking-tight">Teams</h1>
            <p v-if="user.access_levels.access_level === 2" class="text-xs text-slate-400 mt-1">Showing teams where you are a member.</p>
            <p v-if="user.access_levels.access_level === 3" class="text-xs text-slate-400 mt-1">Showing teams within your jurisdiction.</p>
            <p v-if="user.access_levels.access_level === 1" class="text-xs text-slate-400 mt-1">Showing all teams within the region.</p>
        </div>
        <button
            type="button"
            @click="openTeamModal('create')"
            class="flex items-center gap-2 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-semibold rounded-lg transition-all shadow-sm shadow-indigo-200 shrink-0"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            <span class="hidden sm:inline">New Team</span>
            <span class="sm:hidden">New</span>
        </button>
    </div>

    <!-- ── Toolbar ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 sm:max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                v-model="search"
                type="text"
                placeholder="Search teams…"
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all placeholder:text-slate-300"
            />
        </div>
        <!-- Count pill -->
        <div class="ml-auto flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-[11px] font-semibold text-slate-500 shrink-0 tabular-nums">
            <Icon icon="hugeicons:add-team" class="text-sm text-slate-400" />
            {{ props.teams.data?.length ?? 0 }} teams
        </div>
    </div>

    <!-- ── Mobile cards / Desktop table ──────────────── -->
    <div class="flex-1 min-h-0 overflow-y-auto">

        <!-- ── Mobile card list ── -->
        <div class="flex flex-col gap-2.5 md:hidden">
            <div v-if="!props.teams.data?.length" class="flex flex-col items-center gap-2 py-16">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mb-1">
                    <Icon icon="hugeicons:folder-02" class="text-xl text-slate-300" />
                </div>
                <p class="text-sm font-semibold text-slate-400">No teams found</p>
                <p class="text-xs text-slate-300">Adjust your search or create a new team.</p>
            </div>

            <div
                v-for="team in props.teams.data"
                :key="team.id"
                class="bg-white rounded-xl border border-slate-200 px-4 py-3.5 transition-opacity"
                :class="!team.is_active ? 'opacity-50' : ''"
            >
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0 ring-1 ring-indigo-100">
                        <Icon icon="hugeicons:add-team" class="text-base text-indigo-500" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-700 truncate uppercase tracking-wide">{{ team.name }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[11px] text-slate-400">{{ team.members.length }} members · {{ team.barangays.length }} barangays</span>
                        </div>
                    </div>
                    <!-- Status dot -->
                    <span
                        class="w-2 h-2 rounded-full shrink-0"
                        :class="team.is_active ? 'bg-emerald-400' : 'bg-slate-300'"
                    />
                </div>

                <!-- Badges row -->
                <div class="flex items-center gap-1.5 mt-2.5">
                    <span v-if="team.pk_kit" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-semibold ring-1 ring-indigo-100">
                        <Icon icon="hugeicons:package-01" class="text-[10px]" /> PK Kit
                    </span>
                    <span v-if="team.eo_link" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-semibold ring-1 ring-amber-100">
                        <Icon icon="hugeicons:link-01" class="text-[10px]" /> EO Link
                    </span>
                    <span v-if="!team.is_active" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-400 text-[10px] font-semibold">
                        Inactive
                    </span>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                    <button
                        type="button"
                        @click="openTeamModal('edit', team)"
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-[11px] font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                    >
                        <Icon icon="hugeicons:pencil-edit-01" class="text-xs" /> Edit
                    </button>
                    <button
                        type="button"
                        @click="router.visit(`/teams/${team.id}/members`)"
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-[11px] font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                    >
                        <Icon icon="hugeicons:user-group" class="text-xs" /> Members
                    </button>
                    <button
                        type="button"
                        @click="router.visit(`/teams/${team.id}/barangays`)"
                        class="flex-1 flex items-center justify-center gap-1.5 py-1.5 text-[11px] font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                    >
                        <Icon icon="hugeicons:map-pinpoint-01" class="text-xs" /> Barangays
                    </button>
                </div>
            </div>
        </div>

        <!-- ── Desktop table ── -->
        <div class="hidden md:block bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm shadow-slate-100">

            <!-- Table header -->
            <div class="grid grid-cols-[3rem_1fr_10rem_10rem_14rem_14rem] border-b border-slate-100 bg-slate-50/80 px-2">
                <div class="px-3 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">#</div>
                <div class="px-3 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Team</div>
                <div class="px-3 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Members</div>
                <div class="px-3 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Scope</div>
                <div class="px-3 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">EO Link</div>
                <div class="px-3 py-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-right">Actions</div>
            </div>

            <!-- Empty state -->
            <div v-if="!props.teams.data?.length" class="flex flex-col items-center gap-2 py-20">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mb-1">
                    <Icon icon="hugeicons:folder-02" class="text-xl text-slate-300" />
                </div>
                <p class="text-sm font-semibold text-slate-400">No teams found</p>
                <p class="text-xs text-slate-300">Adjust your search or create a new team.</p>
            </div>

            <!-- Rows -->
            <div
                v-for="(team, index) in props.teams.data"
                :key="team.id"
                class="grid grid-cols-[3rem_1fr_10rem_10rem_14rem_14rem] items-center px-2 border-b border-slate-50 last:border-0 hover:bg-indigo-50/40 transition-colors group"
                :class="!team.is_active ? 'opacity-50' : ''"
            >
                <!-- # -->
                <div class="px-3 py-4 text-[11px] text-slate-300 font-bold tabular-nums">
                    {{ String(index + 1).padStart(2, '0') }}
                </div>

                <!-- Team name + badges -->
                <div class="px-3 py-4 flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 ring-1 ring-indigo-100 flex items-center justify-center shrink-0 group-hover:bg-indigo-100 transition-colors">
                        <Icon icon="hugeicons:add-team" class="text-sm text-indigo-500" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] font-bold text-slate-700 uppercase tracking-wide truncate leading-none">{{ team.name }}</p>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <!-- Status badge -->
                            <span
                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wide"
                                :class="team.is_active
                                    ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100'
                                    : 'bg-slate-100 text-slate-400'"
                            >
                                <span class="w-1 h-1 rounded-full" :class="team.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                                {{ team.is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <!-- PK Kit badge -->
                            <span v-if="team.pk_kit" class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-indigo-50 text-indigo-500 text-[9px] font-bold uppercase tracking-wide ring-1 ring-indigo-100">
                                <Icon icon="hugeicons:package-01" class="text-[9px]" /> PK Kit
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Members -->
                <div class="px-3 py-4">
                    <div class="flex items-center gap-1.5">
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-slate-50 border border-slate-100">
                            <Icon icon="hugeicons:user-group" class="text-[11px] text-slate-400" />
                            <span class="text-[11px] font-semibold text-slate-600 tabular-nums">{{ team.members.length }}</span>
                        </div>
                    </div>
                </div>

                <!-- Scope / Barangays -->
                <div class="px-3 py-4">
                    <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-slate-50 border border-slate-100 w-fit">
                        <Icon icon="hugeicons:map-pinpoint-01" class="text-[11px] text-slate-400" />
                        <span class="text-[11px] font-semibold text-slate-600 tabular-nums">{{ team.barangays.length }}</span>
                    </div>
                </div>

                <!-- EO Link -->
                <div class="px-3 py-4">
                    <a
                        v-if="team.eo_link"
                        :href="team.eo_link"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 text-[11px] font-medium text-indigo-500 hover:text-indigo-700 hover:underline truncate max-w-[11rem]"
                    >
                        <Icon icon="hugeicons:link-01" class="text-xs shrink-0" />
                        <span class="truncate">View EO</span>
                    </a>
                    <span v-else class="text-[11px] text-slate-300 italic">—</span>
                </div>

                <!-- Actions -->
                <div class="px-3 py-4 flex items-center justify-end gap-1.5">
                    <button
                        type="button"
                        @click="openTeamModal('edit', team)"
                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                    >
                        <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                        Edit
                    </button>
                    <button
                        type="button"
                        @click="router.visit(`/teams/${team.id}/members`)"
                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                    >
                        <Icon icon="hugeicons:user-group" class="text-xs" />
                        Members
                    </button>
                    <button
                        type="button"
                        @click="router.visit(`/teams/${team.id}/barangays`)"
                        class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                    >
                        <Icon icon="hugeicons:map-pinpoint-01" class="text-xs" />
                        Barangays
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Create / Edit Modal ────────────────────────── -->
    <Dialog
        v-model:visible="teamModal.visible"
        :header="teamModal.mode === 'create' ? 'New Team' : 'Edit Team'"
        modal
        :style="{ width: '95vw', maxWidth: '420px' }"
        :pt="{
            header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
            title:   { class: '!text-sm !font-bold !text-slate-800' },
            content: { class: '!p-5' },
        }"
    >
        <form @submit.prevent="submitTeam" class="flex flex-col gap-4">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Team Name <span class="text-red-400">*</span></label>
                <InputText
                    v-model="form.name"
                    placeholder="e.g. Team Alpha"
                    class="!text-sm !py-2"
                    :class="form.errors.name ? '!border-red-400' : ''"
                    autofocus
                />
                <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
            </div>

            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-700">Active</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Team will be available for use</p>
                </div>
                <ToggleSwitch v-model="form.is_active" />
            </div>

            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-700">PK KIT</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Team was given a PK Kit</p>
                </div>
                <ToggleSwitch v-model="form.pk_kit" />
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Executive Order Link</label>
                <InputText
                    v-model="form.eo_link"
                    placeholder="https://example.com/eo"
                    class="!text-sm !py-2"
                    :class="form.errors.eo_link ? '!border-red-400' : ''"
                />
                <p v-if="form.errors.eo_link" class="text-[11px] text-red-500">{{ form.errors.eo_link }}</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="button"
                    @click="teamModal.visible = false"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
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