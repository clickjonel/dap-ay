<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, inject, onMounted } from 'vue'
    import { router } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { Dialog } from 'primevue'
    import { useToast } from 'primevue/usetoast'

    defineOptions({ layout: Main })
    const user = inject('user')
    const toast = useToast()

    const props = defineProps({
        barangays: { type: Object, default: () => ({}) },
        filters:   { type: Object, default: () => ({}) },
        provinces: { type: Array,  default: () => []   },
    })

    // ── Search ─────────────────────────────────────────────
    const search = ref(props.filters.search ?? '')
    const confirmDeleteBarangayDialog = ref(false)
    const selectedBarangay = ref(null)

    onMounted(()=>{
        console.log(props.barangays)
    })

    function openConfirmDeleteReportDialog(barangay) {
        selectedBarangay.value = barangay
        confirmDeleteBarangayDialog.value = true
    }

    let searchTimeout = null
    const onSearch = (e) => {
        search.value = e.target.value
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(() => {
            router.get('/barangays', { search: search.value }, {
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

    const deletebarangay = () => {
        router.delete(`/barangay/${selectedBarangay.value.id}`, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Barangay Deleted', detail: 'Barangay has been deleted successfully.', life: 2000 })
                confirmDeleteBarangayDialog.value = false
            },
            onError: () => toast.add({ severity: 'error', summary: 'Failed to delete barangay', life: 2000 }),
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

                <button @click="router.visit('/barangays/create')" class="px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-emerald-900 transition-colors cursor-pointer">Add Barangay</button>

            </div>
        </div>

        <!-- ── Table ─────────────────────────────────────── -->
        <div class="overflow-y-auto px-2 pb-2">
            <div class="w-full grid grid-cols-12 divide-x divide-gray-400 text-xs font-semibold tracking-widest uppercase text-gray-600 bg-sky-100 border border-gray-400 sticky top-0">
                <span class="col-span-2 p-2">PSGC Code</span>
                <span class="col-span-3 p-2">Barangay</span>
                <span class="col-span-3 p-2">Municipality</span>
                <span class="col-span-2 p-2">Province</span>
                <span class="col-span-2 p-2">Actions</span>
            </div>
            <div v-for="brgy in barangays.data" :key="brgy.id" class="w-full grid grid-cols-12 divide-x divide-gray-400 text-xs text-gray-600 border-b border-x border-gray-400 bg-white tracking-wide font-medium">
                <span class="col-span-2 p-2">{{ brgy.psgc_code ?? '-'}}</span>
                <span class="col-span-3 p-2">{{ brgy.name }}</span>
                <span class="col-span-3 p-2">{{ brgy.municipality?.name }}</span>
                <span class="col-span-2 p-2">{{ brgy.province?.name }}</span>
                <span class="col-span-2 p-2">
                    <button @click="router.visit(`/barangays/${brgy.id}`)" class="p-1 text-gray-400 hover:text-green-800 hover:bg-green-50 border border-transparent hover:border-green-200 transition-all" title="Edit Barangay">
                        <Icon icon="boxicons:edit" class="text-base" />
                    </button>
                    <button @click="openConfirmDeleteReportDialog(brgy)" class="p-1 text-gray-400 hover:text-red-400 hover:bg-red-50 border border-transparent hover:border-red-200 transition-all" title="Delete Barangay">
                        <Icon icon="mdi:trash-can-outline" class="text-base" />
                    </button>
                </span>
            </div>

        </div>

        <!-- ── Pagination ─────────────────────────────── -->
        <div class="w-full flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-white shrink-0">

            <span class="text-xs text-slate-400">
                Showing {{ barangays.length }} Records Per Page
            </span>

            <div class="flex items-center gap-1.5">
                <button
                    @click="goToPage(barangays.prev_page_url)"
                    :disabled="!barangays.prev_page_url"
                    class="h-8 p-2.5 flex items-center gap-1 text-sm shadow-md text-slate-900 border border-slate-400 transition-all disabled:opacity-30 cursor-pointer hover:bg-sky-400 hover:text-white disabled:cursor-not-allowed"
                >
                    <Icon icon="lucide:chevron-left" class="text-sm"/>
                    
                </button>
                <button
                    @click="goToPage(barangays.next_page_url)"
                    :disabled="!barangays.next_page_url"
                    class="h-8 p-2.5 flex items-center gap-1 text-sm shadow-md text-slate-900 border border-slate-400 transition-all disabled:opacity-30 cursor-pointer hover:bg-sky-400 hover:text-white disabled:cursor-not-allowed"
                >
                    <Icon icon="lucide:chevron-right" class="text-sm"/>
                </button>
            </div>

        </div>


        <!-- confirm delete barangay -->
        <Dialog v-model:visible="confirmDeleteBarangayDialog" modal :draggable="false" header="Confirm" class="w-full max-w-sm mx-4">
            <div class="flex flex-col gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:delete-02" class="text-base text-red-500" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">
                            Are you sure you want to delete this barangay?
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            This will delete all report related data including indicators, activities and others. Click Delete to delete record.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button
                        type="button"
                        @click="confirmDeleteBarangayDialog = false"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="deletebarangay"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                    >
                        <Icon icon="hugeicons:delete-03" class="text-sm" />
                        Delete Barangay
                    </button>
                </div>
            </div>
        </Dialog>

    </div>
</template>