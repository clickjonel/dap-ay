<template>
    <Card class="w-full min-h-full h-full overflow-y-scroll" style="background-color: aliceblue;">
            <template #title>
                <span class="font-lexend text-lg uppercase underline">Manage Indicator</span>
            </template>
            <template #content>
                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">

                    <!-- Indicator -->
                    <Panel class="w-full">
                        <template #header>
                            <span class="font-lexend font-semibold uppercase">Indicator</span>
                        </template>

                        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                            <FloatLabel variant="on" class="w-full">
                                <InputText v-model="indicator.name" class="w-full"/>
                                <label class="text-sm">Indicator Name</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <Select v-model="indicator.type" 
                                    :options="[
                                        {type:'Priority'},
                                        {type:'Target'},
                                    ]" 
                                    optionValue="type"
                                    optionLabel="type"
                                    class="w-full"
                                />
                                <label class="text-sm">Indicator Type</label>
                            </FloatLabel>

                            <FloatLabel variant="on" class="w-full">
                                <Select v-model="indicator.active" 
                                    :options="[
                                        {label:'Active',active:true},
                                        {label:'Disabled',active:false},
                                    ]" 
                                    optionValue="active"
                                    optionLabel="label"
                                    class="w-full"
                                />
                                <label class="text-sm">Indicator Status</label>
                            </FloatLabel>

                        </div>

                        <Button @click="updateIndicator" label="Update" size="small"/>

                    </Panel>

                     <!-- Disagregations -->
                    <Panel header="Disagregations" class="w-full">
                        <template #header>
                            <div class="w-full flex justify-between items-center">
                                <span class="font-lexend font-semibold uppercase">Disagregations</span>
                                <Button @click="addModal.show = true" label="Add Disaggregation" size="small" severity="info"/>
                            </div>
                        </template>

                        <div class="w-full grid grid-cols-3 gap-4 p-4">
                           <Panel v-for="dis in indicator.disaggregations" :header="dis.name" class="w-full">

                                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                                    <FloatLabel variant="on" class="w-full">
                                        <InputText v-model="dis.name" class="w-full"/>
                                        <label class="text-sm">Indicator Name</label>
                                    </FloatLabel>

                                    <FloatLabel variant="on" class="w-full">
                                        <Select v-model="dis.active" 
                                            :options="[
                                                {label:'Active',active:true},
                                                {label:'Disabled',active:false},
                                            ]" 
                                            optionValue="active"
                                            optionLabel="label"
                                            class="w-full"
                                        />
                                        <label class="text-sm">Status</label>
                                    </FloatLabel>

                                    <FloatLabel variant="on" class="w-full">
                                        <Select v-model="dis.totalable" 
                                            :options="[
                                                {label:'Yes',totalable:true},
                                                {label:'No',totalable:false},
                                            ]" 
                                            optionValue="totalable"
                                            optionLabel="label"
                                            class="w-full"
                                        />
                                        <label class="text-sm">Include in total?</label>
                                    </FloatLabel>
                                </div>

                                <template #footer>
                                    <div class="w-full flex justify-start items-start gap-4">
                                        <Button @click="updateDisaggregation(dis)" label="Update" size="small" severity="info"/>
                                    </div>
                                </template>
                            </Panel>
                        </div>

                    </Panel>

                </div>
            </template>

    </Card>

    <Dialog v-model:visible="addModal.show" modal header="Add Disaggregation" :style="{ width: '600px' }">
        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <FloatLabel variant="on" class="w-full">
                <InputText v-model="addModal.disaggregation.name" class="w-full"/>
                <label class="text-sm">Indicator Name</label>
            </FloatLabel>

            <FloatLabel variant="on" class="w-full">
                <Select v-model="addModal.disaggregation.active" 
                    :options="[
                        {label:'Active',active:true},
                        {label:'Disabled',active:false},
                    ]" 
                    optionValue="active"
                    optionLabel="label"
                    class="w-full"
                />
                <label class="text-sm">Status</label>
            </FloatLabel>

            <FloatLabel variant="on" class="w-full">
                <Select v-model="addModal.disaggregation.totalable" 
                    :options="[
                        {label:'Yes',totalable:true},
                        {label:'No',totalable:false},
                    ]" 
                    optionValue="totalable"
                    optionLabel="label"
                    class="w-full"
                />
                <label class="text-sm">Include in total?</label>
            </FloatLabel>
        </div>

        <Button @click="createDisaggregation" label="Save Disaggegration" size="small"/>

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
    const indicator = ref({})

    const addModal = ref({
        show:false,
        disaggregation:{
            name:'',
            totalable:null,
            active:null,
            indicator_id:indicator.value.id
        }
    })

    onMounted(()=>{
        fetchIndicator()
    })

    const fetchIndicator = () => {
        axios.get('indicator/find',{
            params:{
                indicator_id:route.params.indicator_id,
                relationships:[
                    'disaggregations'
                ]
            }
        })
        .then((response)=>{
            indicator.value = response.data
            console.log(indicator.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const updateIndicator = () => {
        axios.post('indicator/update',{
            id: indicator.value.id ,
            name: indicator.value.name,
            active: indicator.value.active,
            type: indicator.value.type,
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchIndicator()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const updateDisaggregation = (disaggregation) => {
        axios.post('indicator/disaggregation/update',disaggregation)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            fetchIndicator()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const createDisaggregation = () => {
        axios.post('indicator/disaggregation/create', {
            ...addModal.value.disaggregation,
            indicator_id: indicator.value.id
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            addModal.value.show = false
            addModal.value.disaggregation = {
                name:'',
                totalable:null,
                active:null,
            }
            fetchIndicator()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
            console.log(error)
        })
        .finally(()=>{

        })
    }


</script>