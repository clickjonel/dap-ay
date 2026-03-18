<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import Dialog from 'primevue/dialog'
import Select from 'primevue/select'
import ToggleSwitch from 'primevue/toggleswitch'
import { useToast } from 'primevue/usetoast'

const toast = useToast()

const props = defineProps({
    barangay: { type: Object, default: () => ({}) },
})

const pkStatusOptions = [
    'Preparation',
    'Implementing with MOVs',
    'Implementing without MOVs',
    'Processing of MOVs',
    'Monitoring Implementation',
]

const modalVisible = ref(false)
const isEdit       = computed(() => !!props.barangay?.pk_profile)

const form = useForm({
    pk_status: null,
    pk_site:   false,
})

const onShow = () => {
    form.pk_status = props.barangay.pk_profile?.pk_status ?? null
    form.pk_site   = !!props.barangay.pk_profile?.pk_site
}

const close = () => {
    modalVisible.value = false
    form.reset()
}

const submit = () => {
    const options = {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: isEdit.value ? 'PK Profile Updated' : 'PK Profile Set', detail: `PK Profile has been ${isEdit.value ? 'updated' : 'created'} successfully.`, life: 2000 })
            close()
        },
    }

    if (isEdit.value) {
        form.put(`/barangay/pk-profile/${props.barangay.pk_profile.id}`, options)
    } else {
        form.transform(data => ({ ...data, barangay_id: props.barangay.id }))
            .post('/barangay/pk-profile', options)
    }
}
</script>

<template>
<div>

    <!-- ── Has profile ─────────────────────────────────── -->
    <div v-if="isEdit" class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-50">
        <div class="px-5 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <Icon icon="hugeicons:user-circle" class="text-sm text-slate-400" />
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">PK Profile</span>
            </div>
            <button
                type="button"
                @click="modalVisible = true"
                class="flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors"
            >
                <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                Update Profile
            </button>
        </div>
        <div class="grid grid-cols-2 divide-x divide-slate-50">
            <div class="px-5 py-4 flex flex-col gap-1">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">PK Status</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 rounded-full text-[11px] font-medium text-emerald-700 w-fit mt-0.5">
                    <Icon icon="hugeicons:checkmark-circle-01" class="text-xs text-emerald-500" />
                    {{ barangay.pk_profile.pk_status }}
                </span>
            </div>
            <div class="px-5 py-4 flex flex-col gap-1">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">PK Site</span>
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold w-fit mt-0.5"
                    :class="barangay.pk_profile.pk_site ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400'"
                >
                    {{ barangay.pk_profile.pk_site ? 'PK Site' : 'Non-PK Site' }}
                </span>
            </div>
        </div>
    </div>

    <!-- ── No profile ──────────────────────────────────── -->
    <div v-else class="bg-white rounded-xl border border-dashed border-slate-200 flex flex-col items-center justify-center py-16 gap-3">
        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center">
            <Icon icon="hugeicons:user-circle" class="text-xl text-slate-300" />
        </div>
        <div class="text-center">
            <p class="text-sm font-semibold text-slate-500">No PK Profile</p>
            <p class="text-xs text-slate-400 mt-1">This barangay doesn't have a PK profile yet.</p>
        </div>
        <button
            type="button"
            @click="modalVisible = true"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors mt-1"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            Set PK Profile
        </button>
    </div>

    <!-- ── Modal ───────────────────────────────────────── -->
    <Dialog
        modal
        append-to="body"
        :visible="modalVisible"
        :header="isEdit ? 'Update PK Profile' : 'Set PK Profile'"
        :style="{ width: '440px' }"
        :pt="{
            header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
            title:   { class: '!text-sm !font-bold !text-slate-800'   },
            content: { class: '!p-5'                                   },
        }"
        @update:visible="modalVisible = $event"
        @show="onShow"
        @hide="form.reset()"
    >
        <form @submit.prevent="submit" class="flex flex-col gap-4">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600">PK Status <span class="text-red-400">*</span></label>
                <Select v-model="form.pk_status" :options="pkStatusOptions" placeholder="Select status..." class="w-full text-sm!" :class="form.errors.pk_status ? 'border-red-400!' : ''" />
                <p v-if="form.errors.pk_status" class="text-[11px] text-red-500">{{ form.errors.pk_status }}</p>
            </div>

            <div
                class="flex items-center justify-between p-3 rounded-lg border transition-colors"
                :class="form.pk_site ? 'bg-indigo-50 border-indigo-100' : 'bg-slate-50 border-slate-100'"
            >
                <div>
                    <p class="text-xs font-semibold text-slate-700">PK Site</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mark this barangay as a PK site</p>
                </div>
                <ToggleSwitch v-model="form.pk_site" />
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button type="button" @click="close" class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                <button type="submit" :disabled="form.processing" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors">
                    <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                    {{ isEdit ? 'Update Profile' : 'Set Profile' }}
                </button>
            </div>

        </form>
    </Dialog>

</div>
</template>