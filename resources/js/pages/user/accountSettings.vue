<script setup>
    import { ref } from 'vue'
    import { Icon } from '@iconify/vue'
    import { useForm, usePage } from '@inertiajs/vue3'
    import { useToast } from 'primevue/usetoast'
    import InputText from 'primevue/inputtext'
    import Password from 'primevue/password'
    import main from '@/layouts/main.vue'

    const page = usePage()

    defineOptions({ layout: main })

    const toast = useToast()

    const profileForm = useForm({
        name:  page.props.auth.user.name,
        email: page.props.auth.user.email,
    })

    const passwordForm = useForm({
        password:               '',
    })

    const submitProfile = () => {
        profileForm.put('/account-settings-update', {
            onSuccess: () => toast.add({ severity: 'success', summary: 'Profile Updated', detail: 'Your name and email have been saved.', life: 3000 }),
            onError:   () => toast.add({ severity: 'error',   summary: 'Update Failed',   detail: 'Please check the fields and try again.', life: 3000 }),
        })
    }

    const submitPassword = () => {
        passwordForm.put('/password-update', {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Password Changed', detail: 'Your password has been updated successfully.', life: 3000 })
                passwordForm.reset()
            },
            onError: () => toast.add({ severity: 'error', summary: 'Update Failed', detail: 'Please check your current password and try again.', life: 3000 }),
        })
    }
</script>

<template>
<div class="h-full flex flex-col gap-6">

    <!-- ── Page header ──────────────────────────────── -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none">Account Settings</h1>
            <p class="text-xs text-slate-400 mt-1">Manage your account preferences and information.</p>
        </div>
    </div>

    <div class="flex flex-col gap-5 max-w-2xl">

        <!-- ── Profile section ──────────────────────── -->
        <!-- <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                    <Icon icon="hugeicons:user-circle" class="text-sm text-indigo-500" />
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-700 leading-none">Profile Information</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Update your name and email address.</p>
                </div>
            </div>

            <form @submit.prevent="submitProfile" class="px-6 py-5 flex flex-col gap-5">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">
                        Full Name <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <Icon icon="hugeicons:user-circle" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                        <InputText
                            v-model="profileForm.name"
                            placeholder="e.g. Juan dela Cruz"
                            class="w-full !pl-9 !py-2.5 !text-sm"
                            :class="profileForm.errors.name ? '!border-red-400' : ''"
                        />
                    </div>
                    <p v-if="profileForm.errors.name" class="text-[11px] text-red-500 flex items-center gap-1">
                        <Icon icon="hugeicons:alert-circle" class="text-xs" />
                        {{ profileForm.errors.name }}
                    </p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">
                        Email Address <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <Icon icon="hugeicons:mail-01" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
                        <InputText
                            v-model="profileForm.email"
                            type="email"
                            placeholder="e.g. juan@health.gov.ph"
                            class="w-full !pl-9 !py-2.5 !text-sm"
                            :class="profileForm.errors.email ? '!border-red-400' : ''"
                        />
                    </div>
                    <p v-if="profileForm.errors.email" class="text-[11px] text-red-500 flex items-center gap-1">
                        <Icon icon="hugeicons:alert-circle" class="text-xs" />
                        {{ profileForm.errors.email }}
                    </p>
                </div>

            
                <div class="flex justify-end pt-1">
                    <button
                        type="submit"
                        :disabled="profileForm.processing"
                        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200"
                    >
                        <Icon v-if="profileForm.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        <Icon v-else icon="hugeicons:floppy-disk" class="text-sm" />
                        Save Profile
                    </button>
                </div>

            </form>
        </div> -->

        <!-- ── Password section ──────────────────────── -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

            <!-- Section header -->
            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                    <Icon icon="hugeicons:lock-key" class="text-sm text-amber-500" />
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-700 leading-none">Change Password</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Ensure your account uses a strong password.</p>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitPassword" class="px-6 py-5 flex flex-col gap-5">

                <!-- New password -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600">
                        New Password <span class="text-red-400">*</span>
                    </label>
                    <Password
                        v-model="passwordForm.password"
                        toggleMask
                        placeholder="Enter new password"
                        inputClass="w-full !py-2.5 !text-sm"
                        class="w-full"
                        :class="passwordForm.errors.password ? '[&>input]:!border-red-400' : ''"
                    />
                    <p v-if="passwordForm.errors.password" class="text-[11px] text-red-500 flex items-center gap-1">
                        <Icon icon="hugeicons:alert-circle" class="text-xs" />
                        {{ passwordForm.errors.password }}
                    </p>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-1">
                    <button
                        type="submit"
                        :disabled="passwordForm.processing"
                        class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 disabled:opacity-60 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-amber-200"
                    >
                        <Icon v-if="passwordForm.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                        <Icon v-else icon="hugeicons:lock-password" class="text-sm" />
                        Update Password
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
</template>