<template>
     <Card class="w-full min-h-screen" style="background-color: aliceblue;">
            <template #title>
                <span class="font-lexend text-lg uppercase underline">Update Program</span>
            </template>
            <template #content>
                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">

                    <!-- program -->
                    <Panel header="PROGRAM" class="w-full">
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

                        <Button @click="updateProgram" label="Update" size="small"/>

                    </Panel>


                    <!-- sub programs -->
                    <Panel class="w-full">

                        <template #header>
                            <div class="w-full flex justify-between items-center">
                                <span class="uppercase font-semibold">Sub Programs</span>
                                <Button @click="createSubProgramModal.show = true" v-tooltip.left="'Click to add Sub Program'" icon="pi pi-plus" size="small" rounded severity="info"/>
                            </div>
                        </template>

                       <div class="w-full grid grid-cols-3 gap-4">

                            <Card v-for="sub in program.sub_programs" class="w-full" style="background-color: azure;">

                                <template #title>
                                    <div class="font-lexend text-sm capitalize underline">{{ sub.name }}</div>
                                </template>

                                <template #content>
                                    <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                                        <FloatLabel variant="on" class="w-full">
                                            <InputText v-model="sub.name" class="w-full" size="small"/>
                                            <label class="text-sm">Program Name</label>
                                        </FloatLabel>
                                        <FloatLabel variant="on" class="w-full">
                                            <InputText v-model="sub.code" class="w-full" size="small"/>
                                            <label class="text-sm">Program Code</label>
                                        </FloatLabel>
                                        <FloatLabel variant="on" class="w-full">
                                            <Select v-model="sub.active" 
                                                :options="[
                                                    {value:true,status:'Active'},
                                                    {value:false,status:'Inactive'},
                                                ]" 
                                                optionValue="value"
                                                optionLabel="status"
                                                class="w-full"
                                                size="small"
                                            />
                                            <label class="text-sm">Program Status</label>
                                        </FloatLabel>
                                    </div>
                                </template>

                                <template #footer>
                                    <Button @click="updateSubProgram(sub)" label="Update" size="small"/>
                                </template>

                            </Card>
                            
                       </div>
                    </Panel>

                    <!-- Indicators -->
                    <Panel class="w-full">

                        <template #header>
                            <div class="w-full flex justify-between items-center">
                                <span class="uppercase font-semibold">Program Indicators</span>
                                <Button v-tooltip.left="'Click to add Sub Program'" icon="pi pi-plus" size="small" rounded severity="info"/>
                            </div>
                        </template>

                       <div class="w-full grid grid-cols-3 gap-4">

                            <Card v-for="ind in program.indicators" class="w-full" style="background-color: azure;">

                                <template #title>
                                    <div class="font-lexend text-sm capitalize underline">{{ ind.name }}</div>
                                </template>

                                <template #content>
                                    <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                                        <FloatLabel variant="on" class="w-full">
                                            <InputText v-model="ind.name" class="w-full" size="small"/>
                                            <label class="text-sm">Indicator Name</label>
                                        </FloatLabel>
                                        <FloatLabel variant="on" class="w-full">
                                            <Select v-model="ind.active" 
                                                :options="[
                                                    {value:true,status:'Active'},
                                                    {value:false,status:'Inactive'},
                                                ]" 
                                                optionValue="value"
                                                optionLabel="status"
                                                class="w-full"
                                                size="small"
                                            />
                                            <label class="text-sm">Indicator Status</label>
                                        </FloatLabel>
                                        <FloatLabel variant="on" class="w-full">
                                            <Select v-model="ind.type" 
                                                :options="[
                                                    {label:'Priority'},
                                                    {label:'Target'},
                                                ]" 
                                                optionValue="label"
                                                optionLabel="label"
                                                class="w-full"
                                                size="small"
                                            />
                                            <label class="text-sm">Indicator Type</label>
                                        </FloatLabel>
                                    </div>
                                </template>

                                <template #footer>
                                    <Button @click="updateIndicator(ind)" label="Update" size="small"/>
                                </template>

                            </Card>
                            
                       </div>
                    </Panel>
                </div>
            </template>

        </Card>

        <Dialog v-model:visible="createSubProgramModal.show" modal header="Add Sub Program" :style="{ width: '600px' }">
            <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                <FloatLabel variant="on" class="w-full">
                    <InputText v-model="createSubProgramModal.subProgram.name" class="w-full"/>
                    <label class="text-sm">Sub Program Name</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputText v-model="createSubProgramModal.subProgram.code" class="w-full"/>
                    <label class="text-sm">Sub Program Code</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="createSubProgramModal.subProgram.active" 
                        :options="[
                            {value:true,status:'Active'},
                            {value:false,status:'Inactive'},
                        ]" 
                        optionValue="value"
                        optionLabel="status"
                        class="w-full"
                    />
                    <label class="text-sm">Sub Program Status</label>
                </FloatLabel>
            </div>
            <Button @click="createSubProgram" label="Create" size="small"/>
        </Dialog>

</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { 
        Panel,
        FloatLabel,
        InputText,
        Select,
        InputNumber, 
        Button ,
        Dialog,
        Card
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute } from 'vue-router';
    import { useToast } from 'primevue';

    const toast = useToast()
    const route = useRoute()
    const program = ref({})

    const createSubProgramModal = ref({
        show:false,
        subProgram:{}
    })
   

    onMounted(()=>{
        fetchProgram()
    })

    const fetchProgram = () => {
        axios.get('program/find',{
            params:{
                program_id:route.params.program_id,
                relationships:[
                    'subPrograms',
                    'indicators'
                ]
            }
        })
        .then((response)=>{
            program.value = response.data
            console.log(program.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const updateSubProgram = (subProgram) => {
        axios.post('/sub-program/update',subProgram)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchProgram()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const updateProgram = () => {
        axios.post('/program/update',{
            id:program.value.id,
            name:program.value.name,
            code:program.value.code,
            active:program.value.active,
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchProgram()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const createSubProgram = () => {
        if(!createSubProgramModal.value.subProgram.name || !createSubProgramModal.value.subProgram.code || createSubProgramModal.value.subProgram.active === undefined){
            toast.add({ severity: 'error', summary: 'Required', detail: 'All Fields are required', life: 3000 });
            return;
        }

        const payload = {
            ...createSubProgramModal.value.subProgram,
            program_id: program.value.id
        };

        axios.post('/sub-program/create',payload)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            createSubProgramModal.value.subProgram = {}
            createSubProgramModal.value.show = false
            fetchProgram()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const updateIndicator = (indicator) => {
        axios.post('/indicator/update',{
            id:indicator.id,
            name:indicator.name,
            type:indicator.type,
            active:indicator.active,
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchProgram()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

</script>