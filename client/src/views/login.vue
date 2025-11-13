<template>
    <div class="min-h-screen h-screen w-full bg-gray-100 flex justify-center items-center">
        <Card class="w-[500px] h-[700px]">
            <template #content>
                <div class="w-full flex flex-col justify-start items-start gap-4">

                    <div class="w-full flex flex-col justify-center items-center">
                        <img :src="dohLogo" alt="DOH Logo" class="size-24 my-4"></img>
                        <span class="w-full text-center font-bold text-xl uppercase">Dap-ay Information System (Purokalusugan)</span>
                        <span class="w-full text-center font-medium text-sm text-gray-600">Department of Health - Center for Health Development</span>
                        <span class="w-full text-center font-medium text-sm text-gray-600">Cordillera Administrative Region</span>
                        <span class="w-full text-center font-semibold text-lg mt-4">Welcome Back</span>
                    </div>

                    <form @submit.prevent="login" class="w-full flex flex-col justify-center items-center gap-[20px] mt-6">
                        <div class="w-full">
                            <label class="font-medium uppercase">Username</label>
                            <InputText v-model="credentials.username" class="w-full font-bold" required/>
                        </div>
                        <div class="w-full">
                            <label class="font-medium uppercase">Password</label>
                            <Password v-model="credentials.password" :feedback="false" toggle-mask class="w-full font-bold" required
                                :pt="{
                                    pcInputText:{
                                        root: { class: 'w-full' },
                                        input: { class: 'w-full' }
                                    }
                                }"
                            />
                        </div>
                        <Button type="submit" label="Sign In" icon="pi pi-sign-in" class="w-full"/>
                    </form>

                    <div class="w-full flex flex-col justify-center items-center text-sm text-gray-400 text-center">
                        <span>© 2025 Department of Health</span>
                        <span>Center for Health Development - Cordillera Administrative Region</span>
                        <span>Developed By: ICTMU</span>
                    </div>

                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import dohLogo from '@/assets/images/doh-logo.svg';
    import Card from 'primevue/card';
    import InputText from 'primevue/inputtext';
    import Button from "primevue/button"
    import Password from 'primevue/password';
    import { useToast } from 'primevue/usetoast';
    import axios from '@/utils/axios.js'
    import { useAuthStore } from '@/stores/auth';
    import { useRouter } from 'vue-router';

    const store = useAuthStore()
    const router = useRouter()
    const toast = useToast();
    const credentials = ref({
        username:'',
        password:''
    });

    const validateCredentials = () => {
        if(credentials.value.username.trim() && credentials.value.password.trim()){
            return true;
        }
        toast.add({ severity: 'error', summary: 'Required', detail: 'Username and Password fields are required', life: 3000 });
        return false;
    }

    const login = () => {
        const validated = validateCredentials()
        if(validated){
            axios.post('login',credentials.value)
            .then((response) => {
                store.setUser(response.data.user)
                store.setTeams(response.data.user?.teams ?? [])
                store.setToken(response.data.token)
                store.isAuthenticated = true
                router.push({ path: '/dashboard' })
            })
            .catch((error) => {
                toast.add({ severity: 'error', summary: 'Invalid Credentials', detail: error.response.data.message, life: 3000 });
                // console.log(error)
            })
            .finally(()=>{

            })
        }

    }


</script>