<template>
     <Dialog v-model:visible="visible" modal :style="{ width: '1000px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Add Site</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <Panel header="Purokalusugan Site" class="w-full" toggleable>
                <div class="w-full flex justify-center items-center gap-4">
                    <FloatLabel class="w-full" variant="on">
                        <Select v-model="site.province" 
                            :options="locationStore.provinces" 
                            optionLabel="province_name" 
                            class="w-full" 
                            size="small" 
                            @change="locationStore.fetchMunicipalities(site.province.province_id)"
                        />
                        <label>Select Province</label>
                    </FloatLabel>
                    <FloatLabel class="w-full" variant="on">
                        <Select v-model="site.municipality" 
                            :options="locationStore.municipalities" 
                            optionLabel="municipality_name" 
                            class="w-full" 
                            size="small" 
                            @change="locationStore.fetchBarangays(site.municipality.municipality_id)"
                        />
                        <label>Select Municipality</label>
                    </FloatLabel>
                    <FloatLabel class="w-full" variant="on">
                        <Select v-model="site.barangay" 
                            :options="locationStore.barangays" 
                            optionLabel="barangay_name" 
                            class="w-full" 
                            size="small"
                        />
                        <label>Select Barangay</label>
                    </FloatLabel>
                </div>
                 <div class="w-full flex justify-center items-center gap-4 mt-4">
                    <InputText v-model="site.latitude" label="Latitude" />
                    <InputText v-model="site.longitude" label="Longitude" />
                </div>
            </Panel>
            <Panel header="PK Site Profile" class="w-full" toggleable>
                <div class="w-full flex flex-col justify-start items-start gap-4">
                    <div class="w-full flex justify-between items-center gap-4">
                        <InputNumber v-model="profile.total_purok" label="Total Number of Purok" />
                        <InputNumber v-model="profile.total_sitio" label="Total Number of Sitio" />
                    </div>
                    <div class="w-full flex justify-between items-center gap-4">
                        <InputNumber v-model="profile.total_target_purok" label="Total Target Number of Purok" />
                        <InputNumber v-model="profile.total_target_sitio" label="Total Target Number of Sitio" />
                    </div>
                    <div class="w-full flex justify-between items-center gap-4">
                        <InputNumber v-model="profile.total_target_population" label="Total Target Population" />
                    </div>
                </div>
            </Panel>
            <Panel header="PK Site Priority Health Programs" class="w-full" toggleable>
                <FloatLabel class="w-full" variant="on">
                    <MultiSelect 
                        :options="programStore.programs" 
                        optionLabel="program_name" 
                        filter 
                        placeholder="Select Programs"
                        class="w-full" 
                        size="small"
                        @change="handleProgramSelection"
                    />
                        
                    <label>Select Programs</label>
                </FloatLabel>
                <div class="w-full flex flex-col justify-start items-center gap-4 mt-4">
                    <div v-for="program in programs" :key="program.program_id" class="w-full flex justify-center items-center gap-4">
                        <InputText v-model="program.program_name" label="Program" />
                        <InputNumber v-model="program.target_baseline" label="Target Baseline" />
                        <InputNumber v-model="program.priority_order" label="Priority Order" />
                    </div>
                </div>
            </Panel>
        </div>

        <template #footer>
            <Button @click="saveSiteProfile" icon="pi pi-plus-circle" label="Save Site" severity="info"/>
        </template>
    </Dialog>
</template>

<script setup>
    import { ref } from 'vue'; 
    import { Button, Dialog, Panel, Select, FloatLabel,MultiSelect  } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import InputNumber from '@/components/inputFields/InputNumber.vue';
    import { useLocationStore } from '@/stores/location';
    import { useProgramStore } from '@/stores/program';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    
    const toast = useToast()
    const locationStore = useLocationStore()
    const programStore = useProgramStore()
    const visible = defineModel('visible', { default: false });
    const emit = defineEmits(['done'])

    const site = ref({
        province:null,
        municipality:null,
        barangay:null,
        longitude:'',
        latitude:''
    })
    const profile = ref({
        total_purok:0,
        total_sitio:0,
        total_target_purok:0,
        total_target_sitio:0,
        total_target_population:0,
    })
    const programs = ref([])

    const saveSiteProfile = () => {
        const payload = formatPayload()
        axios.post('/site/create',payload)
        .then((response)=>{
            emit('done',response.data)
            resetForm()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message , life: 3000 });
        })
        .finally(()=>{

        })
    }

    const resetForm = () => {
        site.value =  {
            province:null,
            municipality:null,
            barangay:null,
            longitude:'',
            latitude:''
        }
        profile.value =  {
            total_purok:undefined,
            total_sitio:undefined,
            target_purok:undefined,
            target_sitio:undefined,
            target_population:undefined,
        }
        programs.value =  []
    }

    const formatPayload = () => {
        const payload = {
            site:{
                barangay_id:site.value.barangay?.barangay_id,
                longitude:site.value.longitude,
                latitude:site.value.latitude
            },
            profile:{
                total_purok: profile.value.total_purok,
                total_sitio: profile.value.total_sitio,
                total_target_purok: profile.value.total_target_purok,
                total_target_sitio: profile.value.total_target_sitio,
                total_target_population: profile.value.total_target_population,
            },
            programs:programs.value.map(program => ({
                program_id: program.program_id,
                total_baseline: program.total_baseline,
                priority_order: program.priority_order
            }))
        }

        return payload;
    }

    const handleProgramSelection = (event) => {
        programs.value = event.value.map((program, index) => ({
            ...program,
            priority_order: index + 1,
            target_baseline: program.target_baseline || 0,
        }));
    }

</script>