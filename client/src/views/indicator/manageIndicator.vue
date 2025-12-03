<template>
     <Card class="w-full min-h-full" style="background-color: aliceblue;">
            <template #title>
                <span class="font-lexend text-lg uppercase underline">Manage Indicator</span>
            </template>
            <template #content>
                <div class="w-full flex flex-col justify-start items-start gap-4 p-4">

                    <!-- Indicator -->
                    <Panel header="Indicator" class="w-full">
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

                </div>
            </template>

        </Card>

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
    const indicator = ref({})

    onMounted(()=>{
        fetchIndicator()
    })

    const fetchIndicator = () => {
        axios.get('indicator/find',{
            params:{
                indicator_id:route.params.indicator_id,
                relationships:[
                    //'disaggregations'
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



</script>