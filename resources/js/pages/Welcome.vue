<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="PuroKalusugan | Modern Health Reporting" />

    <div class="min-h-screen bg-white font-sans selection:bg-emerald-100 selection:text-emerald-900">

        <!-- ── Nav ─────────────────────────────────────── -->
        <nav class="flex items-center justify-between px-5 sm:px-8 py-5 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 flex-shrink-0">
                    <svg class="text-white w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-xl font-bold tracking-tight text-slate-900">PuroKalusugan</span>
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-[0.2em] font-bold text-emerald-600">Reporting System</span>
                </div>
            </div>

            <div class="flex items-center gap-4 sm:gap-8 text-sm font-semibold text-slate-600">
                <a href="#" class="hidden sm:block hover:text-emerald-600 transition">About</a>
                <a href="#" class="hidden sm:block hover:text-emerald-600 transition">Guidelines</a>
                <Link
                    href="/login"
                    class="px-4 py-2 sm:px-5 sm:py-2.5 text-xs sm:text-sm rounded-full border border-slate-200 hover:border-emerald-600 hover:text-emerald-600 transition-all"
                >
                    Login
                </Link>
            </div>
        </nav>

        <!-- ── Hero ────────────────────────────────────── -->
        <header class="relative overflow-hidden pt-10 pb-16 sm:pt-16 sm:pb-24 lg:pt-32">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
                <div class="absolute top-[-10%] left-[10%] w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[200px] sm:w-[400px] h-[200px] sm:h-[400px] bg-blue-50 rounded-full blur-3xl opacity-60"></div>
            </div>

            <div class="max-w-7xl mx-auto px-5 sm:px-8 grid lg:grid-cols-2 gap-10 lg:gap-12 items-center">

                <!-- Left -->
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-5 sm:mb-6 border border-emerald-100">
                        <span class="relative flex h-2 w-2 flex-shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        NOW LIVE IN CAR REGION
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-5 sm:mb-6">
                        Data-Driven <br/>
                        <span class="text-emerald-600">Community Health.</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed mb-8 sm:mb-10 max-w-lg">
                        Streamlining the consolidation of health reports from every Barangay. Empowering local health units with real-time insights for a healthier Cordillera.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-200 hover:bg-emerald-600 hover:shadow-emerald-100 transition-all flex items-center justify-center gap-2 group text-sm sm:text-base">
                            See Dashboards
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6" />
                            </svg>
                        </button>
                        <button class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 bg-white text-slate-900 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all text-sm sm:text-base">
                            View Analytics
                        </button>
                    </div>
                </div>

                <!-- Right (hidden on small, visible from lg) -->
                <div class="relative hidden lg:block">
                    <div class="bg-white p-8 rounded-[2rem] shadow-2xl border border-slate-100 relative z-10">
                        <div class="space-y-4">
                            <div class="h-12 w-full bg-slate-50 rounded-xl flex items-center px-4 gap-4 border border-slate-100">
                                <div class="w-6 h-6 rounded-full bg-emerald-100 flex-shrink-0"></div>
                                <div class="h-2 w-32 bg-slate-200 rounded"></div>
                                <div class="ml-auto h-2 w-12 bg-emerald-400 rounded flex-shrink-0"></div>
                            </div>
                            <div class="h-12 w-full bg-slate-50 rounded-xl flex items-center px-4 gap-4 border border-slate-100">
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex-shrink-0"></div>
                                <div class="h-2 w-24 bg-slate-200 rounded"></div>
                                <div class="ml-auto h-2 w-16 bg-blue-400 rounded flex-shrink-0"></div>
                            </div>
                            <div class="h-32 w-full bg-emerald-600 rounded-2xl flex flex-col justify-end p-4 text-white">
                                <span class="text-xs opacity-80 uppercase font-bold tracking-widest">Indicators</span>
                                <span class="text-3xl font-black">Programs</span>
                                <span class="text-xs opacity-80 uppercase font-bold tracking-widest">Human Resources</span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-emerald-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
                    <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
                </div>

            </div>
        </header>

        <!-- ── Features ─────────────────────────────────── -->
        <section class="py-14 sm:py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 grid sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">

                <div class="group">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 mb-5 sm:mb-6 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-slate-900">Seamless Input</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Simplified forms designed for rapid data entry by Purok health workers.</p>
                </div>

                <div class="group">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 mb-5 sm:mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-slate-900">Real-time Sync</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Data is instantly consolidated, providing immediate insights for Barangay Health Units.</p>
                </div>

                <div class="group sm:col-span-2 md:col-span-1">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 mb-5 sm:mb-6 group-hover:scale-110 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-slate-900">Secure Storage</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Enterprise-grade security to ensure sensitive patient and community data stays protected.</p>
                </div>

            </div>
        </section>

    </div>
</template>