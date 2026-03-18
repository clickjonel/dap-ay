<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    barangay: { type: Object, default: () => ({}) },
})

const form = useForm({
    barangay_id: props.barangay.id,
    longitude:   '',
    latitude:    '',
    is_gida:     false,
})

const submit = () => {
    form.post('/barangay/geography', {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Geography Saved',
                detail:   'Geography has been saved successfully.',
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
                <h1 class="text-lg font-bold text-slate-800 leading-none">Set Geography</h1>
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
                    <Icon icon="hugeicons:maps-location-02" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Geography Details</span>
                </div>

                <form @submit.prevent="submit" class="px-5 py-5 flex flex-col gap-4">

                    <!-- Coordinates -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">
                                Longitude <span class="text-red-400">*</span>
                            </label>
                            <input
                                v-model="form.longitude"
                                type="text"
                                placeholder="e.g. 120.9842"
                                class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all font-mono"
                                :class="form.errors.longitude ? 'border-red-400' : ''"
                            />
                            <p v-if="form.errors.longitude" class="text-[11px] text-red-500">{{ form.errors.longitude }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">
                                Latitude <span class="text-red-400">*</span>
                            </label>
                            <input
                                v-model="form.latitude"
                                type="text"
                                placeholder="e.g. 14.5995"
                                class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all font-mono"
                                :class="form.errors.latitude ? 'border-red-400' : ''"
                            />
                            <p v-if="form.errors.latitude" class="text-[11px] text-red-500">{{ form.errors.latitude }}</p>
                        </div>
                    </div>

                    <!-- GIDA -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-600">GIDA Status</label>
                        <div
                            @click="form.is_gida = !form.is_gida"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition-all select-none"
                            :class="form.is_gida
                                ? 'border-indigo-300 bg-indigo-50'
                                : 'border-slate-200 bg-white hover:border-slate-300'"
                        >
                            <div
                                class="w-9 h-5 rounded-full transition-colors relative shrink-0"
                                :class="form.is_gida ? 'bg-indigo-600' : 'bg-slate-200'"
                            >
                                <div
                                    class="absolute top-0.5 w-4 h-4 rounded-full bg-white shadow transition-all"
                                    :class="form.is_gida ? 'left-[18px]' : 'left-0.5'"
                                />
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ form.is_gida ? 'GIDA Area' : 'Non-GIDA Area' }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ form.is_gida ? 'This barangay is a Geographically Isolated and Disadvantaged Area.' : 'This barangay is not a GIDA.' }}
                                </p>
                            </div>
                        </div>
                        <p v-if="form.errors.is_gida" class="text-[11px] text-red-500">{{ form.errors.is_gida }}</p>
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
                            Save Geography
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>