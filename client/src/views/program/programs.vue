<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <InputText v-model="keyword" label="Search Keyword" width="w-[300px]" @change="fetchPrograms(current_page)"/>
            <Button @click="showCreateModal = true" label="Create Program" size="small"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-full flex flex-col justify-start items-start">
                <div class="w-full flex justify-start items-start divide-x divide-black border-b uppercase font-semibold bg-teal-100 border">
                    <span class="w-[20%] p-2">Program Name</span>
                    <span class="w-[20%] p-2">Program Code</span>
                    <span class="w-[20%] p-2">Program Status</span>
                    <span class="w-[20%] p-2">Sub Programs</span>
                    <span class="w-[20%] p-2">Actions</span>
                </div>
                <div v-for="prog in programs.data" class="w-full flex justify-start items-stretch divide-x divide-black border-b font-light border-x text-sm">
                    <span class="w-[20%] p-2">{{ prog.program_name }}</span>
                    <span class="w-[20%] p-2">{{ prog.program_code }}</span>
                    <span class="w-[20%] p-2">
                        <Tag :severity="prog.program_active ? 'success' : 'secondary'":value="prog.program_active ? 'Active' : 'Disabled'" style="font-size: 12px; font-weight: 300;"></Tag>
                    </span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start gap-2 text-xs">
                        <Tag severity="info" v-for="sub in prog.sub_programs" :value="sub.sub_program_name" style="font-size: 12px; font-weight: 300;"></Tag>
                    </span>
                    <span class="w-[20%] p-2 flex justify-start items-center gap-2">
                        <Button @click="router.push({path:`/program/update/${prog.program_id}`})" v-tooltip="'Update Program'" icon="pi pi-pen-to-square" severity="help" rounded outlined/>
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

    <CreateProgramModal v-if="showCreateModal" v-model:visible="showCreateModal" @done="handleProgramCreated"/>
        
</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, Tag } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import CreateProgramModal from '@/components/modals/CreateProgramModal.vue';
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

    const handleProgramCreated = () => {
        showCreateModal.value = false
        fetchPrograms(programs.current_page)
    }

</script>