<script setup>
import Main from '@/layouts/main.vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    barangay:       { type: Object, default: () => ({}) },
    org_indicators: { type: Array,  default: () => []   },
})

const form = useForm({
    barangay_id: props.barangay.id,
    indicators: props.org_indicators.map(i => ({
        organizational_indicator_id: i.id,
        name:                        i.indicator_name,
        value:                       i.value ?? '',
    }))
})

const submit = () => {
    form
        .transform(data => ({
            barangay_id: data.barangay_id,
            indicators:  data.indicators.map(({ organizational_indicator_id, value }) => ({
                organizational_indicator_id,
                value,
            }))
        }))
        .post(`/barangay/organizational-indicator`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Indicators Saved', detail: 'Organizational indicators have been saved successfully.', life: 2000 })
                router.visit('/barangays')
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Error', detail: 'Please check the form and try again.', life: 3000 })
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
                <h1 class="text-lg font-bold text-slate-800 leading-none">Set Organizational Indicators</h1>
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

        <!-- ── Form ──────────────────────────────────────── -->
        <div class="flex-1 min-h-0 overflow-y-auto">
            <div class="flex flex-col gap-3 max-w-2xl mx-auto">

                <!-- Empty -->
                <div v-if="form.indicators.length === 0" class="bg-white rounded-xl border border-slate-200 flex flex-col items-center gap-2 py-16">
                    <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                    <p class="text-sm text-slate-400">No organizational indicators available.</p>
                </div>

                <!-- Indicator rows -->
                <div
                    v-for="(indicator, index) in form.indicators"
                    :key="indicator.organizational_indicator_id"
                    class="bg-white rounded-xl border border-slate-200 px-5 py-4 flex items-center gap-4"
                >
                    <!-- Index badge -->
                    <span class="text-[10px] font-mono font-bold text-slate-300 shrink-0 w-5 text-right">
                        {{ index + 1 }}
                    </span>

                    <!-- Name -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-700 leading-snug">{{ indicator.name }}</p>
                    </div>

                    <!-- Value input -->
                    <div class="flex items-center gap-2 shrink-0">
                        <input
                            v-model.number="indicator.value"
                            type="text"
                            class="w-20 px-2 py-1.5 text-sm text-center font-semibold bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
                        />
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2 py-3">
                    <button
                        type="button"
                        @click="router.visit('/barangays')"
                        class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                    >
                        <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        Save Indicators
                    </button>
                </div>

            </div>
        </div>

    </div>
</template>