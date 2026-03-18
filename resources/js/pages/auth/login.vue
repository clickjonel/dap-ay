<script setup lang="ts">
    import { Head, useForm } from '@inertiajs/vue3';
    import { Icon } from "@iconify/vue";
    import { ref, onMounted } from 'vue';

    // PrimeVue Components
    import InputText from 'primevue/inputtext';
    import Password from 'primevue/password';
    import Checkbox from 'primevue/checkbox';
    import Button from 'primevue/button';
    import Message from 'primevue/message';

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.post('/login');
    };

</script>

<template>
    <Head title="Login | PuroKalusugan" />
    
    <div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-100/50 rounded-full blur-[120px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100/50 rounded-full blur-[120px] -ml-48 -mb-48"></div>

        <div class="w-full max-w-[420px] z-10">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-2xl shadow-lg mb-4">
                    <Icon icon="hugeicons:analytics-02" class="text-5xl text-white"/>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">DAP-AY Reporting System</h1>
                <p class="text-slate-500 font-medium mt-1">Purokalusugan Reporting System</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-[2rem] p-8 md:p-10 shadow-xl shadow-slate-200/60">
                <form @submit.prevent="submit" class="flex flex-col gap-6">

                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Email</label>
                        <span class="p-input-icon-left w-full">
                            <i class="pi pi-envelope text-slate-400 ml-3" />
                            <InputText 
                                id="email" 
                                v-model="form.email" 
                                type="email" 
                                placeholder="e.g. juan.delacruz@health.gov" 
                                class="w-full !rounded-xl !py-3.5 !pl-11 border-slate-200 focus:!border-emerald-500 transition-all"
                                :class="{ 'p-invalid': form.errors.email }"
                            />
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Password</label>
                            <!-- <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Forgot?</a> -->
                        </div>
                        <Password 
                            v-model="form.password" 
                            toggleMask 
                            :feedback="false"
                            placeholder="••••••••"
                            inputClass="w-full !rounded-xl !py-3.5 border-slate-200"
                            class="w-full ml-3"
                            :class="{ 'p-invalid': form.errors.password }"
                        />
                    </div>

                    <Button 
                        type="submit" 
                        label="Submit and Login" 
                        icon="pi pi-sign-in" 
                        iconPos="right"
                        :loading="form.processing"
                        class="!bg-emerald-600 !border-emerald-600 !py-4 !rounded-xl !font-bold !text-lg !shadow-emerald-100 hover:!bg-emerald-700 transition-all active:scale-[0.98]"
                    />
                </form>
            </div>

            <div class="mt-8 flex items-center justify-center gap-3 text-slate-400">
                <i class="pi pi-lock text-xs"></i>
                <span class="text-[10px] font-bold uppercase tracking-widest">Department of Health - For Authorized Users Only</span>
            </div>
        </div>
    </div>
</template>
