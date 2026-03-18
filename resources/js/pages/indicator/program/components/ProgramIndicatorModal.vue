<script setup>
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Select from 'primevue/select'
import ToggleSwitch from 'primevue/toggleswitch'
import { useToast } from 'primevue/usetoast'

const toast = useToast()

const emit = defineEmits(['update:visible'])

const props = defineProps({
    visible:   { type: Boolean, default: false  },
    indicator: { type: Object,  default: null   },
    programs:  { type: Array,   default: () => [] },
})

// ── Mode ───────────────────────────────────────────────
const isEdit = computed(() => props.indicator !== null)

// ── Form ───────────────────────────────────────────────
const form = useForm({
    indicator_name: '',
    program_id:     null,
    is_active:      true,
})

// ── Populate form on dialog open ───────────────────────
const onShow = () => {
    if (isEdit.value) {
        form.indicator_name = props.indicator.indicator_name ?? ''
        form.program_id     = props.indicator.program_id     ?? null
        form.is_active      = !!props.indicator.is_active
    } else {
        form.reset()
        form.is_active = true
    }
}

// ── Close ──────────────────────────────────────────────
const close = () => {
    emit('update:visible', false)
    form.reset()
}

// ── Submit ─────────────────────────────────────────────
const submit = () => {
    const options = {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  isEdit.value ? 'Indicator Updated' : 'Indicator Created',
                detail:   `Indicator has been successfully ${isEdit.value ? 'updated' : 'created'}.`,
                life:     2000,
            })
            close()
        },
    }

    if (isEdit.value) {
        form.put(`/indicator/program/${props.indicator.id}`, options)
    } else {
        form.post('/indicator/program', options)
    }
}
</script>

<template>
<Dialog
    modal
    append-to="body"
    @update:visible="$emit('update:visible', $event)"
    @show="onShow"
    @hide="form.reset()"
    :visible="props.visible"
    :header="isEdit ? 'Edit Indicator' : 'New Indicator'"
    :style="{ width: '440px' }"
    :pt="{
        header:  { class: 'border-b border-slate-100 !py-4 !px-5' },
        title:   { class: '!text-sm !font-bold !text-slate-800'   },
        content: { class: '!p-5'                                   },
    }"
>
    <form @submit.prevent="submit" class="flex flex-col gap-4">

        <!-- Indicator Name -->
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">
                Indicator Name <span class="text-red-400">*</span>
            </label>
            <InputText
                v-model="form.indicator_name"
                placeholder="e.g. Maternal Health Coverage"
                class="text-sm! py-2!"
                :class="form.errors.indicator_name ? 'border-red-400!' : ''"
                autofocus
            />
            <p v-if="form.errors.indicator_name" class="text-[11px] text-red-500">
                {{ form.errors.indicator_name }}
            </p>
        </div>

        <!-- Program -->
        <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-slate-600">
                Program <span class="text-red-400">*</span>
            </label>
            <Select
                v-model="form.program_id"
                :options="programs"
                option-label="name"
                option-value="id"
                placeholder="Select a program..."
                class="w-full text-sm!"
                :class="form.errors.program_id ? 'border-red-400!' : ''"
            />
            <p v-if="form.errors.program_id" class="text-[11px] text-red-500">
                {{ form.errors.program_id }}
            </p>
        </div>

        <!-- Status -->
        <div
            class="flex items-center justify-between p-3 rounded-lg border transition-colors"
            :class="form.is_active ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-100'"
        >
            <div>
                <p class="text-xs font-semibold text-slate-700">Active</p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Enable or disable this indicator
                </p>
            </div>
            <ToggleSwitch v-model="form.is_active" />
        </div>

        <!-- Actions -->
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
                <Icon
                    v-if="form.processing"
                    icon="hugeicons:loading-01"
                    class="text-sm animate-spin"
                />
                {{ isEdit ? 'Save Changes' : 'Create Indicator' }}
            </button>
        </div>

    </form>
</Dialog>
</template>