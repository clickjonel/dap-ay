<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

</script>

<template>
    <Head title="PuroKalusugan | Modern Health Reporting" />

    <div class="min-h-screen bg-white font-sans selection:bg-emerald-100 selection:text-emerald-900">
        <nav class="flex items-center justify-between px-8 py-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                    <Activity class="text-white w-6 h-6" />
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight text-slate-900">PuroKalusugan</span>
                    <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-emerald-600">Reporting System</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#" class="hover:text-emerald-600 transition">About</a>
                <a href="#" class="hover:text-emerald-600 transition">Guidelines</a>
                <Link href="/login" class="px-5 py-2.5 rounded-full border border-slate-200 hover:border-emerald-600 hover:text-emerald-600 transition-all">
                    Login
                </Link>
            </div>
        </nav>

        <header class="relative overflow-hidden pt-16 pb-24 lg:pt-32">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
                <div class="absolute top-[-10%] left-[10%] w-[500px] h-[500px] bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-blue-50 rounded-full blur-3xl opacity-60"></div>
            </div>

            <div class="max-w-7xl mx-auto px-8 grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-6 border border-emerald-100">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        NOW LIVE IN CAR REGION
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-6">
                        Data-Driven <br/> 
                        <span class="text-emerald-600">Community Health.</span>
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed mb-10 max-w-lg">
                        Streamlining the consolidation of health reports from every Barangay. Empowering local health units with real-time insights for a healthier Cordillera.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-200 hover:bg-emerald-600 hover:shadow-emerald-100 transition-all flex items-center justify-center gap-2 group">
                            See Dashboards
                            <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </button>
                        <button class="px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                            View Analytics
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-white p-8 rounded-[2rem] shadow-2xl border border-slate-100 relative z-10">
                        <div class="space-y-4">
                            <div class="h-12 w-full bg-slate-50 rounded-xl flex items-center px-4 gap-4 border border-slate-100">
                                <div class="w-6 h-6 rounded-full bg-emerald-100"></div>
                                <div class="h-2 w-32 bg-slate-200 rounded"></div>
                                <div class="ml-auto h-2 w-12 bg-emerald-400 rounded"></div>
                            </div>
                            <div class="h-12 w-full bg-slate-50 rounded-xl flex items-center px-4 gap-4 border border-slate-100">
                                <div class="w-6 h-6 rounded-full bg-blue-100"></div>
                                <div class="h-2 w-24 bg-slate-200 rounded"></div>
                                <div class="ml-auto h-2 w-16 bg-blue-400 rounded"></div>
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

        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-3 gap-12">
                <div class="group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 mb-6 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <ClipboardCheck class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Seamless Input</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Simplified forms designed for rapid data entry by Purok health workers.</p>
                </div>
                <div class="group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <Activity class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Real-time Sync</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Data is instantly consolidated, providing immediate insights for Barangay Health Units.</p>
                </div>
                <div class="group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-100 mb-6 group-hover:scale-110 group-hover:bg-slate-900 group-hover:text-white transition-all">
                        <ShieldCheck class="w-6 h-6" />
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Secure Storage</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Enterprise-grade security to ensure sensitive patient and community data stays protected.</p>
                </div>
            </div>
        </section>
    </div>
</template>