<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-4">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
            <Button @click="createModal.show = true" label="Create" size="small"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 text-sm">
            <div class="w-full h-full flex flex-col justify-start items-start">
                <div class="w-full flex justify-start items-start divide-x divide-black border-b uppercase font-semibold bg-teal-100 border">
                    <span class="w-[50%] p-2">Name</span>
                    <span class="w-[30%] p-2">Type</span>
                    <span class="w-[20%] p-2">Actions</span>
                </div>
                <div v-for="indicator in indicators.data" class="w-full flex justify-start items-stretch divide-x divide-black border-b font-light border-x text-sm">
                    <span class="w-[50%] p-1">{{ indicator.name }}</span>
                    <span class="w-[30%] p-1">{{ indicator.type }}</span>
                    <span class="w-[20%] p-1 flex justify-start items-center gap-2">
                        <!-- <Button @click="router.push({path:`/program/update/${prog.program_id}`})" v-tooltip="'Update Program'" icon="pi pi-pen-to-square" severity="help" outlined/> -->
                    </span>
                </div>
            </div>

            <div class="w-full min-h-[60px] flex justify-between items-center px-4 border">
                <span class="text-md font-light">Showing rows {{ indicators.from }} - {{ indicators.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchIndicators(indicators.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="indicators.current_page === 1"/>
                    <span class="border rounded-full size-12 text-xs flex justify-center items-center font-medium">{{ indicators.current_page }}</span>
                    <Button @click="fetchIndicators(indicators.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="indicators.next_page_url === null"/>
                </div>
            </div>

        </div>

    </div>

    <Dialog v-model:visible="createModal.show" modal header="Edit Profile" :style="{ width: '25rem' }">
       <template #header>
            <span class="font-lexend uppercase font-bold">Create Indicator</span>
       </template>
       
       <div class="w-full flex flex-col justify-start items-start gap-4 p-2">
            <FloatLabel variant="on" class="w-full">
                <InputText v-model="createModal.indicator.name" class="w-full"/>
                <label class="text-sm">Indicator Name</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <Select v-model="createModal.indicator.type" 
                    :options="[
                        {name:'Target'},
                        {name:'Priority'}
                    ]" 
                    optionLabel="name"
                    optionValue="name"
                    class="w-full" 
                />
                <label class="text-sm">Indicator Type</label>
            </FloatLabel>
       </div>

       <template #footer>
             <Button @click="handleCreateIndicator" label="Create" size="small" severity="info" :loading="createModal.createLoading"/>
       </template>
    </Dialog>

</template>

<script setup>
    import { onMounted,ref } from 'vue';
    import { FloatLabel,InputText,Button,Dialog,Select } from 'primevue'
    import { useToast } from 'primevue';
    import axios from '@/utils/axios.js'

    const toast = useToast()
    const indicators = ref([])
    const keyword = ref('')
    const createModal = ref({
        show:false,
        indicator:{
            type:'',
            name:''
        },
        createLoading:false
    })

    onMounted(()=>{
        fetchIndicators(1)
    })

    const fetchIndicators = (page) => {
        axios.get(`/indicator/list/?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            indicators.value = response.data
            console.log(indicators.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const handleCreateIndicator = () => {
        createModal.value.createLoading = true
        if(createModal.value.indicator.name === '' || createModal.value.indicator.type === ''){
            toast.add({ severity: 'error', summary: 'Required', detail: 'All Fields are required, you should fill all fields first before submitting.', life: 3000 });
            createModal.value.createLoading = false
            return
        }

        axios.post('/indicator/create',createModal.value.indicator)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            createModal.value.indicator = {}
            createModal.value.show = false
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Error', detail: 'Something Went Wrong Please Try Again.', life: 3000 });
        })
        .finally(()=>{
            createModal.value.createLoading = false
        })
    }

</script>