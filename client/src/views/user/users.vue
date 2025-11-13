<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchUsers(users.current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 text-sm">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm">
                <div class="w-full flex justify-start items-start font-medium uppercase bg-[#D3DDDB]">
                    <span class="w-[25%] p-2">Name</span>
                    <span class="w-[25%] p-2">Nickname</span>
                    <span class="w-[25%] p-2">Status</span>
                    <span class="w-[25%] p-2">Actions</span>
                </div>
                <div v-for="user in users.data" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <span class="w-[25%] p-2">{{ user.full_name }}</span>
                    <span class="w-[25%] p-2">{{ user.nickname }}</span>
                    <span class="w-[25%] p-2">{{ user.account_status }}</span>
                    <span class="w-[20%] p-2 flex justify-start items-center gap-2">
                        <!-- <Button @click="router.push({path:`/program/update/${prog.program_id}`})" v-tooltip="'Update Program'" icon="pi pi-pen-to-square" severity="help" outlined/> -->
                    </span>
                </div>
            </div>

            <div class="w-full min-h-[60px] flex justify-between items-center">
                <span class="text-md text-base">Showing rows {{ users.from }} - {{ users.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchUsers(users.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="users.current_page === 1"/>
                    <span class="border rounded-full size-10 text-xs flex justify-center items-center font-medium">{{ users.current_page }}</span>
                    <Button @click="fetchUsers(users.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="users.next_page_url === null"/>
                </div>
            </div>

        </div>

    </div>
        
</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, FloatLabel, InputText } from 'primevue';
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