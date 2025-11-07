<template>
     <Dialog v-model:visible="visible" modal :style="{ width: '1000px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Update Site Profile</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <div class="w-full flex flex-col justify-start items-start gap-6">
                <InputNumber v-model="model.total_purok" label="Total Number of Purok" />
                <InputNumber v-model="model.total_sitio" label="Total Number of Sitio" />
                <InputNumber v-model="model.total_target_purok" label="Total Target Number of Purok" />
                <InputNumber v-model="model.total_target_sitio" label="Total Target Number of Sitio" />
                <InputNumber v-model="model.total_target_population" label="Total Target Population" />
            </div>
        </div>

        <template #footer>
            <Button @click="save" label="Update Profile" severity="info" size="small"/>
        </template>
    </Dialog>
</template>

<script setup> 
    import { Button, Dialog } from 'primevue';
    import InputNumber from '@/components/inputFields/InputNumber.vue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    
    const toast = useToast()
    const visible = defineModel('visible', { default: false });
    const model = defineModel({required:true,default:null})
    const emit = defineEmits(['done'])

    const save= () => {
        formatPayload()
        const payload = formatPayload()
        axios.post(`/site/update/profile/${model.value.site_profile_id}`,payload)
        .then((response)=>{
            emit('done',response.data)
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message , life: 3000 });
        })
        .finally(()=>{

        })
    }

    const formatPayload = () => {
        const payload = {
            total_purok: model.value.total_purok,
            total_sitio: model.value.total_sitio,
            total_target_purok: model.value.total_target_purok,
            total_target_sitio: model.value.total_target_sitio,
            total_target_population: model.value.total_target_population
        }
        
        return payload
    }

</script>