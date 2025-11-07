<template>
    <Dialog v-model:visible="visible" modal :style="{ width: '800px' }" @hide="resetFields">
        <template #header>
            <span class="uppercase font-bold text-lg">Create Program</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <InputText v-model="program.program_name" label="Program Name"/>
            <InputText v-model="program.program_code" label="Program Code"/>
            <Select v-model="program.program_active" 
                :options="statusOptions" 
                optionLabel="name" 
                optionValue="id"
                placeholder="Select Program Status" 
                class="w-full" 
            />
        </div>

        <template #footer>
            <Button icon="pi pi-plus-circle" label="Save Program" severity="info" @click="saveProgram"/>
        </template>
    </Dialog>
</template>

<script setup>
    import { ref } from 'vue';
    import Dialog from 'primevue/dialog';
    import Button from 'primevue/button';
    import Select from 'primevue/select';
    import InputText from '@/components/inputFields/InputText.vue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';

    const visible = defineModel('visible', { default: false });
    const emit = defineEmits(['done'])
    const toast = useToast()

    const program = ref({
        program_name: '',
        program_code: '',
        program_active: null
    });

    const statusOptions = [
        { id: 1, name: 'Active' },
        { id: 0, name: 'Disabled' }
    ];

    const saveProgram = () => {
       axios.post('program/create',program.value)
       .then((response)=>{
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            emit('done')
       })
       .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Required Fields', detail: Object.values(error.response.data.errors).flat().join('\n '), life: 3000 });
       })
       .finally(()=>{

       })
    };

    const resetFields = () => {
        program.value = {
            program_name: '',
            program_code: '',
            program_active: null
        }
    }
</script>