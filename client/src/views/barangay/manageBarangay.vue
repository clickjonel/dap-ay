<template>
     <Card class="w-full min-h-screen" style="background-color: aliceblue;">
            <template #title>
                <span class="font-lexend text-lg uppercase underline">Manage Barangay</span>
            </template>
            <template #content>
                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">

                    <!-- barangay -->
                    <Panel header="Barangay" class="w-full">
                        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                            <FloatLabel variant="on" class="w-full">
                                <InputText v-model="barangay.name" class="w-full"/>
                                <label class="text-sm">Barangay Name</label>
                            </FloatLabel>

                             <FloatLabel variant="on" class="w-full">
                                <InputText v-model="barangay.latitude" class="w-full"/>
                                <label class="text-sm">Barangay Latitude</label>
                            </FloatLabel>

                             <FloatLabel variant="on" class="w-full">
                                <InputText v-model="barangay.longitude" class="w-full"/>
                                <label class="text-sm">Barangay Longitude</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <Select v-model="barangay.status" 
                                    :options="[
                                        {value:true,status:'Implementing PK'},
                                        {value:false,status:'Monitoring PK'},
                                    ]" 
                                    optionValue="status"
                                    optionLabel="status"
                                    class="w-full"
                                />
                                <label class="text-sm">PK Status</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputNumber v-model="barangay.total_purok" class="w-full"/>
                                <label class="text-sm">Total Purok/Sitio</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputNumber v-model="barangay.target_purok" class="w-full"/>
                                <label class="text-sm">Target Purok/Sitio</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <InputNumber v-model="barangay.target_population" class="w-full"/>
                                <label class="text-sm">Target Population</label>
                            </FloatLabel>

                        </div>

                        <Button @click="updateBarangay" label="Update" size="small"/>

                    </Panel>


                    <!-- Priority Programs -->
                    <Panel class="w-full">

                        <template #header>
                            <div class="w-full flex justify-between items-center">
                                <span class="uppercase font-semibold">Priority Programs</span>
                                <Button @click="addBarangayPriorityProgramModal.show = true" v-tooltip.left="'Click to add Priority Program'" icon="pi pi-plus" size="small" rounded severity="info"/>
                            </div>
                        </template>

                       <div class="w-full grid grid-cols-3 gap-4">
                            <Card v-for="sub in barangay.sub_programs" class="w-full" style="background-color: azure;">

                                <template #title>
                                    <div class="font-lexend text-sm capitalize underline">{{ sub.name }}</div>
                                </template>

                                <template #content>
                                    <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                                        <FloatLabel variant="on" class="w-full">
                                            <InputNumber v-model="sub.pivot.baseline" class="w-full" size="small"/>
                                            <label class="text-sm">Baseline</label>
                                        </FloatLabel>
                                       
                                        <FloatLabel variant="on" class="w-full">
                                            <Select v-model="sub.pivot.order" 
                                                :options="Array.from({ length: barangay.sub_programs.length }, (_, i) => ({ value: i + 1 }))" 
                                                optionValue="value"
                                                optionLabel="value"
                                                class="w-full"
                                                size="small"
                                            />
                                            <label class="text-sm">Order</label>
                                        </FloatLabel>
                                    </div>
                                </template>

                                <template #footer>
                                    <Button @click="updatePriorityProgram(sub.pivot)" label="Update" size="small"/>
                                </template>

                            </Card>
                       </div>
                    </Panel>

                </div>
            </template>

        </Card>

        <Dialog v-model:visible="addBarangayPriorityProgramModal.show" modal header="Select Priority Program" :style="{ width: '500px' }">
           <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                <Select v-model="addBarangayPriorityProgramModal.barangayPriorityProgram" :options="subProgramSelection" optionLabel="name" optionValue="id" placeholder="Select a Priority Program" class="w-full" size="small"/>
                <Button @click="addBarangayPriorityProgram" label="Save Prioty Program"  size="small"/>
           </div>
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
    import { useSubProgramStore } from '@/stores/subPrograms';

    const toast = useToast()
    const route = useRoute()
    const barangay = ref({})
    const subProgramStore = useSubProgramStore()
    const subProgramSelection = ref([])

    const addBarangayPriorityProgramModal = ref({
        show:false,
        barangayPriorityProgram:null
    })
   

    onMounted(()=>{
        fetchBarangay()
    })

    const fetchBarangay = () => {
        axios.get('barangay/find',{
            params:{
                barangay_id:route.params.barangay_id,
                relationships:[
                    'subPrograms'
                    // 'indicators'
                ]
            }
        })
        .then((response)=>{
            barangay.value = response.data
            subProgramSelection.value = subProgramStore.subPrograms.filter(
                sp => !barangay.value.sub_programs.some(assigned => assigned.id === sp.id)
            );

            console.log(subProgramSelection.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const updateBarangay = () => {
        axios.post('barangay/update',{
            id:barangay.value.id,
            name: barangay.value.name,
            status: barangay.value.status,
            latitude: barangay.value.latitude,
            longitude: barangay.value.longitude,
            total_purok: barangay.value.total_purok,
            target_purok: barangay.value.target_purok,
            target_population: barangay.value.target_population
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchBarangay()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const updatePriorityProgram = (priorityProgram) => {
        axios.post('barangay-priority-program/update',priorityProgram)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchBarangay()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const addBarangayPriorityProgram = () => {

        if(addBarangayPriorityProgramModal.value.barangayPriorityProgram === null){
            toast.add({ severity: 'error', summary: 'Error', detail: 'Select Program First', life: 3000 });
            return;
        }

        axios.post('barangay-priority-program/create',{
           sub_program_id: addBarangayPriorityProgramModal.value.barangayPriorityProgram,
           baseline:null,
           order:barangay.value.sub_programs.length + 1,
           barangay_id:barangay.value.id
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            addBarangayPriorityProgramModal.value.show = false
            addBarangayPriorityProgramModal.value.barangayPriorityProgram = null
            fetchBarangay()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

</script>