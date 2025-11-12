<template>
    <div class="w-full h-screen bg-gray-100 flex justify-center items-center">
        <Card class="w-1/3 h-1/3">
            <template #title>
                <span class="font-lexend text-lg uppercase underline">Create Program</span>
            </template>
            <template #content>
                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                    <FloatLabel variant="on" class="w-full">
                        <InputText v-model="program.name" class="w-full"/>
                        <label class="text-sm">Program Name</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-full">
                        <InputText v-model="program.code" class="w-full"/>
                        <label class="text-sm">Program Code</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-full">
                        <Select v-model="program.active" 
                            :options="[
                                {value:true,status:'Active'},
                                {value:false,status:'Inactive'},
                            ]" 
                            optionValue="value"
                            optionLabel="status"
                            class="w-full"
                        />
                        <label class="text-sm">Program Status</label>
                    </FloatLabel>
                </div>
            </template>
            <template #footer>
                 <Button @click="handleSave" label="Submit" size="small"/>
            </template>
        </Card>
    </div>    
</template>

<script setup>
    import { ref } from 'vue';
    import { Card, FloatLabel,InputText,Select,Button } from 'primevue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    import { useRouter } from 'vue-router';

    const toast = useToast();
    const router = useRouter();
    const program = ref({});

    const handleSave = () => {
        if(!program.value.name || !program.value.code || program.value.active === undefined){
            toast.add({ severity: 'error', summary: 'Required', detail: 'All Fields are required', life: 3000 });
            return;
        }

        axios.post('program/create',program.value)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Saved', detail: response.data, life: 3000 });
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Required', detail: error.response?.data?.message, life: 3000 });
            router.push('/programs')
        })
        .finally(()=>{

        })
    }

</script>