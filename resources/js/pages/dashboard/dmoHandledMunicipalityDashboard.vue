<script setup>
  import Main from '@/layouts/main.vue'
  import { reactive } from 'vue'

  defineOptions({ layout: Main })

  const props = defineProps({
    municipalities: Array,
  })

  const openPanels = reactive(new Set([0]))

  function toggle(i) {
    openPanels.has(i) ? openPanels.delete(i) : openPanels.add(i)
  }

  function totalReports(mun) {
    return mun.municipality.barangays.reduce((s, b) => s + (b.reports_count ?? 0), 0)
  }

  function totalPKActivities(mun) {
    return mun.municipality.barangays.reduce((s, b) => s + (b.pk_activities_count ?? 0), 0)
  }

  function gidaCount(mun) {
    return mun.municipality.barangays.filter(b => b.geography?.is_gida).length
  }

</script>

<template>
  <div class="space-y-4 py-2">
    <div
      v-for="(mun, i) in props.municipalities"
      :key="mun.municipality.id"
      class="rounded-xl border border-gray-200 overflow-hidden bg-white"
    >
      <!-- Header -->
      <button
        class="w-full flex items-center gap-4 px-5 py-4 bg-gray-50 hover:bg-gray-100 transition-colors border-b border-gray-200"
        @click="toggle(i)"
      >
        <!-- Icon -->
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#0F6E56" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
            <circle cx="12" cy="9" r="2.5"/>
          </svg>
        </div>

        <!-- Info -->
        <div class="flex-1 text-left">
          <p class="text-[11px] font-medium uppercase tracking-widest text-gray-400">Municipality</p>
          <p class="text-base font-medium text-gray-900">{{ mun.municipality.name }}</p>
        </div>

        <!-- Pills -->
        <div class="flex items-center gap-2 shrink-0">
          <span class="text-xs font-medium px-3 py-1 rounded-full bg-blue-50 text-blue-700">
            {{ mun.municipality.barangays.length }} barangays
          </span>
          <span class="text-xs font-medium px-3 py-1 rounded-full bg-emerald-50 text-emerald-700">
            {{ totalReports(mun) }} reports
          </span>
          <span class="text-xs font-medium px-3 py-1 rounded-full bg-sky-100 text-emerald-700">
            {{ totalPKActivities(mun) }} PK Activities
          </span>
          <span class="text-xs font-medium px-3 py-1 rounded-full bg-gray-100 text-gray-600">
            {{ gidaCount(mun) }} GIDA
          </span>
        </div>

        <!-- Chevron -->
        <svg
          class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
          :class="{ 'rotate-180': openPanels.has(i) }"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        >
          <polyline points="6 9 12 15 18 9"/>
        </svg>
      </button>

      <!-- Barangay Grid -->
      <div v-if="openPanels.has(i)" class="p-5">
        <p class="text-[11px] font-medium uppercase tracking-widest text-gray-400 mb-3">Barangays</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
          <div
            v-for="brgy in mun.municipality.barangays"
            :key="brgy.id"
            class="border border-gray-100 rounded-lg overflow-hidden hover:border-gray-300 transition-colors"
          >
            <!-- Card Name -->
            <div class="px-3.5 py-2.5 bg-gray-50 border-b border-gray-100">
              <p class="text-[13px] font-medium text-gray-800">{{ brgy.name }}</p>
            </div>

            <!-- Big Metrics -->
            <div class="grid grid-cols-2 divide-x divide-gray-100 border-b border-gray-100">
              <div class="px-3 py-2.5">
                <p class="text-lg font-medium text-gray-900 leading-none">{{ brgy.reports_count ?? 0 }}</p>
                <p class="text-[11px] text-gray-400 mt-1">Reports</p>
              </div>
              <div class="px-3 py-2.5">
                <p class="text-lg font-medium text-gray-900 leading-none">{{ brgy.pk_activities_count ?? 0 }}</p>
                <p class="text-[11px] text-gray-400 mt-1">PK activities</p>
              </div>
            </div>

            <!-- Flags -->
            <div class="px-3.5 py-2.5 flex flex-col gap-1.5">
              <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">PK status</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full">
                  {{ brgy.pk_profile?.pk_status ?? '—' }}
                </span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">PK site</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full"
                  :class="brgy.has_pk_site ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">
                  {{ brgy.pk_profile?.pk_site === true ? 'Yes' : 'No' }} 
                </span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">GIDA area</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full"
                  :class="brgy.is_gida ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'">
                  {{ brgy.geography?.is_gida === true ? 'Yes' : 'No' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>