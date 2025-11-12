<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchPrograms(current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
            <Button @click="router.push('/program/create')" label="Add Program" size="small"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm border divide-y font-lexend">
                <div class="w-full flex justify-start items-start divide-x divide-black capitalize font-semibold bg-teal-100">
                    <span class="w-[25%] p-2">Program Name</span>
                    <span class="w-[25%] p-2">Program Code</span>
                    <span class="w-[25%] p-2">Program Status</span>
                    <span class="w-[25%] p-2">Actions</span>
                </div>
                <div v-for="prog in programs.data" class="w-full flex justify-start items-stretch divide-x font-light text-xs border-b">
                    <span class="w-[25%] p-1">{{ prog.name }}</span>
                    <span class="w-[25%] p-1">{{ prog.code }}</span>
                    <span class="w-[25%] p-1">
                        <Tag :severity="prog.active ? 'success' : 'secondary'":value="prog.active ? 'Active' : 'Disabled'" style="font-size: 12px; font-weight: 300;"></Tag>
                    </span>
                    <span class="w-[25%] p-1 flex justify-start items-center gap-2">
                         <Button @click="router.push(`/program/update/${prog.id}`)" v-tooltip="'Manage Program'" icon="pi pi-cog" size="small" severity="secondary" rounded outlined/>
                    </span>
                </div>
            </div>

            <div class="w-full min-h-[60px] flex justify-between items-center px-4 border">
                <span class="text-md font-light">Showing rows {{ programs.from }} - {{ programs.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchPrograms(programs.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="programs.current_page === 1"/>
                    <span class="border rounded-full size-12 text-xs flex justify-center items-center font-medium">{{ programs.current_page }}</span>
                    <Button @click="fetchPrograms(programs.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="programs.next_page_url === null"/>
                </div>
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
    const programs = ref([])
    const showCreateModal = ref(false)

    onMounted(()=>{
        fetchPrograms(1)
    })

    // functions
    const fetchPrograms = (page) => {
        axios.get(`/program/list/?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            programs.value = response.data
            console.log(programs.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    // const handleProgramCreated = () => {
    //     showCreateModal.value = false
    //     fetchPrograms(programs.current_page)
    // }

</script>