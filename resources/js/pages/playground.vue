<script setup>
    import { ref } from 'vue';
    import { Icon } from '@iconify/vue';

    // Sidebar state for mobile viewport toggle
    const isSidebarOpen = ref(false);

</script>

<template>
  <!-- Main App Container -->
  <main class="w-full h-screen flex justify-start items-start bg-slate-50 text-slate-800 antialiased font-sans relative overflow-hidden">

    <!-- 1. MOBILE HEADER BAR (Hidden on Desktop) -->
    <header class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-slate-200 px-4 flex items-center justify-between z-30 shadow-sm">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 flex items-center justify-center rounded-lg bg-[#0D542B]/10 text-[#0D542B]">
          <Icon icon="lucide:activity" class="text-xl stroke-[2.5]"/>
        </div>
        <div class="flex flex-col leading-tight">
          <span class="font-black text-xs tracking-wider text-[#0D542B]">DAP-AY</span>
          <span class="font-bold text-[9px] text-emerald-600 uppercase tracking-wider">Reporting System</span>
        </div>
      </div>
      
      <!-- Hamburger Menu Toggle Button -->
      <button 
        @click="isSidebarOpen = !isSidebarOpen"
        class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#0D542B]/20 transition-all"
        aria-label="Toggle Navigation Sidebar"
      >
        <Icon :icon="isSidebarOpen ? 'lucide:x' : 'lucide:menu'" class="text-2xl" />
      </button>
    </header>

    <!-- 2. MOBILE BACKDROP OVERLAY -->
    <div 
      v-if="isSidebarOpen" 
      @click="isSidebarOpen = false"
      class="lg:hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 transition-opacity duration-300"
    ></div>

    <!-- 3. SIDEBAR COMPONENT (Responsive Canvas Layering) -->
    <aside 
      :class="[
        'w-[280px] h-full flex flex-col justify-between items-start bg-white border-r border-slate-200 p-4 shadow-xl lg:shadow-sm fixed lg:static top-0 bottom-0 left-0 z-50 transition-transform duration-300 ease-in-out',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]"
    >
      
      <!-- DOH Branding Header Section -->
      <section class="w-full flex justify-start items-center gap-3 px-2 py-3 mb-6 border-b border-slate-100 pb-5">
        <div class="h-11 w-11 flex items-center justify-center rounded-xl bg-[#0D542B]/10 text-[#0D542B] shrink-0">
          <Icon icon="lucide:activity" class="text-2xl stroke-[2.5]"/>
        </div>
        <div class="flex flex-col justify-center items-start leading-tight">
          <span class="font-black text-sm tracking-wider text-[#0D542B]">DAP-AY</span>
          <span class="font-bold text-[10px] text-emerald-600 tracking-wider uppercase">Reporting System</span>
        </div>
      </section>

      <!-- Navigation Links Section -->
      <section class="w-full flex-1 flex flex-col gap-6 overflow-y-auto pr-1 custom-scrollbar">
        
        <!-- Group 1: Health Telemetry & Operations -->
        <div class="w-full flex flex-col gap-1">
          <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Operations</span>
          
          <!-- Active Link Style -->
          <button class="w-full flex items-center justify-between px-3 py-2.5 bg-[#0D542B] text-white rounded-xl font-semibold text-sm shadow-sm hover:bg-[#093d1f] transition-all duration-150">
            <div class="flex items-center gap-3">
              <Icon icon="lucide:layout-dashboard" class="text-lg" />
              <span>Dashboard</span>
            </div>
            <span class="bg-white/20 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md">Realtime</span>
          </button>
          
          <!-- Idle Link -->
          <button class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:text-[#0D542B] hover:bg-[#0D542B]/5 rounded-xl font-medium text-sm transition-all duration-150 group">
            <Icon icon="lucide:file-bar-chart-2" class="text-lg text-slate-400 group-hover:text-[#0D542B] transition-colors" />
            <span>Health Metrics</span>
          </button>
        </div>

        <!-- Group 2: Document Registry & Compliance -->
        <div class="w-full flex flex-col gap-1">
          <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Records & Registry</span>
          
          <button class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:text-[#0D542B] hover:bg-[#0D542B]/5 rounded-xl font-medium text-sm transition-all duration-150 group">
            <Icon icon="lucide:folder-heart" class="text-lg text-slate-400 group-hover:text-[#0D542B] transition-colors" />
            <span>Patient Logs</span>
          </button>

          <button class="w-full flex items-center justify-between px-3 py-2.5 text-slate-600 hover:text-[#0D542B] hover:bg-[#0D542B]/5 rounded-xl font-medium text-sm transition-all duration-150 group">
            <div class="flex items-center gap-3">
              <Icon icon="lucide:clipboard-list" class="text-lg text-slate-400 group-hover:text-[#0D542B] transition-colors" />
              <span>DOH Submissions</span>
            </div>
            <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
          </button>
        </div>

        <!-- Group 3: System Controls -->
        <div class="w-full flex flex-col gap-1">
          <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">System Configuration</span>
          
          <button class="w-full flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:text-[#0D542B] hover:bg-[#0D542B]/5 rounded-xl font-medium text-sm transition-all duration-150 group">
            <Icon icon="lucide:shield-check" class="text-lg text-slate-400 group-hover:text-[#0D542B] transition-colors" />
            <span>Access Control</span>
          </button>
        </div>

      </section>

      <!-- Footer / User Profile Section -->
      <section class="w-full pt-4 mt-4 border-t border-slate-100">
        <div class="w-full flex justify-between items-center p-2 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-100 cursor-pointer transition-all duration-150 group">
          <div class="flex justify-start items-center gap-3 min-w-0">
            <div class="relative w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 shrink-0">
              <Icon icon="lucide:user" class="text-base text-slate-500"/>
              <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
            </div>
            <div class="flex flex-col justify-center items-start leading-tight min-w-0">
              <span class="font-bold text-slate-700 text-xs tracking-wide truncate w-full">Dr. Maria Dela Cruz</span>
              <span class="font-medium text-slate-400 text-[11px] truncate w-full">Health Officer IV</span>
            </div>
          </div>
          <button class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all shrink-0">
            <Icon icon="lucide:chevrons-up-down" class="text-sm"/>
          </button>
        </div>
      </section>

    </aside>

    <!-- 4. MAIN WORKSPACE CANVAS AREA -->
    <!-- Added pt-16 to shift content below the mobile top bar, and lg:pt-0 to reset on desktop -->
    <div class="flex-1 h-full pt-16 lg:pt-0 overflow-y-auto">
      <div class="p-4 sm:p-6 lg:p-8">
        
        <!-- Quick Placeholder Page Title Dashboard Card -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200 shadow-sm mb-6">
          <h1 class="text-xl sm:text-2xl font-bold text-slate-900">System Dashboard Overview</h1>
          <p class="text-sm text-slate-500 mt-1">Welcome back! Here is the latest telemetry distribution for your region.</p>
        </div>

      </div>
    </div>

  </main>
</template>

<style scoped>
    .custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(13, 84, 43, 0.08);
    border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(13, 84, 43, 0.2);
    }
</style>