<script setup>
import Main from '@/layouts/main.vue'
import { Icon } from '@iconify/vue'
import { ref, computed, onMounted } from 'vue'

defineOptions({ layout: Main })

const props = defineProps({
    provinces: Array,
    indicators:  Array
})

const selectedProvince = ref(props.provinces?.[0] ?? null)
const search           = ref('')

const selectProvince = (province) => {
    selectedProvince.value = province
    search.value           = ''
}

const getOrgIndicatorData = (barangay, indicatorID) => {
    return barangay.organizational_indicators?.find(pp => pp.org_indicator_id === indicatorID) ?? null
}

const filteredMunicipalities = computed(() => {
    if (!selectedProvince.value) return []
    if (!search.value) return selectedProvince.value.municipalities

    return selectedProvince.value.municipalities
        ?.map(municipality => ({
            ...municipality,
            barangays: municipality.barangays?.filter(b =>
                b.name.toLowerCase().includes(search.value.toLowerCase())
            )
        }))
        .filter(m =>
            m.name.toLowerCase().includes(search.value.toLowerCase()) ||
            m.barangays?.length > 0
        )
})
</script>

<template>
    <main class="w-full h-full flex justify-start items-start gap-0 overflow-hidden">

        <!-- Sidebar -->
        <div class="w-[200px] h-full flex flex-col shrink-0 bg-white border-r border-slate-100 overflow-auto">
            <div class="px-4 py-3 border-b border-slate-100 shrink-0">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Provinces</span>
            </div>
            <div class="flex-1 overflow-auto py-1.5">
                <button
                    v-for="province in props.provinces"
                    :key="province.id"
                    @click="selectProvince(province)"
                    :class="[
                        'w-full text-left px-4 py-2.5 text-xs font-medium uppercase tracking-wide transition-all',
                        selectedProvince?.id === province.id
                            ? 'bg-sky-50 text-sky-700 border-r-2 border-sky-500'
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'
                    ]"
                >
                    {{ province.name }}
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 h-full flex flex-col overflow-hidden bg-white">

            <!-- Top Bar -->
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 shrink-0">
                <div>
                    <h2 class="text-xs font-semibold text-slate-700">{{ selectedProvince?.name }}</h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Purokalusugan Program Coverage</p>
                </div>
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 w-56 focus-within:bg-white focus-within:border-sky-300 focus-within:ring-2 focus-within:ring-sky-50 transition-all">
                    <Icon icon="lucide:search" class="text-slate-300 text-xs shrink-0"/>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search municipality or barangay..."
                        class="flex-1 text-xs outline-none bg-transparent placeholder:text-slate-300 text-slate-600"
                    />
                    <button v-if="search" @click="search = ''" class="text-slate-300 hover:text-slate-500 shrink-0">
                        <Icon icon="lucide:x" class="text-xs"/>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="flex-1 overflow-auto">
                <table class="w-full text-xs border-collapse">
                    <thead class="sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wider bg-slate-50 border-b border-slate-100 w-44">
                                Barangay
                            </th>
                            <th
                                v-for="ind in props.indicators"
                                :key="ind.id"
                                class="px-2 py-2.5 text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wider bg-slate-50 border-b border-slate-100 w-20 break-words leading-tight"
                            >
                                {{ ind.indicator_name }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <template v-for="municipality in filteredMunicipalities" :key="municipality.id">

                            <!-- Municipality Header Row -->
                            <tr>
                                <td
                                    :colspan="props.indicators.length + 1"
                                    class="px-4 py-2 bg-slate-50 border-y border-slate-100"
                                >
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-md bg-indigo-50 flex items-center justify-center shrink-0">
                                            <Icon icon="lucide:building-2" class="text-indigo-400 text-[10px]"/>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700">{{ municipality.name }}</span>
                                        <span class="text-[10px] text-slate-400 bg-white border border-slate-100 px-2 py-0.5 rounded-full">
                                            {{ municipality.barangays?.length ?? 0 }} barangays
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Barangay Rows -->
                            <tr
                                v-for="(barangay, index) in municipality.barangays"
                                :key="barangay.id"
                                :class="['border-b border-slate-50 hover:bg-sky-50/20 transition-colors', index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30']"
                            >
                                <!-- Barangay Name -->
                                <td class="px-4 py-2.5 pl-8">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1 h-1 rounded-full bg-slate-300 shrink-0"/>
                                        <span class="text-slate-600 text-[11px]">{{ barangay.name }}</span>
                                    </div>
                                </td>

                                <!-- Program Cells -->
                                <td
                                    v-for="ind in props.indicators"
                                    :key="ind.id"
                                    class="px-2 py-2.5 text-center"
                                >
                                    <template v-if="getOrgIndicatorData(barangay, ind.id)">
                                        <div class="flex flex-col items-center leading-tight">
                                            <span class="text-sky-700 font-semibold text-[11px]">
                                                {{ getOrgIndicatorData(barangay, ind.id).value }}
                                            </span>
                                            <!-- <span class="text-[9px] text-slate-400">
                                                #{{ getOrgIndicatorData(barangay, ind.id).total }}
                                            </span> -->
                                        </div>
                                    </template>
                                    <template v-else>
                                        <span class="text-slate-200 text-[11px]">—</span>
                                    </template>
                                </td>
                            </tr>

                        </template>

                        <!-- Empty -->
                        <tr v-if="filteredMunicipalities?.length === 0">
                            <td :colspan="props.ind.length + 1" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                                        <Icon icon="lucide:search-x" class="text-slate-400"/>
                                    </div>
                                    <p class="text-sm text-slate-400">No results for "{{ search }}"</p>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>

    </main>
</template>