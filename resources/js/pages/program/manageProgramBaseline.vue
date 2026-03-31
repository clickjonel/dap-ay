<script setup>
import { ref } from 'vue'
import { Icon } from '@iconify/vue';
import { router } from '@inertiajs/vue3';
import Main from '@/layouts/main.vue';
import InputNumber from 'primevue/inputnumber';

defineOptions({
    layout: Main,
})

const props = defineProps({
    program: Object,
})

</script>

<template>
    <div class="h-full flex flex-col items-center gap-4 lg:gap-5 min-h-0">
        <!-- ── Header ─────────────────────────────────────────── -->
        <div class="w-full flex items-start sm:items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="router.visit('/program')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
                >
                    <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
                </button>
                <div>
                    <h1 class="text-base lg:text-lg font-bold text-slate-800 leading-none">{{ props.program.name }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Manage program baselines.</p>
                </div>
            </div>
            <button
                type="button"
                @click="openCreate"
                class="flex items-center gap-2 px-3 py-2 lg:px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200 shrink-0"
            >
                <Icon icon="hugeicons:plus-sign" class="text-sm" />
                <span class="hidden sm:inline">Add Member</span>
                <span class="sm:hidden">Add</span>
            </button>
        </div>

        <div class="w-full h-full flex justify-center items-center">
            <div class="w-[500px] flex flex-col justify-start items-start gap-4">

            </div>
        </div>

    </div>
</template>