<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import Select from 'primevue/select'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    barangay:   { type: Object, default: () => ({}) },
})

const pkStatusOptions = [
    'Preparation',
    'Implementing with MOVs',
    'Implementing without MOVs',
    'Processing of MOVs',
    'Monitoring Implementation',
]

const form = useForm({
    barangay_id: props.barangay.id,
    pk_status: props.pk_profile?.pk_status ?? null,
    pk_site: props.pk_profile?.pk_site ?? false,
})

const submit = () => {
    form.post('/barangay/pk-profile', {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'PK Profile Saved',
                detail:   'PK Profile has been saved successfully.',
                life:     2000,
            })
            router.visit('/barangays')
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary:  'Error',
                detail:   'Please check the form and try again.',
                life:     3000,
            })
        },
    })
}
</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0">

        <!-- ── Header ─────────────────────────────────────── -->
        <div class="flex items-center gap-4 shrink-0">
            <button
                type="button"
                @click="router.visit('/barangays')"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">PK Profile</h1>
                <p class="text-xs text-slate-400 mt-1">
                    {{ props.barangay.name }} —
                    {{ props.barangay.municipality?.name }},
                    {{ props.barangay.province?.name }}
                </p>
            </div>
            <span class="ml-auto text-[10px] font-mono font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
                #{{ props.barangay.id }}
            </span>
        </div>

        <!-- ── Form card ──────────────────────────────────── -->
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-50 w-full max-w-lg">

                <div class="px-5 py-3.5 flex items-center gap-2">
                    <Icon icon="hugeicons:file-01" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">PK Profile</span>
                </div>

                <form @submit.prevent="submit" class="px-5 py-5 flex flex-col gap-4">

                    <!-- PK Status -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">
                            PK Status <span class="text-red-400">*</span>
                        </label>
                        <Select
                            v-model="form.pk_status"
                            :options="pkStatusOptions"
                            placeholder="Select status..."
                            class="w-full text-sm!"
                            :class="form.errors.pk_status ? '!border-red-400' : ''"
                        />
                        <p v-if="form.errors.pk_status" class="text-[11px] text-red-500">{{ form.errors.pk_status }}</p>
                    </div>

                    <!-- PK Site -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">PK Site</label>
                        <div
                            @click="form.pk_site = !form.pk_site"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-all select-none"
                            :class="form.pk_site
                                ? 'border-indigo-300 bg-indigo-50'
                                : 'border-slate-200 bg-white hover:border-slate-300'"
                        >
                            <div
                                class="w-9 h-5 rounded-full transition-colors relative shrink-0"
                                :class="form.pk_site ? 'bg-indigo-600' : 'bg-slate-200'"
                            >
                                <div
                                    class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-all"
                                    :class="form.pk_site ? 'left-[18px]' : 'left-0.5'"
                                />
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ form.pk_site ? 'PK Site' : 'Non-PK Site' }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ form.pk_site ? 'This barangay is designated as a PK site.' : 'This barangay is not a PK site.' }}
                                </p>
                            </div>
                        </div>
                        <p v-if="form.errors.pk_site" class="text-[11px] text-red-500">{{ form.errors.pk_site }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button
                            type="button"
                            @click="router.visit('/barangays')"
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
                            Save Profile
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>