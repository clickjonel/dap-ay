<script setup>
import Main from '@/layouts/main.vue'
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import ToggleSwitch from 'primevue/toggleswitch'

const toast = useToast()
defineOptions({ layout: Main })

const props = defineProps({
    disaggregations: { type: Array, default: () => [] },
})

// ── Search ─────────────────────────────────────────────
const search = ref('')
const filtered = computed(() =>
    props.disaggregations.filter(d =>
        d.disaggregation_name.toLowerCase().includes(search.value.toLowerCase()) ||
        (d.group ?? '').toLowerCase().includes(search.value.toLowerCase())
    )
)

// ── Modal ──────────────────────────────────────────────
const modalVisible = ref(false)
const editing      = ref(null)
const isEdit       = computed(() => !!editing.value)

const form = useForm({
    disaggregation_name: '',
    group:                '',
    is_active:           true,
})

const openCreate = () => {
    editing.value = null
    form.reset()
    form.is_active = true
    modalVisible.value = true
}

const openEdit = (item) => {
    editing.value              = item
    form.disaggregation_name   = item.disaggregation_name
    form.group                  = item.group ?? ''
    form.is_active             = !!item.is_active
    modalVisible.value         = true
}

const close = () => {
    modalVisible.value = false
    form.reset()
    editing.value = null
}

const submit = () => {
    const name    = form.disaggregation_name
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  isEdit.value ? 'Disaggregation Updated' : 'Disaggregation Created',
                detail:   `"${name}" has been ${isEdit.value ? 'updated' : 'created'} successfully.`,
                life:     2000,
            })
            close()
        },
    }

    if (isEdit.value) {
        form.put(`/disaggregations/${editing.value.id}`, options)
    } else {
        form.post('/disaggregations', options)
    }
}

// ── Toggle status ──────────────────────────────────────
const changeStatus = (item) => {
    useForm({}).delete(`/disaggregations/${item.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Status Updated',
                detail:   `"${item.disaggregation_name}" has been ${item.is_active ? 'disabled' : 'activated'}.`,
                life:     2000,
            })
        },
    })
}
</script>

<template>
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Disaggregations</h1>
            <p class="text-xs text-slate-400 mt-1">Manage disaggregation categories and their groupings.</p>
        </div>
        <button
            type="button"
            @click="openCreate"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            New Disaggregation
        </button>
    </div>

    <!-- ── Filters ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                v-model="search"
                type="text"
                placeholder="Search disaggregations..."
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>
        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ filtered.length }} disaggregations</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Disaggregation Name</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Group</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="filtered.length === 0">
                    <td colspan="5" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                            <p class="text-sm font-medium text-slate-400">No disaggregations found</p>
                            <p class="text-xs text-slate-300">Try adjusting your search or create a new one.</p>
                        </div>
                    </td>
                </tr>

                <tr
                    v-for="(item, index) in filtered"
                    :key="item.id"
                    class="hover:bg-slate-50/70 transition-colors"
                    :class="!item.is_active ? 'opacity-60' : ''"
                >
                    <!-- Index -->
                    <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                        {{ String(index + 1).padStart(2, '0') }}
                    </td>

                    <!-- Name -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <Icon icon="hugeicons:layers-01" class="text-sm text-indigo-500" />
                            </div>
                            <span class="text-sm font-semibold text-slate-700">{{ item.disaggregation_name }}</span>
                        </div>
                    </td>

                    <!-- Type -->
                    <td class="px-5 py-3.5">
                        <span
                            v-if="item.group"
                            class="inline-flex items-center px-2.5 py-1 bg-slate-100 rounded-full text-[11px] font-medium text-slate-600"
                        >
                            {{ item.group }}
                        </span>
                        <span v-else class="text-xs text-slate-300">—</span>
                    </td>

                    <!-- Status -->
                    <td class="px-5 py-3.5">
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                            :class="item.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                        >
                            <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'" />
                            {{ item.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            <button
                                type="button"
                                @click="openEdit(item)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                Edit
                            </button>
                            <button
                                type="button"
                                @click="changeStatus(item)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border transition-all"
                                :class="item.is_active
                                    ? 'border-slate-200 text-slate-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50'
                                    : 'border-slate-200 text-slate-500 hover:border-emerald-300 hover:text-emerald-600 hover:bg-emerald-50'"
                            >
                                <Icon :icon="item.is_active ? 'hugeicons:cancel-01' : 'hugeicons:checkmark-circle-01'" class="text-xs" />
                                {{ item.is_active ? 'Disable' : 'Activate' }}
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- ── Modal ──────────────────────────────────────── -->
    <Dialog
        modal
        append-to="body"
        :visible="modalVisible"
        :header="isEdit ? 'Edit Disaggregation' : 'New Disaggregation'"
        :style="{ width: '440px' }"
        :pt="{
            header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
            title:   { class: '!text-sm !font-bold !text-slate-800'   },
            content: { class: '!p-5'                                   },
        }"
        @update:visible="modalVisible = $event"
        @hide="close"
    >
        <form @submit.prevent="submit" class="flex flex-col gap-4">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">
                    Disaggregation Name <span class="text-red-400">*</span>
                </label>
                <InputText
                    v-model="form.disaggregation_name"
                    placeholder="e.g. Age Group 0-11 months"
                    class="text-sm! py-2!"
                    :class="form.errors.disaggregation_name ? 'border-red-400!' : ''"
                    autofocus
                />
                <p v-if="form.errors.disaggregation_name" class="text-[11px] text-red-500">{{ form.errors.disaggregation_name }}</p>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">Group</label>
                <InputText
                    v-model="form.group"
                    placeholder="e.g. Age Group, Sex, Location"
                    class="text-sm! py-2!"
                    :class="form.errors.group ? 'border-red-400!' : ''"
                />
                <p v-if="form.errors.group" class="text-[11px] text-red-500">{{ form.errors.group }}</p>
                <p class="text-[11px] text-slate-400">Used to group disaggregations together.</p>
            </div>

            <div
                class="flex items-center justify-between p-3 rounded-lg border transition-colors"
                :class="form.is_active ? 'bg-indigo-50 border-indigo-100' : 'bg-slate-50 border-slate-100'"
            >
                <div>
                    <p class="text-xs font-semibold text-slate-700">Active</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Make this disaggregation available for use</p>
                </div>
                <ToggleSwitch v-model="form.is_active" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button
                    type="button"
                    @click="close"
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
                    {{ isEdit ? 'Save Changes' : 'Create Disaggregation' }}
                </button>
            </div>

        </form>
    </Dialog>

</div>
</template>