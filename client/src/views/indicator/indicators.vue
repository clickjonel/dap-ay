<template>
    <div class="w-full min-h-full flex flex-col justify-start items-start gap-4">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchIndicators(indicators.current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
            <!-- <Button @click="router.push('/indicator/create')" label="Add Indicator" size="small"/> -->
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm border divide-y font-poppins">
                <div class="w-full flex justify-start items-start divide-x divide-black border-b uppercase font-semibold bg-teal-100">
                    <span class="w-[20%] p-2">Name</span>
                    <span class="w-[20%] p-2">Program</span>
                    <span class="w-[20%] p-2">Type</span>
                    <span class="w-[20%] p-2">Status</span>
                    <span class="w-[20%] p-2">Actions</span>
                </div>
                <div v-for="indicator in indicators.data" class="w-full flex justify-start items-stretch divide-x font-light text-sm">
                    <span class="w-[20%] p-1">{{ indicator.name }}</span>
                    <span class="w-[20%] p-1">{{ indicator.sub_program?.name ?? 'No Program Associated' }}</span>
                    <span class="w-[20%] p-1">{{ indicator.type }}</span>
                    <span class="w-[20%] p-1">{{ indicator.active ? 'Active' : 'Inactive' }}</span>
                    <span class="w-[20%] p-1 flex justify-start items-center gap-2">
                        <Button v-tooltip="'Manage Indicator'" icon="pi pi-cog" size="small" severity="secondary" rounded outlined/>
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
        fetchIndicators()
    })

    const fetchIndicators = (page=1) => {
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

</script>