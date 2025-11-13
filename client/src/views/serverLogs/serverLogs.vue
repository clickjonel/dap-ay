<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchServerLogs(current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm border divide-y font-lexend">
                <div class="w-full flex justify-start items-start divide-x divide-black capitalize font-semibold bg-teal-100">
                    <span class="w-[20%] p-2">Occurance Date</span>
                    <span class="w-[20%] p-2">Action By</span>
                    <span class="w-[20%] p-2">Action Done</span>
                    <span class="w-[20%] p-2">Table Name</span>
                    <span class="w-[20%] p-2">Column Id</span>
                </div>
                <div v-for="record in serverLogs.data" class="w-full flex justify-start items-stretch divide-x font-light text-xs">
                    <span class="w-[20%] p-1">{{ record.created_at }}</span>
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
    axios.get(`/server-logs/list/?page=${page}`, {
        params: {
            keyword: keyword.value
        }
    })
        .then((response) => {
            serverLogs.value = response.data
            console.log("server Logs",serverLogs.value)
        })
        .catch((error) => {
            console.log(error)
        })
        .finally(() => {

        })
}
</script>