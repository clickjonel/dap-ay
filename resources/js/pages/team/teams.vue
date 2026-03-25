<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, inject } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import Dialog from 'primevue/dialog'
    import InputText from 'primevue/inputtext'
    import ToggleSwitch from 'primevue/toggleswitch'
    import { useToast } from 'primevue/usetoast'

    defineOptions({ layout: Main })

    const user = inject('user')
    const toast = useToast()

    const props = defineProps({
        teams:   { type: Object, default: () => ({}) },
        filters: { type: Object, default: () => ({}) },
    })

    // ── Search ─────────────────────────────────────────────
    const search = ref(props.filters.search ?? '')

    let searchTimeout = null
    const onSearch = (e) => {
        search.value = e.target.value
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(() => {
            router.get('/teams', { search: search.value }, {
                preserveState:  true,
                preserveScroll: true,
                replace:        true,
            })
        }, 400)
    }

    // ── Pagination ─────────────────────────────────────────
    const goToPage = (url) => {
        if (!url) return
        router.get(url, { search: search.value }, {
            preserveState:  true,
            preserveScroll: true,
        })
    }

    // ── Modal ──────────────────────────────────────────────
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
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Teams</h1>
            <p v-if="user.access_levels.access_level === 2" class="text-xs text-slate-400 mt-1">Showing teams where you are a member.</p>
            <p v-if="user.access_levels.access_level === 3" class="text-xs text-slate-400 mt-1">Showing teams within your jurisdiction.</p>
            <p v-if="user.access_levels.access_level === 1" class="text-xs text-slate-400 mt-1">Showing all teams within the region.</p>
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
                :value="search"
                @input="onSearch"
                type="text"
                placeholder="Search teams..."
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>
        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ props.teams.total ?? 0 }} teams</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1 flex flex-col">
        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Team Name</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Members</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Barangays</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">EO Link</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="!props.teams.data?.length">
                    <td colspan="6" class="px-5 py-16 text-center">
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
                    <!-- # -->
                    <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums w-10">
                        {{ String(index + 1).padStart(2, '0') }}
                    </td>

                    <!-- Team name -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <Icon icon="hugeicons:add-team" class="text-sm text-indigo-500" />
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-semibold text-slate-700 truncate uppercase">{{ team.name }}</span>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] font-semibold"
                                        :class="team.is_active ? 'text-emerald-500' : 'text-slate-400'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" :class="team.is_active ? 'bg-emerald-400' : 'bg-slate-300'" />
                                        {{ team.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span v-if="team.pk_kit" class="inline-flex items-center gap-1 text-[10px] font-semibold text-indigo-400">
                                        <Icon icon="hugeicons:package-01" class="text-[10px]" /> PK Kit
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Members -->
                    <td class="px-5 py-3.5 text-xs text-slate-500">
                        {{ team.members.length }} members
                    </td>

                    <!-- Barangays -->
                    <td class="px-5 py-3.5 text-xs text-slate-500">
                        {{ team.barangays.length }} barangays
                    </td>

                    <!-- EO Link -->
                    <td class="px-5 py-3.5">
                        <a
                            v-if="team.eo_link"
                            :href="team.eo_link"
                            target="_blank"
                            class="inline-flex items-center gap-1 text-xs text-indigo-500 hover:text-indigo-700 hover:underline"
                        >
                            <Icon icon="hugeicons:link-01" class="text-xs shrink-0" />
                            View EO
                        </a>
                        <span v-else class="text-xs text-slate-300">—</span>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-3.5" @click.stop>
                        <div class="flex items-center justify-end gap-1">
                            <button
                                type="button"
                                @click="openTeamModal('edit', team)"
                                class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                title="Edit Team"
                            >
                                <Icon icon="hugeicons:pencil-edit-02" class="text-sm" />
                            </button>
                            <button
                                type="button"
                                @click="router.visit(`/teams/${team.id}/members`)"
                                class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                title="Manage Members"
                            >
                                <Icon icon="hugeicons:user-group" class="text-sm" />
                            </button>
                            <button
                                type="button"
                                @click="router.visit(`/teams/${team.id}/barangays`)"
                                class="p-1.5 rounded-md text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors"
                                title="Manage Barangays"
                            >
                                <Icon icon="hugeicons:map-pinpoint-01" class="text-sm" />
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- ── Pagination ─────────────────────────────── -->
        <div
            v-if="props.teams.last_page > 1"
            class="flex items-center justify-between px-5 py-3 border-t border-slate-100 mt-auto"
        >
            <span class="text-xs text-slate-400">
                Showing {{ props.teams.from }}–{{ props.teams.to }} of {{ props.teams.total }}
            </span>
            <div class="flex gap-1">
                <button
                    v-for="link in props.teams.links"
                    :key="link.label"
                    :disabled="!link.url || link.active"
                    @click="goToPage(link.url)"
                    class="px-3 py-1.5 text-[11px] font-medium rounded-lg border transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                    :class="link.active
                        ? 'bg-indigo-600 text-white border-indigo-600'
                        : 'border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50'"
                    v-html="link.label"
                />
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