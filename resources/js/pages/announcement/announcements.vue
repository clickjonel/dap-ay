<script setup>
    import { ref, computed, inject } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'

    import Main from '@/layouts/main.vue'
    import Editor from 'primevue/editor'
    import { Button, Dialog, Select } from 'primevue'
    import InputText from 'primevue/inputtext'

    const user = inject('user')
    const toast = useToast()

    defineOptions({ layout: Main })
    const props = defineProps({
        announcements: { type: Array, default: () => [] },
    })

    const search = ref('')

    const filteredAnnouncements = computed(() =>
        props.announcements.filter(t =>
            t.title.toLowerCase().includes(search.value.toLowerCase())
        )
    )

    const announcementModal = ref({
        visible: false,
        mode: null,
        announcement: null
    })

    const form = useForm({
        title: null,
        announcement: null,
        target_access_level: null
    })

    function openAnnouncementModal(announcement = null, mode) {
        announcementModal.value.mode = mode
        announcementModal.value.glossary = announcement
        announcementModal.value.visible = true
        form.reset()
        form.clearErrors()
        if (announcement) {
            form.title = announcement.title
            form.announcement = announcement.announcement
            form.target_access_level = announcement.target_access_level
        }
    }

    function saveAnnouncement() {
        if (announcementModal.value.mode === 'add') {
            form.post('/announcements', {
                onSuccess: () => {
                    announcementModal.value.visible = false
                    toast.add({ severity: 'success', summary: 'Announcement added', life: 3000 })
                    form.resetAndClearErrors()
                },
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Failed to add announcement', detail: 'Please check the form and try again.', life: 4000 })
                }
            })
        } else {
            form.patch(`/announcements/${announcementModal.value.glossary.id}`, {
                onSuccess: () => {
                    announcementModal.value.visible = false
                    toast.add({ severity: 'success', summary: 'Announcement updated', life: 3000 })
                    form.resetAndClearErrors()
                },
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Failed to update announcement', detail: 'Please check the form and try again.', life: 4000 })
                }
            })
        }
    }

    function deleteAnnouncement(announcement) {
        if (!confirm(`Delete "${announcement.title}"?`)) return
        router.delete(`/announcements/${announcement.id}`, {
            onSuccess: () => toast.add({ severity: 'success', summary: 'Announcement deleted', life: 3000 }),
            onError: () => toast.add({ severity: 'error', summary: 'Failed to delete announcement', life: 3000 }),
        })
    }

</script>

<template>
    <div class="h-full flex flex-col gap-4 lg:gap-5">

        <!-- Header -->
        <div class="flex items-start sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Announcements</h1>
                <p class="text-xs text-slate-400 mt-1">Set Announcement to users.</p>
            </div>
            <button
                @click="openAnnouncementModal(null,'add')"
                type="button"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">New Announcement</span>
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
                    placeholder="Search Announcement..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400 shrink-0">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredAnnouncements.length }} <span class="hidden sm:inline">Announcements</span></span>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-1 min-h-0 overflow-y-auto">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <table class="w-full text-xs table-fixed">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-left">
                            <th class="px-4 py-3 font-semibold text-slate-500 w-1/4">Title</th>
                            <th class="px-4 py-3 font-semibold text-slate-500">Announcement</th>
                            <th class="px-4 py-3 font-semibold text-slate-500">Target Access Level</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 w-24 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredAnnouncements.length === 0">
                            <td colspan="3" class="px-4 py-10 text-center text-slate-400">
                                No Announcements found.
                            </td>
                        </tr>
                        <tr
                            v-for="announcement in filteredAnnouncements"
                            :key="announcement.id"
                            class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors"
                        >
                            <td class="px-4 py-3 font-medium text-slate-700 align-top">
                                {{ announcement.title }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 align-top">
                                <div class="ql-content break-all" v-html="announcement.announcement" />
                            </td>
                            <td class="px-4 py-3 font-medium text-slate-700 align-top">
                                {{ announcement.target_access_level_label }}
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center justify-center gap-1">
                                    <button
                                        @click="openAnnouncementModal(announcement, 'edit')"
                                        type="button"
                                        class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                        title="Edit"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-01" class="text-sm" />
                                    </button>
                                    <button
                                        @click="deleteAnnouncement(announcement)"
                                        type="button"
                                        class="p-1.5 rounded-md text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                        title="Delete"
                                    >
                                        <Icon icon="hugeicons:delete-01" class="text-sm" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Glossary Modal -->
    <Dialog
        v-model:visible="announcementModal.visible"
        :header="announcementModal.mode === 'add' ? 'Add Announcement' : 'Update Announcement'"
        class="w-[85%]"
        modal
    >
        <div class="flex flex-col gap-4 pt-2">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Title</label>
                <InputText
                    v-model="form.title"
                    placeholder="e.g. Due Date for Reports"
                    :invalid="!!form.errors.title"
                    class="w-full text-sm"
                />
                <span v-if="form.errors.title" class="text-xs text-red-500">
                    {{ form.errors.title }}
                </span>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Announcement</label>
                <Editor
                    v-model="form.announcement"
                    editorStyle="height: 180px; width: 100%;"
                />
                <span v-if="form.errors.announcement" class="text-xs text-red-500">
                    {{ form.errors.announcement }}
                </span>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Select Target Access levels</label>
                <Select
                    v-model="form.target_access_level"
                    :options="[
                        {label:'HRH Accounts',value:2},
                        {label:'PDOHO Accounts',value:3},
                        {label:'DMO Accounts',value:4},
                    ]"
                    option-value="value"
                    option-label="label"
                    placeholder="Select"
                />
                <span v-if="form.errors.target_access_level" class="text-xs text-red-500">
                    {{ form.errors.target_access_level }}
                </span>
            </div>

        </div>

        <template #footer>
            <div class="flex items-center justify-end gap-2 pt-2">
                <Button
                    label="Cancel"
                    severity="secondary"
                    text
                    :disabled="form.processing"
                    @click="announcementModal.visible = false"
                />
                <Button
                    :label="announcementModal.mode === 'add' ? 'Add Announcement' : 'Update Announcement'"
                    :loading="form.processing"
                    @click="saveAnnouncement"
                />
            </div>
        </template>
    </Dialog>
</template>