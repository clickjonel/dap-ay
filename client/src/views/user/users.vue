<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <InputText v-model="keyword" label="Search Keyword" width="w-[300px]" @change="fetchUsers(users.current_page)"/>
            <Button @click="showCreateModal = true" label="Create Program" size="small"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 text-sm">
            <div class="w-full h-full flex flex-col justify-start items-start">
                <div class="w-full flex justify-start items-start divide-x divide-black border-b uppercase font-semibold bg-teal-100 border">
                    <span class="w-[25%] p-2">Name</span>
                    <span class="w-[25%] p-2">Nickname</span>
                    <span class="w-[25%] p-2">Status</span>
                    <span class="w-[25%] p-2">Actions</span>
                </div>
                <div v-for="user in users.data" class="w-full flex justify-start items-stretch divide-x divide-black border-b font-light border-x text-sm">
                    <span class="w-[25%] p-1">{{ user.full_name }}</span>
                    <span class="w-[25%] p-1">{{ user.nickname }}</span>
                    <span class="w-[25%] p-1">{{ user.account_status }}</span>
                    <span class="w-[20%] p-1 flex justify-start items-center gap-2">
                        <!-- <Button @click="router.push({path:`/program/update/${prog.program_id}`})" v-tooltip="'Update Program'" icon="pi pi-pen-to-square" severity="help" outlined/> -->
                    </span>
                </div>
            </div>

            <div class="w-full min-h-[60px] flex justify-between items-center px-4 border">
                <span class="text-md font-light">Showing rows {{ users.from }} - {{ users.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchUsers(users.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="users.current_page === 1"/>
                    <span class="border rounded-full size-12 text-xs flex justify-center items-center font-medium">{{ users.current_page }}</span>
                    <Button @click="fetchUsers(users.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="users.next_page_url === null"/>
                </div>
            </div>

        </div>

    </div>
        
</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, Tag } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import axios from '@/utils/axios';
    import { useRouter } from 'vue-router';

    const router = useRouter()
    const keyword = ref('')
    const users = ref([])

    onMounted(()=>{
        fetchUsers(1)
    })

    const fetchUsers = (page) => {
        axios.get(`/user/list/?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            users.value = response.data
            console.log(users.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }


</script>