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

    // ── Delete ─────────────────────────────────────────────
    const deleteModal = ref({ visible: false, team: null })

    const openDeleteModal = (team) => {
        deleteModal.value.team = team
        deleteModal.value.visible = true
    }

    const confirmDelete = () => {
        router.delete(`/team/${deleteModal.value.team.id}`, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Team Deleted', detail: 'Team has been deleted successfully.', life: 2000 })
                deleteModal.value.visible = false
                deleteModal.value.team = null
            },
            onError: () => toast.add({ severity: 'error', summary: 'Failed to delete team', life: 2000 }),
        })
    }

    const goToPage = (url) => {
        if (!url) return
        router.get(url, { search: search.value }, {
            preserveState:  true,
            preserveScroll: true,
        })
    }
</script>

<template>
<div class="h-full flex flex-col gap-4">
        
        <div class="w-full flex justify-between items-center">
            <div class="w-full flex justify-between items-center">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Search</label>
                    <input @input="onSearch" :value="search" type="text" class="w-full lg:w-[300px] border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400 text-xs" placeholder="Input Keyword">
                </div>

                <button @click="router.visit('/teams/create')" class="px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-emerald-900 transition-colors cursor-pointer">Add New Team</button>

            </div>
        </div>

        <!-- ── Table ─────────────────────────────────────── -->
        <div class="overflow-y-auto px-2 pb-2">
            <div class="w-full grid grid-cols-12 divide-x divide-gray-400 text-xs font-semibold tracking-widest uppercase text-gray-600 bg-sky-100 border border-gray-400 sticky top-0">
                <span class="col-span-2 px-2 py-4">Team</span>
                <span class="col-span-2 px-2 py-4">PK Kit</span>
                <span class="col-span-2 px-2 py-4">EO Link</span>
                <span class="col-span-2 px-2 py-4">Members</span>
                <span class="col-span-2 px-2 py-4">Barangays</span>
                <span class="col-span-2 px-2 py-4">Actions</span>
            </div>
            <div v-for="team in teams.data" :key="team.id" class="w-full grid grid-cols-12 divide-x divide-gray-400 text-xs text-gray-600 border-b border-x border-gray-400 bg-white tracking-wide font-medium">
                <span class="col-span-2 p-2 uppercase">{{ team.name}}</span>
                <span class="col-span-2 p-2 uppercase">{{ team.pk_kit ? 'Issued' : 'None' }}</span>
                <span class="col-span-2 p-2 uppercase break-words">
                    <a v-if="team.eo_link" :href="team.eo_link" target="_blank" rel="noopener noreferrer" class="text-blue-600 cursor-pointer hover:underline">
                        View
                    </a>
                </span>
                <span class="col-span-2 p-2 uppercase"> {{ team.members.length > 0 ? `${team.members.length} Members` : '' }} </span>
                <span class="col-span-2 p-2 uppercase"> {{ team.barangays.length > 0 ? `${team.barangays.length} Barangays` : '' }} </span>
                <span class="col-span-2 p-2">
                    <button @click="router.visit(`/teams/${team.id}/edit`)" class="p-1 text-gray-400 hover:text-green-800 hover:bg-green-50 border border-transparent hover:border-green-200 transition-all" title="Edit Barangay">
                        <Icon icon="boxicons:edit" class="text-base" />
                    </button>
                    <button @click="openDeleteModal(team)" class="p-1 text-gray-400 hover:text-red-400 hover:bg-red-50 border border-transparent hover:border-red-200 transition-all" title="Delete Barangay">
                        <Icon icon="mdi:trash-can-outline" class="text-base" />
                    </button>
                </span>
            </div>

           

        </div>

         <!-- ── Pagination ─────────────────────────────── -->
         <div class="w-full flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-white shrink-0">

            <span class="text-xs text-slate-400">
                Showing {{ teams.length }} Records Per Page
            </span>

            <div class="flex items-center gap-1.5">
                <button
                    @click="goToPage(teams.prev_page_url)"
                    :disabled="!teams.prev_page_url"
                    class="h-8 p-2.5 flex items-center gap-1 text-sm shadow-md text-slate-900 border border-slate-400 transition-all disabled:opacity-30 cursor-pointer hover:bg-sky-400 hover:text-white disabled:cursor-not-allowed"
                >
                    <Icon icon="lucide:chevron-left" class="text-sm"/>
                    
                </button>
                <button
                    @click="goToPage(teams.next_page_url)"
                    :disabled="!teams.next_page_url"
                    class="h-8 p-2.5 flex items-center gap-1 text-sm shadow-md text-slate-900 border border-slate-400 transition-all disabled:opacity-30 cursor-pointer hover:bg-sky-400 hover:text-white disabled:cursor-not-allowed"
                >
                    <Icon icon="lucide:chevron-right" class="text-sm"/>
                </button>
            </div>

        </div>

        <Dialog
            v-model:visible="deleteModal.visible"
            header="Delete Team"
            modal
            :style="{ width: '95vw', maxWidth: '380px' }"
            :pt="{
                header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
                title:   { class: '!text-sm !font-bold !text-slate-800' },
                content: { class: '!p-5' },
            }"
        >
        <div class="flex flex-col gap-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                    <Icon icon="hugeicons:delete-02" class="text-base text-red-500" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">
                        Are you sure you want to delete
                        <span class="text-red-600 uppercase">{{ deleteModal.team?.name }}</span>?
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        This will also remove all its members and barangay assignments. This action cannot be undone.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="button"
                    @click="deleteModal.visible = false"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    @click="confirmDelete"
                    class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                >
                    <Icon icon="hugeicons:delete-02" class="text-sm" />
                    Delete Team
                </button>
            </div>
        </div>
    </Dialog>

    </div>
</template>