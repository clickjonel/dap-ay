<script setup>
    import { ref } from 'vue'
    import { Icon } from '@iconify/vue'
    import { useForm, usePage } from '@inertiajs/vue3'
    import { useToast } from 'primevue/usetoast'
    import InputText from 'primevue/inputtext'
    import Password from 'primevue/password'
    import main from '@/layouts/main.vue'
    import FloatLabel from 'primevue/floatlabel'

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
        profileForm.put('/user/profile', {
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
                <h1 class="text-lg font-bold text-slate-800 leading-none">User Profile</h1>
                <p class="text-xs text-slate-400 mt-1">Manage your user profile information.</p>
            </div>
        </div>

        <div class="flex flex-col gap-5 max-w-2xl">

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                        <Icon icon="hugeicons:user-circle" class="text-sm text-indigo-500" />
                    </div>
                </div>

                <div class="px-6 py-5 flex flex-col gap-5">
                    <form @submit.prevent="submitProfile" class="flex flex-col gap-5">
                        <FloatLabel for="name" value="Name">
                            <InputText id="name" v-model="profileForm.name" class="w-full" />
                        </FloatLabel>
                        <FloatLabel for="email" value="Email">
                            <InputText id="email" v-model="profileForm.email" class="w-full" />
                        </FloatLabel>
                        <div class="flex justify-end pt-1">
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shadow-indigo-200">
                                <Icon icon="hugeicons:floppy-disk" class="text-sm" />
                                Save Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>