<script setup>
    import { ref, computed, inject } from 'vue'
    import { useForm, router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'

    import Main from '@/layouts/main.vue'
    import Editor from 'primevue/editor'
    import { Button, Dialog } from 'primevue'
    import InputText from 'primevue/inputtext'

    const user = inject('user')
    const toast = useToast()

    defineOptions({ layout: Main })
    const props = defineProps({
        terms: { type: Array, default: () => [] },
    })

    const search = ref('')

    const filteredTerms = computed(() =>
        props.terms.filter(t =>
            t.term.toLowerCase().includes(search.value.toLowerCase())
        )
    )

    const glossaryModal = ref({
        visible: false,
        mode: null,
        glossary: null
    })

    const form = useForm({
        term: null,
        description: null
    })

    function openGlossaryModal(glossary = null, mode) {
        glossaryModal.value.mode = mode
        glossaryModal.value.glossary = glossary
        glossaryModal.value.visible = true
        form.reset()
        form.clearErrors()
        if (glossary) {
            form.term = glossary.term
            form.description = glossary.description
        }
    }

    function saveGlossary() {
        if (glossaryModal.value.mode === 'add') {
            form.post('/glossaries', {
                onSuccess: () => {
                    glossaryModal.value.visible = false
                    toast.add({ severity: 'success', summary: 'Term added', life: 3000 })
                },
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Failed to add term', detail: 'Please check the form and try again.', life: 4000 })
                }
            })
        } else {
            form.patch(`/glossaries/${glossaryModal.value.glossary.id}`, {
                onSuccess: () => {
                    glossaryModal.value.visible = false
                    toast.add({ severity: 'success', summary: 'Term updated', life: 3000 })
                },
                onError: () => {
                    toast.add({ severity: 'error', summary: 'Failed to update term', detail: 'Please check the form and try again.', life: 4000 })
                }
            })
        }
    }

    function deleteTerm(term) {
        if (!confirm(`Delete "${term.term}"?`)) return
        router.delete(`/glossaries/${term.id}`, {
            onSuccess: () => toast.add({ severity: 'success', summary: 'Term deleted', life: 3000 }),
            onError: () => toast.add({ severity: 'error', summary: 'Failed to delete term', life: 3000 }),
        })
    }
</script>

<template>
    <div class="h-full flex flex-col gap-4 lg:gap-5">

        <!-- Header -->
        <div class="flex items-start sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Definition of Terms</h1>
                <p class="text-xs text-slate-400 mt-1">Purokalusugan and Dap-ay terms defined.</p>
            </div>
            <button
                @click="openGlossaryModal(null, 'add')"
                type="button"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">New Term</span>
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
                    placeholder="Search term..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400 shrink-0">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredTerms.length }} <span class="hidden sm:inline">Terms</span></span>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-1 min-h-0 overflow-y-auto">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <table class="w-full text-xs table-fixed">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50 text-left">
                            <th class="px-4 py-3 font-semibold text-slate-500 w-1/4">Term</th>
                            <th class="px-4 py-3 font-semibold text-slate-500">Description</th>
                            <th class="px-4 py-3 font-semibold text-slate-500 w-24 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredTerms.length === 0">
                            <td colspan="3" class="px-4 py-10 text-center text-slate-400">
                                No terms found.
                            </td>
                        </tr>
                        <tr
                            v-for="term in filteredTerms"
                            :key="term.id"
                            class="border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors"
                        >
                            <td class="px-4 py-3 font-medium text-slate-700 align-top">
                                {{ term.term }}
                            </td>
                            <td class="px-4 py-3 text-slate-500 align-top">
                                <div class="ql-content break-words" v-html="term.description" />
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center justify-center gap-1">
                                    <button
                                        @click="openGlossaryModal(term, 'edit')"
                                        type="button"
                                        class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                        title="Edit"
                                    >
                                        <Icon icon="hugeicons:pencil-edit-01" class="text-sm" />
                                    </button>
                                    <button
                                        @click="deleteTerm(term)"
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
        v-model:visible="glossaryModal.visible"
        :header="glossaryModal.mode === 'add' ? 'Add Term' : 'Update Term'"
        class="w-full max-w-lg"
        modal
    >
        <div class="flex flex-col gap-4 pt-2">

            <!-- Term -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Term</label>
                <InputText
                    v-model="form.term"
                    placeholder="e.g. Barangay Health Center"
                    :invalid="!!form.errors.term"
                    class="w-full text-sm"
                />
                <span v-if="form.errors.term" class="text-xs text-red-500">
                    {{ form.errors.term }}
                </span>
            </div>

            <!-- Description -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Description</label>
                <Editor
                    v-model="form.description"
                    editorStyle="height: 180px; width: 100%;"
                />
                <span v-if="form.errors.description" class="text-xs text-red-500">
                    {{ form.errors.description }}
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
                    @click="glossaryModal.visible = false"
                />
                <Button
                    :label="glossaryModal.mode === 'add' ? 'Add Term' : 'Update Term'"
                    :loading="form.processing"
                    @click="saveGlossary"
                />
            </div>
        </template>
    </Dialog>
</template>