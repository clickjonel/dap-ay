<template>
    <div class="w-full min-h-screen h-screen flex justify-start items-start gap-4 p-2 bg-[#F0FCFA]">

        <div class="w-[300px] h-full flex flex-col justify-between bg-[#D3DDDB] rounded-xl">
            <!-- Links -->
            <div class="w-full flex flex-col justify-start items-center p-2">
                <span class="p-2 ">Dap-ay Information System</span>
                <PanelMenu :model="accessLinks" class="w-full text-sm" 
                     :pt="{
                        panel: { 
                            class: '!bg-transparent' 
                        },
                        submenuIcon:{
                            class:'!text-[#2C2E2E]'     
                        },
                        headerContent:{
                            class:'!text-'     
                        },
                        headerIcon:{
                            class:'!text-[#2C2E2E]'     
                        },
                    }"
                />
            </div>

            <!-- Profile -->
            <div class="w-full flex flex-col justify-start items-start p-2">
                <div 
                    @click="toggleProfilePopover" 
                    class="w-full flex items-center gap-3 p-3 hover:bg-[#F0FCFA] rounded-lg cursor-pointer transition-all bg-[#5A686A] text-white hover:text-[#5A686A]"
                >
                    <i class="pi pi-user text-lg"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ auth.user?.first_name ?? 'User' }}</p>
                    </div>
                    <i class="pi pi-chevron-right text-xs"></i>
                </div>
            </div>
        </div>

        <div class="w-full min-h-full h-full flex flex-col justify-start items-start gap-2">

            <div class="w-full min-h-full h-full">
                <router-view></router-view>
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
            <span class="font-semibold uppercase">{{ auth.user.full_name }}</span>

            <div class="w-full flex flex-col justify-start items-start">
                <span class="text-sm font-medium">Teams:</span>
                <div class="w-full flex flex-col justify-start items-start gap-1">
                    <div v-for="team in auth.teams" class="w-full text-xs border-y p-2 bg-[#D3DDDB] hover:bg-sky-200 uppercase mt-1">
                        <span>{{ team.name }}</span>
                        <div class="ml-2 flex flex-col justify-start items-start gap-1">
                            <li v-for="brgy in team.barangays" class="ml-4">{{ brgy.name }}</li>
                        </div>
                    </div>
                </div>
            </div>

            <Button @click="logout" label="Logout" severity="danger" />
        </div>
    </Popover>


</template>

<script setup>
    import { ref,computed } from 'vue';
    import { Button,Popover,Card, PanelMenu } from 'primevue';
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

    const links = [
        {
            label: 'Dashboards',
            icon: 'pi pi-chart-bar',
            accessLevel:1,
            items:[
                {
                    label: 'Admin Dashboard',
                    command: () => handleNavigation('/dashboard')
                },
                {
                    label: 'Secretariat Dashboard',
                    command: () => handleNavigation('/dashboard/secretariat')
                }
            ]
        },
        {
            label: 'Program',
            icon: 'pi pi-table',
            accessLevel:1,
            items:[
                {
                    label: 'Programs',
                    command: () => handleNavigation('/programs')
                },
                {
                    label: 'Sub Programs',
                    // command: () => handleNavigation('/programs')
                }
            ]
        },
        {
            label: 'Users',
            icon: 'pi pi-users',
            command: () => handleNavigation('/users'),
            accessLevel:1,
        },
        {
            label: 'Teams',
            icon: 'pi pi-chart-bar',
            command: () => handleNavigation('/teams'),
            accessLevel:1,
        },
        {
            label: 'Indicators',
            icon: 'pi pi-wave-pulse',
            command: () => handleNavigation('/indicators'),
            accessLevel:1,
        },
        {
            label: 'Logs',
            icon: 'pi pi-server',
            command: () => handleNavigation('/server-logs'),
            accessLevel:1,
        },
        {
            label: 'Barangays',
            icon: 'pi pi-map',
            command: () => handleNavigation('/barangays'),
            accessLevel:1,
        },
        {
            label: 'Reports',
            icon: 'pi pi-file-check',
            items:[
                {
                    label: 'All Reports',
                    command: () => handleNavigation('/reports'),
                },
                {
                    label: 'Monthly User Reports',
                    command: () => handleNavigation('/report/monthly/user')
                },
                {
                    label: 'Monthly Municipal Level Report',
                    command: () => handleNavigation('/report/monthly/municipal-level-report')
                },
                {
                    label: 'Monthly Provincial Level Report',
                    command: () => handleNavigation('/report/monthly/provincial-level-report')
                },
                {
                    label: 'Quarterly Report',
                    // command: () => handleNavigation('/programs')
                },
            ]
        },
        {
            label: 'Announcements',
            icon: 'pi pi-volume-up',
            command: () => handleNavigation('/announcements'),
            accessLevel:1,
        },
    ];

    const accessLinks = computed(() => {
        const userAccessLevel = auth.user?.access_level;
        
        return links
            .filter(link => !link.accessLevel || userAccessLevel === link.accessLevel)
            .map(link => {
                // Only process items if they exist
                if (!link.items?.length) return link;
                
                const filteredItems = link.items.filter(
                    item => !item.accessLevel || userAccessLevel === item.accessLevel
                );
                
                return { ...link, items: filteredItems };
            });
    });

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
