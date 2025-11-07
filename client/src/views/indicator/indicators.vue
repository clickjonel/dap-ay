<template>
    <div class="w-full flex flex-col justify-start items-start gap-4">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
            <Button @click="createModal.show = true" label="Create" size="small"/>
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
    const createModal = ref({
        show:false,
        indicator:{
            type:'',
            name:''
        },
        createLoading:false
    })

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