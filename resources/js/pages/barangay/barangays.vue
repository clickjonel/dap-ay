<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, inject } from 'vue'
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
    <div class="h-full flex flex-col gap-5">

        <!-- ── Page header ──────────────────────────────── -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Barangays</h1>
                <p class="text-xs text-slate-400 mt-1">Manage barangays and their data.</p>
            </div>
            <button
                v-if="user.access_levels.access_level !== 2"
                type="button"
                @click="router.visit('/barangays/create')"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                New Barangay
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
                    placeholder="Search barangays..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ barangays.total ?? 0 }} barangays</span>
            </div>
        </div>

        <!-- ── Table ─────────────────────────────────────── -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1 flex flex-col">
            <table class="w-full text-left">
                <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                    <tr>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">PSGC Code</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Barangay</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    <!-- Empty state -->
                    <tr v-if="!barangays.data || barangays.data.length === 0">
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                <p class="text-sm font-medium text-slate-400">No barangays found</p>
                                <p class="text-xs text-slate-300">Try adjusting your search or add a new barangay.</p>
                            </div>
                        </td>
                    </tr>

                    <tr
                        v-for="barangay in barangays.data"
                        :key="barangay.id"
                        class="hover:bg-slate-50/70 transition-colors cursor-pointer"
                    >
                        <!-- PSGC Code -->
                        <td class="px-5 py-3.5">
                            <code class="text-[11px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-mono">
                                {{ barangay.psgc_code ?? '—' }}
                            </code>
                        </td>

                        <!-- Barangay Name -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                    <Icon icon="hugeicons:map-pinpoint-01" class="text-sm text-indigo-500" />
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-sm font-semibold text-slate-700 truncate">{{ barangay.name }}</span>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-[10px] text-slate-400 truncate">{{ barangay.municipality?.name ?? '—' }}</span>
                                        <span class="text-slate-300 text-[10px]">·</span>
                                        <span class="text-[10px] text-slate-400 truncate">{{ barangay?.province?.name ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-3.5" @click.stop>
                            <div class="flex items-center justify-end gap-1">
                                <!-- edit barangay btn -->
                                <button
                                    type="button"
                                    @click="router.visit(`/barangays/${barangay.id}/edit`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                                    title="Edit Barangay Details"
                                >
                                    <Icon icon="hugeicons:pencil-edit-02" class="text-sm cursor-pointer" />
                                </button>

                                <!-- barangay profile actions -->
                                <button
                                    v-if="!barangay.pk_profile"
                                    type="button"
                                    @click="router.visit(`/barangay/pk-profile/create?barangay_id=${barangay.id}`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                    title="Add Profile"
                                >
                                    <Icon icon="hugeicons:user-add-01" class="text-sm" />
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    @click="router.visit(`/barangay/pk-profile/${barangay.pk_profile.id}/edit`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                    title="Update Profile"
                                >
                                    <Icon icon="hugeicons:user-edit-01" class="text-sm" />
                                </button>

                                <!-- barangay indicator actions -->
                                <button
                                    v-if="barangay.organizational_indicators.length === 0"
                                    type="button"
                                    @click="router.visit(`/barangay/organizational-indicator/create?barangay_id=${barangay.id}`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors"
                                    title="Add Organizational Indicators"
                                >
                                    <Icon icon="hugeicons:chart-increase" class="text-sm" />
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    @click="router.visit(`/barangay/organizational-indicator/${barangay.id}/edit`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors"
                                    title="Update Organizational Indicators"
                                >
                                    <Icon icon="hugeicons:property-edit" class="text-sm" />
                                </button>

                                <!-- barangay geography actions -->
                                <button
                                    v-if="!barangay.geography"
                                    type="button"
                                    @click="router.visit(`/barangay/geography/create?barangay_id=${barangay.id}`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-colors"
                                    title="Add Geography Details"
                                >
                                    <Icon icon="hugeicons:maps-location-02" class="text-sm" />
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    @click="router.visit(`/barangay/geography/${barangay.geography.id}/edit`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-colors"
                                    title="Update Geography Details"
                                >
                                    <Icon icon="hugeicons:maps-location-02" class="text-sm" />
                                </button>

                                <!-- population actions -->
                                <button
                                    v-if="!barangay.population"
                                    type="button"
                                    @click="router.visit(`/barangay/population/create?barangay_id=${barangay.id}`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="Add Population"
                                >
                                    <Icon icon="hugeicons:user-group" class="text-sm" />
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    @click="router.visit(`/barangay/population/${barangay.population.id}/edit`)"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="Update Population"
                                >
                                    <Icon icon="hugeicons:user-group" class="text-sm" />
                                </button>

                                <!-- targets actions -->
                                <button
                                    v-if="!barangay.targets"
                                    type="button"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="Priority Programs"
                                    @click="router.visit(`/barangay/priority-program?barangay_id=${barangay.id}`)"
                                >
                                    <Icon icon="carbon:top-programs" class="text-sm" />
                                </button>


                                <!-- delete -->
                                <button
                                    @click="openConfirmDeleteReportDialog(barangay)"
                                    type="button"
                                    class="p-1.5 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="Priority Programs"
                                >
                                    <Icon icon="hugeicons:delete-02" class="text-sm" />
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

            <!-- ── Pagination ─────────────────────────────── -->
            <div
                v-if="barangays.last_page > 1"
                class="flex items-center justify-between px-5 py-3 border-t border-slate-100 mt-auto"
            >
                <span class="text-xs text-slate-400">
                    Showing {{ barangays.from }}–{{ barangays.to }} of {{ barangays.total }}
                </span>
                <div class="flex gap-1">
                    <button
                        v-for="link in barangays.links"
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