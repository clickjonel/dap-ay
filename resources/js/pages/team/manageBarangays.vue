<script setup>
    import Main from '@/layouts/main.vue'
    import { ref, computed } from 'vue'
    import { router, useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import Dialog from 'primevue/dialog'
    import Select from 'primevue/select'

    defineOptions({ layout: Main })

    const toast = useToast()

    const props = defineProps({
        team:      { type: Object, default: () => ({}) },
        barangays: { type: Array,  default: () => []   },
    })

    // ── Search ─────────────────────────────────────────────
    const search = ref('')
    const filteredAssigned = computed(() =>
        (props.team.barangays ?? []).filter(b =>
            b.name.toLowerCase().includes(search.value.toLowerCase())
        )
    )

    // ── Modal ──────────────────────────────────────────────
    const modal = ref(false)

    const form = useForm({
        barangay_id: null,
    })

    const assign = () => {
        form.post(`/teams/${props.team.id}/barangays`, {
            preserveScroll: true,
            onSuccess: () => {
                modal.value = false
                form.reset()
                toast.add({ severity: 'success', summary: 'Assigned', detail: 'Barangay has been assigned.', life: 2000 })
            },
        })
    }

    const unassign = (barangay) => {
        useForm({}).delete(`/teams/${props.team.id}/barangays/${barangay.pivot.barangay_id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Unassigned', detail: `${barangay.name} has been unassigned.`, life: 2000 })
            },
        })
    }

    // exclude already assigned from the select options
    const assignedIds = computed(() => new Set(props.team.barangays?.map(b => b.id) ?? []))
    const filteredBarangays = computed(() => props.barangays.filter(b => !assignedIds.value.has(b.id)))
</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0 overflow-hidden">

        <!-- ── Header ─────────────────────────────────────────── -->
        <div class="flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <button
                    type="button"
                    @click="router.visit('/teams')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
                >
                    <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
                </button>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 leading-none">{{ team.name }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Manage barangay jurisdictions for this team.</p>
                </div>
            </div>
            <button
                type="button"
                @click="modal = true"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                Assign Barangay
            </button>
        </div>

        <!-- ── Filters ────────────────────────────────────────── -->
        <div class="flex items-center gap-3 shrink-0">
            <div class="relative flex-1 max-w-xs">
                <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search assigned barangays..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                />
            </div>
            <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
                <Icon icon="hugeicons:list-view" class="text-sm" />
                <span>{{ filteredAssigned.length }} barangays</span>
            </div>
        </div>

        <!-- ── Table ─────────────────────────────────────────── -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
            <table class="w-full text-left">
                <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                    <tr>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Barangay</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Municipality</th>
                        <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    <tr v-if="filteredAssigned.length === 0">
                        <td colspan="4" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                <p class="text-sm font-medium text-slate-400">No barangays assigned</p>
                                <p class="text-xs text-slate-300">Click Assign Barangay to get started.</p>
                            </div>
                        </td>
                    </tr>

                    <tr
                        v-for="(barangay, index) in filteredAssigned"
                        :key="barangay.id"
                        class="hover:bg-slate-50/70 transition-colors"
                    >
                        <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                            {{ String(index + 1).padStart(2, '0') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                                    <Icon icon="hugeicons:map-pinpoint-01" class="text-sm text-emerald-500" />
                                </div>
                                <span class="text-xs font-semibold text-slate-700">{{ barangay.name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">
                            {{ barangay.municipality?.name ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end">
                                <button
                                    type="button"
                                    @click="unassign(barangay)"
                                    class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50 transition-all"
                                >
                                    <Icon icon="hugeicons:cancel-01" class="text-xs" />
                                    Unassign
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

    <!-- ── Assign Modal ───────────────────────────────────── -->
    <Dialog
        v-model:visible="modal"
        modal
        append-to="body"
        :style="{ width: '420px' }"
        :pt="{
            header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
            title:   { class: '!text-sm !font-bold !text-slate-800' },
            content: { class: '!p-5' },
        }"
    >
        <template #header>
            <div class="flex flex-col gap-0.5">
                <span class="text-sm font-bold text-slate-800">Assign Barangay</span>
                <span class="text-[11px] text-slate-400 font-normal">{{ team.name }}</span>
            </div>
        </template>

        <form @submit.prevent="assign" class="flex flex-col gap-4">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Barangay <span class="text-red-400">*</span></label>
                <Select
                    v-model="form.barangay_id"
                    :options="filteredBarangays"
                    option-label="name"
                    option-value="id"
                    placeholder="Search barangay..."
                    filter
                    class="!text-sm w-full"
                    :class="form.errors.barangay_id ? '!border-red-400' : ''"
                />
                <p v-if="form.errors.barangay_id" class="text-[11px] text-red-500">{{ form.errors.barangay_id }}</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1 border-t border-slate-100">
                <button
                    type="button"
                    @click="modal = false"
                    class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="form.processing || !form.barangay_id"
                    class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                >
                    <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                    Assign
                </button>
            </div>

        </form>
    </Dialog>
</template>