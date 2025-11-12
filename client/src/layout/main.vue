<template>
    <div class="w-full min-h-screen h-screen flex justify-start items-start gap-4 p-2">

        <div class="hidden md:w-[200px] lg:w-[300px] h-full md:flex flex-col justify-start items-start p-2 rounded-2xl shadow-md bg-gray-200 gap-4">
            <span class="text-xl font-bold">Navigation Links</span>
            <div class="w-full flex flex-col justify-start items-start gap-2">
                <Button @click="handleNavigation('/dashboard')" label="Dashboard" severity="info" class="w-full" />
                <Button @click="handleNavigation('/programs')" label="Programs" severity="info" class="w-full" />
                <Button @click="handleNavigation('/users')" label="Users" severity="info" class="w-full" />
                <Button @click="handleNavigation('/activities')" label="Activities" severity="info" class="w-full" />
                <Button @click="handleNavigation('/sites')" label="PK Sites" severity="info" class="w-full" />
                <Button @click="handleNavigation('/teams')" label="PK Teams" severity="info" class="w-full" />
                <Button @click="handleNavigation('/baseline-indicators')" label="PK Baseline Indicators" severity="info" class="w-full" />
                <Button @click="handleNavigation('/reports')" label="Reports" severity="info" class="w-full" />
                <Button @click="handleNavigation('/indicators')" label="Indicators" severity="info" class="w-full" />
                <Button @click="handleNavigation('/server-logs')" label="Server Logs" severity="info" class="w-full" />
                <Button @click="handleNavigation('/barangays')" label="Barangays" severity="info" class="w-full" />
            </div>
        </div>

        <div class="w-full h-full flex flex-col justify-start items-start gap-2">
            <div class="w-full min-h-[60px] flex justify-between items-center p-2 rounded-xl shadow-md bg-gray-200">
                <span class="md:hidden font-bold uppercase">Purokalusugan</span>
                <span class="md:hidden">
                    <Button @click="toggleMobileNavigationPopover" icon="pi pi-bars" outlined severity="secondary" size="small" class="shadow-md shadow-slate-400"/>
                </span>
                <span class="hidden md:flex text-xl font-bold uppercase text-shadow-md shadow-slate-600 px-4">{{ route.name ?? 'Purokalusugan' }}</span>
                <span class="hidden md:flex">
                    <Button @click="toggleProfilePopover" :label="auth.user?.nickname ?? ''" severity="info" class="uppercase shadow-md shadow-slate-600"/>
                </span>
            </div>
            <div class="w-full max-h-full h-full p-4 overflow-y-scroll">
                <router-view></router-view>
            </div>
            <div class="w-full min-h-[60px] flex justify-between items-center bg-gray-200 px-4 py-2 rounded-2xl shadow-md">
                <div class="flex justify-center items-center gap-2">
                    <img :src="dohLogo" alt="DOH Logo" class="size-[50px]">
                    <img :src="chdCarLogo" alt="DOH Car Logo" class="size-[50px] rounded-full">
                    <!-- <img src="" alt="Bagong Pilipinas Logo">
                    <img src="" alt="Dap-ay Logo"> -->
                </div>
                <div class="flex flex-col justify-start items-center gap-1 text-xs font-medium text-gray-600">
                    <span>Dap-ay Information System (Purokalusugan)</span>
                    <span>Developed by ICTMU-CHD-CAR</span>
                </div>
            </div>
        </div>

    </div>

    <Popover ref="mobileNavigationPopover">
        <div class="flex flex-col gap-2 w-[300px]">
            <span class="font-semibold uppercase">Navigation Links</span>
            <Button label="Dashboard" severity="info" />
            <Button label="Programs" severity="info" />
            <Button label="Users" severity="info" />
            <Button label="Activities" severity="info" />
            <Button label="PK Sites" severity="info" />
            <Button @click="logout" label="Logout" severity="danger" />
        </div>
    </Popover>

    <Popover ref="profilePopover">
        <div class="flex flex-col gap-2 w-[300px]">
            <span class="font-semibold uppercase">User Profile</span>
            <span>Team 1</span>
            <span>Team 2</span>
            <Button @click="logout" label="Logout" severity="danger" />
        </div>
    </Popover>



</template>

<script setup>
    import { ref } from 'vue';
    import { Button,Popover } from 'primevue';
    import { useRoute,useRouter  } from 'vue-router';
    import { useAuthStore } from '@/stores/auth';
    import axios from '@/utils/axios'
    import { useToast } from 'primevue/usetoast';

    //logos
    import dohLogo from '@/assets/images/doh-logo.svg';
    import chdCarLogo from '@/assets/images/chd-car.jpg';

    const toast = useToast();
    const route = useRoute();
    const router = useRouter();
    const auth = useAuthStore()
    const mobileNavigationPopover = ref()
    const profilePopover = ref()

    const toggleMobileNavigationPopover = (event) => {
        mobileNavigationPopover.value.toggle(event)
    }

    const toggleProfilePopover = (event) => {
        profilePopover.value.toggle(event)
    }

    const logout = () => {
         axios.post('logout',{})
        .then((response) => {
            toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 3000 });
            auth.clearUser()
            router.push({ path: '/' })
        })
        .catch((error) => {
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const handleNavigation = (url) => {
        router.push({path:url})
    }

</script>