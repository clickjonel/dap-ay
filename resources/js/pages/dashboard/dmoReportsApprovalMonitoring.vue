<script setup>
    import Main from '@/layouts/main.vue'
    import { computed } from 'vue'
    import { Icon } from '@iconify/vue'

    defineOptions({ layout: Main })

    const props = defineProps({
        users: { type: Array, default: () => [] }
    })

    const totalApproved = computed(() => props.users.reduce((sum, u) => sum + (u.approved ?? 0), 0))
    const totalRejected = computed(() => props.users.reduce((sum, u) => sum + (u.rejected ?? 0), 0))

    const approvalRate = (user) => {
        const total = (user.approved ?? 0) + (user.rejected ?? 0)
        if (total === 0) return 0
        return Math.round((user.approved / total) * 100)
    }

    const initials = (name) =>
        name?.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase() ?? '?'
</script>

<template>
    <div class="h-full flex flex-col gap-5 p-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-none">DMO Approval Monitoring</h1>
                <p class="text-xs text-slate-400 mt-1">Report actions by officer</p>
            </div>
            <span class="text-[11px] px-3 py-1.5 bg-slate-100 text-slate-500 rounded-lg border border-slate-200">
                {{ users.length }} officers
            </span>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-[11px] text-slate-400 mb-1">Total Officers</p>
                <p class="text-2xl font-semibold text-slate-700">{{ users.length }}</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4">
                <p class="text-[11px] text-emerald-600 mb-1">Total Approved</p>
                <p class="text-2xl font-semibold text-emerald-700">{{ totalApproved }}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-4">
                <p class="text-[11px] text-red-400 mb-1">Total Rejected</p>
                <p class="text-2xl font-semibold text-red-600">{{ totalRejected }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden flex-1 overflow-y-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            DMO Officer
                        </th>
                        <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-center">
                            Approved
                        </th>
                        <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-center">
                            Rejected
                        </th>
                        <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-100 text-center">
                            Approval Rate
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">

                    <!-- Empty state -->
                    <tr v-if="users.length === 0">
                        <td colspan="4" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <Icon icon="hugeicons:folder-02" class="text-3xl text-slate-300" />
                                <p class="text-sm text-slate-400">No officers found</p>
                            </div>
                        </td>
                    </tr>

                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="hover:bg-slate-50/70 transition-colors"
                    >
                        <!-- Officer -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-semibold flex items-center justify-center shrink-0">
                                    {{ initials(user.name) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ user.name }}</span>
                            </div>
                        </td>

                        <!-- Approved -->
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" />
                                {{ user.approved ?? 0 }}
                            </span>
                        </td>

                        <!-- Rejected -->
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-600 rounded-full text-[11px] font-semibold">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400" />
                                {{ user.rejected ?? 0 }}
                            </span>
                        </td>

                        <!-- Approval Rate -->
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-[11px] text-slate-500 w-8 text-right">{{ approvalRate(user) }}%</span>
                                <div class="w-16 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-emerald-500 transition-all"
                                        :style="{ width: `${approvalRate(user)}%` }"
                                    />
                                </div>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</template>