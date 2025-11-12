<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">
        <div class="w-full flex justify-between items-center p-2">
            <InputText v-model="keyword" label="Search Keyword" width="w-[300px]" />
        </div>
        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div
                class="w-full flex justify-start items-start divide-x divide-black border-b uppercase font-semibold bg-teal-100 border">
                <span class="w-[20%] p-2">Occurance Date</span>
                <span class="w-[20%] p-2">Action By</span>
                <span class="w-[20%] p-2">Action Done</span>
                <span class="w-[20%] p-2">Table Name</span>
                <span class="w-[20%] p-2">Column Id</span>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/utils/axios';
import InputText from '@/components/inputFields/InputText.vue';
import { useRouter } from 'vue-router';
const keyword = ref('')
const fetchServerLogs = (page) => {
    axios.get(`/serverLogs/list/?page=${page}`, {
        params: {
            keyword: keyword.value
        }
    })
        .then((response) => {
            records.value = response.data
            console.log(records.value)
        })
        .catch((error) => {
            console.log(error)
        })
        .finally(() => {

        })
}
</script>