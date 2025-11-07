<template>
     <Dialog v-model:visible="visible" modal :style="{ width: '1000px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Update Site</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <Panel header="Purokalusugan Site" class="w-full">
                <div class="w-full flex flex-col justify-center items-center gap-6">
                    <FloatLabel class="w-full" variant="on">
                        <Select v-model="model.province" 
                            :options="locationStore.provinces" 
                            optionLabel="province_name" 
                            class="w-full" 
                            @change="locationStore.fetchMunicipalities(model.province.province_id)"
                        />
                        <label>Select Province</label>
                    </FloatLabel>
                    <FloatLabel class="w-full" variant="on">
                        <Select v-model="model.municipality" 
                            :options="locationStore.municipalities" 
                            optionLabel="municipality_name" 
                            class="w-full" 
                            @change="locationStore.fetchBarangays(model.municipality.municipality_id)"
                        />
                        <label>Select Municipality</label>
                    </FloatLabel>
                    <FloatLabel class="w-full" variant="on">
                        <Select v-model="model.barangay" 
                            :options="locationStore.barangays" 
                            optionLabel="barangay_name" 
                            class="w-full" 
                            :dataKey="barangay => barangay.barangay_id"
                        />
                        <label>Select Barangay</label>
                    </FloatLabel>
                </div>
                 <div class="w-full flex justify-center items-center gap-4 mt-4">
                    <InputText v-model="model.latitude" label="Latitude"/>
                    <InputText v-model="model.longitude" label="Longitude" />
                </div>
            </Panel>
        </div>

        <template #footer>
            <Button @click="save" label="Update Site" severity="info" size="small"/>
        </template>
    </Dialog>
</template>

<script setup>
    import { onMounted, ref } from 'vue'; 
    import { Button, Dialog, Panel, Select, FloatLabel } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import { useLocationStore } from '@/stores/location';
    import { useProgramStore } from '@/stores/program';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    
    const toast = useToast()
    const locationStore = useLocationStore()
    const visible = defineModel('visible', { default: false });
    const model = defineModel({required:true,default:null})
    const emit = defineEmits(['done'])

    onMounted(async()=>{
        if(model){
            locationStore.fetchMunicipalities(model.value.province.province_id)
            locationStore.fetchBarangays(model.value.municipality.municipality_id)
            console.log(model.value)
        }
    })

    const save= () => {
        formatPayload()
        const payload = formatPayload()
        axios.post(`/site/update/${model.value.site_id}`,payload)
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
            barangay_id: model.value.barangay.barangay_id,
            latitude: model.value.latitude,
            longitude: model.value.longitude
        }
        
        return payload
    }

</script>