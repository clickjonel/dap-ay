<template>
     <Dialog v-model:visible="visible" modal :style="{ width: '1000px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Update Site Priority Programs</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                <div class="w-full flex flex-col justify-start items-center gap-6 mt-4">
                    <div v-for="prog in model" :key="prog.program_id" class="w-full flex justify-center items-center gap-4">
                       <FloatLabel class="w-full" variant="on">
                            <Select v-model="prog.program" 
                                :options="programStore.programs" 
                                optionLabel="program_name" 
                                class="w-full" 
                            />
                            <label>Select Program</label>
                        </FloatLabel>
                        <InputNumber v-model="prog.target_baseline" label="Target Baseline" />
                        <InputNumber v-model="prog.priority_order" label="Priority Order" />
                    </div>
                </div>
        </div>

        <template #footer>
            <Button @click="save" label="Update Programs" severity="info" size="small"/>
        </template>
    </Dialog>
</template>

<script setup> 
    import { onMounted } from 'vue';
    import { Button, Dialog, Select, FloatLabel } from 'primevue';
    import InputNumber from '@/components/inputFields/InputNumber.vue';
    import InputText from '@/components/inputFields/InputText.vue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    import { useProgramStore } from '@/stores/program';
    
    const toast = useToast()
    const visible = defineModel('visible', { default: false });
    const model = defineModel({required:true,default:null})
    const emit = defineEmits(['done'])
    const programStore = useProgramStore()

    onMounted(()=>{
        if(model){
            programStore.fetchPrograms()
        }
    })

    const save= () => {
        const payload = formatPayload()
        axios.post('/site/update/priority_programs',payload)
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
        const payload = model.value.map(program => ({
            program_id: program.program.program_id,
            profile_id: program.profile_id,
            target_baseline: program.target_baseline,
            priority_order: program.priority_order,
            site_program_id: program.site_program_id
        }))
        console.log(payload)
        
        return payload
    }

</script>