<script setup>
import Main from '@/layouts/main.vue'
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { useToast } from 'primevue/usetoast'

defineOptions({ layout: Main })

const toast = useToast()

const props = defineProps({
    activities: { type: Object, default: () => ({}) },
    filters:    { type: Object, default: () => ({}) },
})

const search = ref(props.filters.search ?? '')

let debounceTimer
const onSearch = () => {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
        router.get('/pk-activities', { search: search.value }, { preserveState: true, replace: true })
    }, 300)
}
</script>

<template>
<div class="h-full flex flex-col gap-5">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Activities</h1>
            <p class="text-xs text-slate-400 mt-1">List and manage purokalusugan activities.</p>
        </div>
        <button
            type="button"
            @click="router.visit('/pk-activities/create')"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
        >
            <Icon icon="hugeicons:plus-sign" class="text-sm" />
            New Activity
        </button>
    </div>

    <!-- ── Filters ───────────────────────────────────── -->
    <div class="flex items-center gap-3">
        <div class="relative flex-1 max-w-xs">
            <Icon icon="hugeicons:search-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            <input
                v-model="search"
                @input="onSearch"
                type="text"
                placeholder="Search activity..."
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 transition-all"
            />
        </div>

        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-400">
            <Icon icon="hugeicons:list-view" class="text-sm" />
            <span>{{ activities.total }} activities</span>
        </div>
    </div>

    <!-- ── Table ─────────────────────────────────────── -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto flex-1">
        <table class="w-full text-left">
            <thead class="bg-slate-100 border-b border-slate-100 sticky top-0">
                <tr>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">#</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Activity</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Date</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Type</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Programs</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Barangays</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Clients</th>
                    <th class="px-5 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">

                <!-- Empty state -->
                <tr v-if="activities.data.length === 0">
                    <td colspan="8" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                            <p class="text-sm font-medium text-slate-400">No activities found</p>
                            <p class="text-xs text-slate-300">Try adjusting your search or create a new activity.</p>
                        </div>
                    </td>
                </tr>

                <tr
                    v-for="(activity, index) in activities.data"
                    :key="activity.id"
                    class="hover:bg-slate-50/70 transition-colors"
                >
                    <!-- # -->
                    <td class="px-5 py-3.5 text-[11px] text-slate-300 font-bold tabular-nums">
                        {{ String((activities.current_page - 1) * activities.per_page + index + 1).padStart(2, '0') }}
                    </td>

                    <!-- Activity Name -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <Icon icon="hugeicons:health" class="text-sm text-indigo-500" />
                            </div>
                            <span class="text-sm font-medium text-slate-700">{{ activity.activity_name }}</span>
                        </div>
                    </td>

                    <!-- Date -->
                    <td class="px-5 py-3.5">
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-slate-700">{{ activity.date_start }}</span>
                            <span class="text-[10px] text-slate-400">to {{ activity.date_end }}</span>
                        </div>
                    </td>

                    <!-- Type -->
                    <td class="px-5 py-3.5">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold"
                            :class="activity.type === 'large'
                                ? 'bg-indigo-50 text-indigo-700'
                                : 'bg-slate-100 text-slate-600'"
                        >
                            {{ activity.type === 'large' ? 'Large Scale' : 'Small Scale' }}
                        </span>
                    </td>

                    <!-- Programs -->
                    <td class="px-5 py-3.5">
                        <div class="flex flex-wrap gap-1">
                            <span
                                v-for="program in activity.programs?.slice(0, 2)"
                                :key="program.id"
                                class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-medium rounded-full"
                            >
                                {{ program.name }}
                            </span>
                            <span
                                v-if="activity.programs?.length > 2"
                                class="inline-flex px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-medium rounded-full"
                            >
                                +{{ activity.programs.length - 2 }} more
                            </span>
                        </div>
                    </td>

                    <!-- Barangays -->
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-full text-[11px] font-medium text-slate-600">
                            <Icon icon="hugeicons:map-pinpoint-01" class="text-xs text-slate-400" />
                            {{ activity.barangays_count ?? activity.barangays?.length ?? 0 }} barangays
                        </span>
                    </td>

                    <!-- Total Clients -->
                    <td class="px-5 py-3.5">
                        <span class="text-sm font-semibold text-slate-700">{{ activity.total_clients.toLocaleString() }}</span>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1.5">
                            <!-- <button
                                type="button"
                                @click="router.visit(`/pk-activities/${activity.id}`)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:view" class="text-xs" />
                                View
                            </button> -->
                            <button
                                type="button"
                                @click="router.visit(`/pk-activities/${activity.id}/edit`)"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                            >
                                <Icon icon="hugeicons:pencil-edit-01" class="text-xs" />
                                Edit
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- ── Pagination ─────────────────────────────────── -->
    <div class="flex items-center justify-between shrink-0">
        <p class="text-xs text-slate-400">
            Showing {{ activities.from ?? 0 }}–{{ activities.to ?? 0 }} of {{ activities.total }} activities
        </p>
        <div class="flex items-center gap-1">
            <button
                v-for="link in activities.links"
                :key="link.label"
                :disabled="!link.url"
                @click="link.url && router.visit(link.url, { preserveState: true })"
                class="px-3 py-1.5 text-[11px] font-medium rounded-lg border transition-all"
                :class="link.active
                    ? 'bg-indigo-600 border-indigo-600 text-white'
                    : 'border-slate-200 text-slate-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 disabled:opacity-40 disabled:cursor-not-allowed'"
                v-html="link.label"
            />
        </div>
    </div>

</div>
</template>