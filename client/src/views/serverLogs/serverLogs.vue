<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchServerLogs(serverLogs.current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
        </div>

       <div class="w-full h-full flex flex-col justify-between items-start gap-2 overflow-y-auto">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm">
                <div class="w-full flex justify-start items-start font-medium uppercase bg-[#D3DDDB] sticky top-0">
                    <span class="w-[20%] p-2">Occurence</span>
                    <span class="w-[20%] p-2">Action By</span>
                    <span class="w-[20%] p-2">Action Done</span>
                    <span class="w-[20%] p-2">Table Interaction</span>
                    <span class="w-[20%] p-2">Primary Key</span>
                </div>
                <div v-for="record in serverLogs.data" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <span class="w-[20%] p-1">{{ displayReadableDateTime(record.created_at)}}</span>
                    <span class="w-[20%] p-1">{{ record.user.full_name }}</span>
                    <span class="w-[20%] p-1">{{ record.action_done }}</span>
                    <span class="w-[20%] p-1">{{ record.table_name }}</span>
                    <span class="w-[20%] p-1">{{ record.column_id }}</span>
                </div>
            </div>
            <div class="w-full min-h-[50px] flex justify-between items-center px-4 border">
                <span class="text-sm font-light">Showing rows {{ serverLogs.from }} - {{ serverLogs.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchServerLogs(serverLogs.current_page - 1)" icon="pi pi-angle-left"
                        severity="info" rounded aria-label="Previous" :disabled="serverLogs.current_page === 1" />
                    <span
                        class="border rounded-full size-10 text-xs flex justify-center items-center font-medium">{{
                        serverLogs.current_page }}</span>
                    <Button @click="fetchServerLogs(serverLogs.current_page + 1)" icon="pi pi-angle-right"
                        severity="info" rounded aria-label="Next" :disabled="serverLogs.next_page_url === null" />
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Button, Tag, FloatLabel,InputText } from 'primevue';
import axios from '@/utils/axios';

const keyword = ref('')
const serverLogs = ref([])

onMounted(() => {
    fetchServerLogs(1);
})

const fetchServerLogs = (page) => {
    axios.get(`/server-log/list/?page=${page}`, {
        params: {
            keyword: keyword.value
        }
    })
        .then((response) => {
            serverLogs.value = response.data   
            console.log('server logs',serverLogs.value)         
        })
        .catch((error) => {
            console.log(error)
        })
        .finally(() => {

        })
}

function displayReadableDateTime(dateTime){
    const result = new Date(dateTime);
    return result.toLocaleString('en-PH',{
        year:'numeric',month:'long',day:'numeric',
        hour: '2-digit',minute:'2-digit',second:'2-digit',
        timeZone:'Asia/Manila'
    });
}
</script>