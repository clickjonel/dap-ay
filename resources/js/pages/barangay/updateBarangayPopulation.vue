<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    barangay:   { type: Object, default: () => ({}) },
    population: { type: Object, default: () => ({}) },
})

const form = useForm({
    total_population:  props.population.total_population  ?? '',
    target_population: props.population.target_population ?? '',
    total_puroks:      props.population.total_puroks      ?? '',
    target_puroks:     props.population.target_puroks     ?? '',
    total_households:  props.population.total_households  ?? '',
    target_households: props.population.target_households ?? '',
})

const submit = () => {
    form.put(`/barangay/population/${props.population.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary:  'Population Updated',
                detail:   'Population details have been updated successfully.',
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

const back = () => {
    window.history.back()
}

</script>

<template>
    <div class="h-full flex flex-col gap-5 min-h-0">

        <!-- ── Header ─────────────────────────────────────── -->
        <div class="flex items-center gap-4 shrink-0">
            <button
                type="button"
                @click="back"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">Update Population</h1>
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
                    <Icon icon="hugeicons:user-group" class="text-sm text-slate-400" />
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Population Details</span>
                </div>

                <form @submit.prevent="submit" class="px-5 py-5 flex flex-col gap-4">

                    <!-- Population -->
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Population</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Total <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.total_population"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.total_population ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.total_population" class="text-[11px] text-red-500">{{ form.errors.total_population }}</p>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Target <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.target_population"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.target_population ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.target_population" class="text-[11px] text-red-500">{{ form.errors.target_population }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Puroks -->
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Puroks</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Total <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.total_puroks"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.total_puroks ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.total_puroks" class="text-[11px] text-red-500">{{ form.errors.total_puroks }}</p>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Target <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.target_puroks"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.target_puroks ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.target_puroks" class="text-[11px] text-red-500">{{ form.errors.target_puroks }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Households -->
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Households</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Total <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.total_households"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.total_households ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.total_households" class="text-[11px] text-red-500">{{ form.errors.total_households }}</p>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-slate-600">
                                    Target <span class="text-red-400">*</span>
                                </label>
                                <input
                                    v-model="form.target_households"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                                    :class="form.errors.target_households ? 'border-red-400' : ''"
                                />
                                <p v-if="form.errors.target_households" class="text-[11px] text-red-500">{{ form.errors.target_households }}</p>
                            </div>
                        </div>
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
                            Update Population
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>