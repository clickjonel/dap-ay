<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
             <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchTeams(teams.current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
            <Button @click="router.push('/team/create')" label="Add Team" class="!bg-[#5A686A] !border-none"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 overflow-y-auto">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm">
                <div class="w-full flex justify-start items-start font-medium uppercase bg-[#D3DDDB] sticky top-0">
                    <span class="w-[80%] p-2">Team Name</span>
                    <span class="w-[20%] p-2">Actions</span>
                </div>
                <div v-for="team in teams.data" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <span class="w-[80%] p-2">{{ team.name }}</span>
                    <span class="w-[20%] p-2 flex justify-start items-center gap-2">
                        <Button @click="router.push({path:`/team/update/${team.id}`})" v-tooltip="'Update Team'" icon="pi pi-cog" severity="secondary" rounded outlined class="!size-8"/>
                    </span>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-between items-center">
            <span class="text-md font-base">Showing rows {{ teams.from }} - {{ teams.to }}</span>
            <div class="flex justify-between items-center gap-4">
                <Button @click="fetchTeams(teams.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="teams.current_page === 1"/>
                <span class="border rounded-full size-10 text-xs flex justify-center items-center font-medium">{{ teams.current_page }}</span>
                <Button @click="fetchTeams(teams.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="teams.next_page_url === null"/>
            </div>
        </div>

    </div>

</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, Tag, FloatLabel,InputText } from 'primevue';
    import axios from '@/utils/axios';
    import { useRouter } from 'vue-router';

    const router = useRouter()
    const keyword = ref('')
    const teams = ref([])

    onMounted(()=>{
        fetchTeams(1)
    })

    // functions
    const fetchTeams = (page) => {
        axios.get(`/team/list/?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            teams.value = response.data
            console.log(teams.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }


</script>