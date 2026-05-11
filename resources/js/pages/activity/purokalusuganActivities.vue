<script setup>
    import Main from '@/layouts/main.vue'
    import { ref } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import { Dialog } from 'primevue'


    defineOptions({ layout: Main })

    const toast = useToast()

    const props = defineProps({
        activities: { type: Object, default: () => ({}) },
        filters:    { type: Object, default: () => ({}) },
    })

    const deleteActivityModal = ref({
        visible: false,
        activity_id: null
    })

    const search = ref(props.filters.search ?? '')

    let debounceTimer
    const onSearch = () => {
        clearTimeout(debounceTimer)
        debounceTimer = setTimeout(() => {
            router.get('/pk-activities', { search: search.value }, { preserveState: true, replace: true })
        }, 300)
    }

    const confirmDeleteActivity = (activityID) => {
        deleteActivityModal.value.visible = true
        deleteActivityModal.value.activity_id = activityID
    }

    
    const confirmDelete = () => {
        router.delete(`/activity/${deleteActivityModal.value.activity_id}`, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Activity Deleted', detail: 'Activity has been deleted successfully.', life: 2000 })
                deleteActivityModal.value.visible = false
                deleteActivityModal.value.activity_id = null
            },
            onError: () => toast.add({ severity: 'error', summary: 'Failed to delete activity', life: 2000 }),
        })
    }


</script>

<template>
    <div class="h-full flex flex-col gap-4 md:gap-5 p-2 md:p-0">
    
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shrink-0">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Activities</h1>
                <p class="text-xs text-slate-400 mt-1">List and manage purokalusugan activities.</p>
            </div>
            <button
                type="button"
                @click="router.visit('/pk-activities/create')"
                class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                New Activity
            </button>
        </div>
    
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative w-full sm:max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    @input="onSearch"
                    type="text"
                    placeholder="Search activity..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
    
            <div class="hidden sm:flex ml-auto items-center gap-1.5 text-xs text-slate-400">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ activities.total }} activities</span>
            </div>
        </div>
    
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col flex-1 min-h-0">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 md:px-5 py-3 text-[10px] font-bold uppercase text-slate-400">#</th>
                            <th class="px-4 md:px-5 py-3 text-[10px] font-bold uppercase text-slate-400">Activity</th>
                            <th class="px-4 md:px-5 py-3 text-[10px] font-bold uppercase text-slate-400">Date</th>
                            <th class="hidden lg:table-cell px-5 py-3 text-[10px] font-bold uppercase text-slate-400">Participants</th>
                            <th class="hidden md:table-cell px-5 py-3 text-[10px] font-bold uppercase text-slate-400">Programs</th>
                            <th class="hidden sm:table-cell px-5 py-3 text-[10px] font-bold uppercase text-slate-400">Barangays</th>
                            <th class="px-4 md:px-5 py-3 text-[10px] font-bold uppercase text-slate-400 text-center">Clients</th>
                            <th class="px-4 md:px-5 py-3 text-[10px] font-bold uppercase text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-if="activities.data.length === 0">
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                    <p class="text-sm font-medium text-slate-400">No activities found</p>
                                </div>
                            </td>
                        </tr>
    
                        <tr
                            v-for="(activity, index) in activities.data"
                            :key="activity.id"
                            class="hover:bg-slate-50/70 transition-colors"
                        >
                            <td class="px-4 md:px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                                {{ index + 1 }}
                            </td>
    
                            <td class="px-4 md:px-5 py-3.5">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-semibold text-slate-900 leading-tight"> {{ activity.activity_name }}</span>
                                    <span class="sm:hidden text-[9px] text-indigo-500 font-medium uppercase mt-1">{{ activity.type }}</span>
                                </div>
                            </td>
    
                            <td class="px-4 md:px-5 py-3.5 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-medium text-slate-700">{{ activity.date_start }}</span>
                                    <span class="text-[10px] text-slate-400 sm:hidden">to {{ activity.date_end }}</span>
                                    <span class="hidden sm:inline text-[9px] text-slate-400 uppercase">{{ activity.type }} Scale</span>
                                </div>
                            </td>
    
                            <td class="hidden lg:table-cell px-5 py-3.5">
                                <span class="text-[10px] text-slate-600">{{ activity.participants_count }}</span>
                            </td>
    
                            <td class="hidden md:table-cell px-5 py-3.5">
                                <span class="text-[10px] text-slate-600">{{ activity.programs_count }}</span>
                            </td>
    
                            <td class="hidden sm:table-cell px-5 py-3.5">
                                <span class="text-[10px] text-slate-600">{{ activity.barangays_count }}</span>
                            </td>
    
                            <td class="px-4 md:px-5 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                {{ activity.total_clients }}
                            </td>
    
                            <td class="px-4 md:px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        @click="router.visit(`/pk-activities/${activity.id}/edit`)"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full sm:rounded-lg sm:border sm:border-slate-200 sm:px-2.5 sm:py-1.5 sm:text-[11px] transition-all"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-01" class="text-sm" />
                                        <!-- <span class="hidden sm:inline ml-1.5">Edit</span> -->
                                    </button>
                                    <button
                                        @click="confirmDeleteActivity(activity.id)"
                                        type="button"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full sm:rounded-lg sm:border sm:border-slate-200 sm:px-2.5 sm:py-1.5 sm:text-[11px] transition-all"
                                    >
                                        <Icon icon="hugeicons:delete-03" class="text-sm" />
                                        <!-- <span class="hidden sm:inline ml-1.5">Edit</span> -->
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0 mt-2">
            <p class="order-2 sm:order-1 text-[10px] md:text-xs text-slate-400">
                Showing {{ activities.from ?? 0 }}–{{ activities.to ?? 0 }} of {{ activities.total }}
            </p>
            <div class="order-1 sm:order-2 flex items-center gap-1 flex-wrap justify-center">
                <button
                    v-for="link in activities.links"
                    :key="link.label"
                    :disabled="!link.url"
                    @click="link.url && router.visit(link.url, { preserveState: true })"
                    class="min-w-[32px] h-8 flex items-center justify-center px-2 text-[10px] md:text-[11px] font-medium rounded-lg border transition-all"
                    :class="link.active
                        ? 'bg-indigo-600 border-indigo-600 text-white'
                        : 'border-slate-200 text-slate-500 hover:border-indigo-300 hover:bg-indigo-50 disabled:opacity-40'"
                    v-html="link.label"
                />
            </div>
        </div>
    
    </div>

    <Dialog
        v-model:visible="deleteActivityModal.visible"
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
                        Are you sure you want to delete this activity?
                        <!-- <span class="text-red-600 uppercase">{{ deleteModal.team?.name }}</span>? -->
                    </p>
                    <p class="text-xs text-slate-400 mt-1">
                        This will also remove all its related records. This action cannot be undone.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="button"
                    @click="deleteActivityModal.visible = false"
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
                    Delete Activity
                </button>
            </div>
        </div>
    </Dialog>
    </template>