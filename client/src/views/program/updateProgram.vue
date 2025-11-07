<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Update Program</span>

        <!-- program -->
        <Panel header="Program" class="w-full" toggleable>
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <FloatLabel variant="on" class="w-full">
                    <InputText class="w-full" v-model="program.program_name" size="small"/>
                    <label>Program Name</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputText class="w-full" v-model="program.program_code" size="small"/>
                    <label>Program Code</label>
                </FloatLabel>
                <FloatLabel class="w-full" variant="on">
                    <Select v-model="program.program_active" 
                        :options="statusOptions" 
                        optionLabel="name" 
                        option-value="id"
                        class="w-full" 
                        size="small"
                    />
                    <label>Select Program Status</label>
                </FloatLabel>
                <Button label="Save Updates to Program" size="small" class="shadow-slate-400 shadow-sm" @click="updateProgram"/>
           </div>
        </Panel>

        <!-- Sub Programs -->
         <Panel header="Sub Programs" class="w-full" toggleable>
           <div class="w-full grid grid-cols-4 gap-4">
                <Panel v-for="sub in program.sub_programs" :header="sub.sub_program_name" class="py-4 uppercas">
                   <div class="w-full flex flex-col justify-start items-start gap-6 p-4">
                        <FloatLabel variant="on" class="w-full">
                            <InputText class="w-full" v-model="sub.sub_program_code" size="small"/>
                            <label>Sub Program Code</label>
                        </FloatLabel>
                        <FloatLabel class="w-full" variant="on">
                            <Select v-model="sub.sub_program_active" 
                                :options="statusOptions" 
                                optionLabel="name" 
                                option-value="id"
                                class="w-full" 
                                size="small"
                            />
                            <label>Select Sub Program Status</label>
                        </FloatLabel>

                       <div class="w-full flex justify-start items-start gap-2">
                            <Button label="Update Sub Program" size="small" class="shadow-slate-400 shadow-sm" @click="updateSubProgram(sub)"/>
                       </div>

                   </div>
                </Panel>
           </div>

            <Button 
                label="Add Sub Program" 
                size="small" 
                class="shadow-slate-400 shadow-sm mt-4" 
                severity="info"
                @click="toggleCreateSubProgramPopover"
            />

        </Panel>

        <!-- indicators -->
        <Panel header="Program Indicators" class="w-full" toggleable>
          <div class="w-full grid grid-cols-3 gap-4">
                <Panel v-for="ind in program.indicators" :header="ind.indicator_name" class="py-4 uppercas">
                   <div class="w-full flex flex-col justify-start items-start gap-6 p-4">
                        <FloatLabel class="w-full" variant="on">
                            <Select v-model="ind.sub_program_id" 
                                :options="program.sub_programs" 
                                optionLabel="sub_program_name" 
                                option-value="sub_program_id"
                                class="w-full" 
                                size="small"
                            />
                            <label>Select Sub Program</label>
                        </FloatLabel>

                        <FloatLabel variant="on" class="w-full">
                            <InputText class="w-full" v-model="ind.indicator_name" size="small"/>
                            <label>Indicator Code</label>
                        </FloatLabel>

                        <FloatLabel class="w-full" variant="on">
                            <Select v-model="ind.indicator_active" 
                                :options="statusOptions" 
                                optionLabel="name" 
                                option-value="id"
                                class="w-full" 
                                size="small"
                            />
                            <label>Select Indicator Status</label>
                        </FloatLabel>

                       <div class="w-full flex justify-start items-start gap-2">
                            <Button @click="updateIndicator(ind)" label="Update Indicator" size="small" class="shadow-slate-400 shadow-sm"/>
                       </div>

                   </div>
                </Panel>
           </div>

            <Button 
                label="Add Indicator" 
                size="small" 
                class="shadow-slate-400 shadow-sm mt-4" 
                severity="info"
                @click="toggleCreateIndicatorPopover"
            />
        </Panel>
    </div>

    <Popover ref="createSubProgramPopover">
        <div class="w-[500px] flex flex-col justify-start items-start gap-4 p-4">
             <FloatLabel variant="on" class="w-full">
                <InputText class="w-full" v-model="subProgram.sub_program_name" size="small"/>
                <label>Sub Program Name</label>
            </FloatLabel>
             <FloatLabel variant="on" class="w-full">
                <InputText class="w-full" v-model="subProgram.sub_program_code" size="small"/>
                <label>Sub Program Code</label>
            </FloatLabel>
            <FloatLabel class="w-full" variant="on">
                <Select v-model="subProgram.sub_program_active" 
                    :options="statusOptions" 
                    optionLabel="name" 
                    option-value="id"
                    class="w-full" 
                    size="small"
                />
                <label>Select Sub Program Status</label>
            </FloatLabel>

            <Button 
                label="Create Sub Program" 
                size="small" 
                class="shadow-slate-400 shadow-sm mt-4" 
                severity="info"
                @click="createSubProgram"
            />
        </div>
    </Popover>

    <Popover ref="createIndicatorPopover">
        <div class="w-[500px] flex flex-col justify-start items-start gap-4 p-4">
             <FloatLabel variant="on" class="w-full">
                <InputText class="w-full" v-model="indicator.indicator_name" size="small"/>
                <label>Indicator Name</label>
            </FloatLabel>
             <FloatLabel variant="on" class="w-full">
                <InputText class="w-full" v-model="indicator.indicator_code" size="small"/>
                <label>Indicator Code</label>
            </FloatLabel>
            <FloatLabel class="w-full" variant="on">
                <Select v-model="indicator.indicator_active" 
                    :options="statusOptions" 
                    optionLabel="name" 
                    option-value="id"
                    class="w-full" 
                    size="small"
                />
                <label>Select Indicator Status</label>
            </FloatLabel>
             <FloatLabel class="w-full" variant="on">
                <Select v-model="indicator.sub_program_id" 
                    :options="program.sub_programs" 
                    optionLabel="sub_program_name" 
                    option-value="sub_program_id"
                    class="w-full" 
                    size="small"
                />
                <label>Select Sub Program</label>
            </FloatLabel>

            <Button 
                label="Create Sub Program" 
                size="small" 
                class="shadow-slate-400 shadow-sm mt-4" 
                severity="info"
                @click="createIndicator"
            />
        </div>
    </Popover>

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
        Popover
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute } from 'vue-router';
    import { useToast } from 'primevue';

    const toast = useToast()
    const route = useRoute()
    const program = ref({})

    const statusOptions = [
        { id: 1, name: 'Active' },
        { id: 0, name: 'Disabled' }
    ];

    const createSubProgramPopover = ref()
    const createIndicatorPopover = ref()
    const subProgram = ref({
        sub_program_name:'',
        sub_program_code:'',
        sub_program_active:undefined,
        program_id:route.params.program_id
    })

    const indicator = ref({
        indicator_name:'',
        indicator_code:'',
        indicator_active:undefined,
        sub_program_id:undefined
    })

    onMounted(async()=>{
        await fetchProgram()
    })

    const fetchProgram = async () => {
        try{
            const response = await axios.get('/program/find', {
                params:{
                    program_id:route.params.program_id,
                    relationships:[
                        'subPrograms',
                        'indicators'
                    ]
                }
            })
            program.value = response.data
            console.log(program.value)
        }
        catch(error){
            toast.add({ severity: 'error', summary: 'Action Failed', detail: 'Failed to fetch program, contact system administrator for troubleshooting.', life: 3000 });
        }
    }

    const updateSubProgram = (program) => {
        axios.post('/program/sub-program/update',program)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Action Successful', detail: response.data, life: 3000 });
            fetchProgram()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Action Failed', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{
            
        })
    }

    const toggleCreateSubProgramPopover = (event) => {
        createSubProgramPopover.value.toggle(event)
    }

    const toggleCreateIndicatorPopover = (event) => {
        createIndicatorPopover.value.toggle(event)
    }

    const createSubProgram = () => {
        if(
            subProgram.value.sub_program_name !== '' || 
            subProgram.value.sub_program_code !== '' || 
            subProgram.value.sub_program_active !== undefined
        ){
            axios.post('/program/sub-program/create',subProgram.value)
            .then((response)=>{
                toast.add({ severity: 'success', summary: 'Action Successful', detail: response.data, life: 3000 });
                createSubProgramPopover.value.hide()
                fetchProgram()
                 subProgram.value = {
                    sub_program_name:'',
                    sub_program_code:'',
                    sub_program_active:undefined,
                    program_id:route.params.program_id
                }
            })
            .catch((error)=>{
                toast.add({ severity: 'error', summary: 'Action Failed', detail: error.response?.data?.message, life: 3000 });
            })
            .finally(()=>{
                
            })
        }
        else{
            toast.add({ severity: 'error', summary: 'Failed', detail: 'Failed to create sub program, all fields are needed to be filled.', life: 3000 });
        }
    }

        const createIndicator = () => {
        if(
            indicator.value.indicator_name !== '' || 
            indicator.value.indicator_code !== '' || 
            indicator.value.indicator_active !==  undefined ||
            indicator.value.sub_program_id !==  undefined
        ){
            axios.post('/program/indicator/create',indicator.value)
            .then((response)=>{
                toast.add({ severity: 'success', summary: 'Action Successful', detail: response.data, life: 3000 });
                createIndicatorPopover.value.hide()
                fetchProgram()
                 indicator.value = {
                    indicator_name:'',
                    indicator_code:'',
                    indicator_active:undefined,
                    sub_program_id:undefined
                }
            })
            .catch((error)=>{
                toast.add({ severity: 'error', summary: 'Action Failed', detail: error.response?.data?.message, life: 3000 });
            })
            .finally(()=>{
                
            })
        }
        else{
            toast.add({ severity: 'error', summary: 'Failed', detail: 'Failed to create sub program, all fields are needed to be filled.', life: 3000 });
        }
    }

    const updateProgram = () => {
        axios.patch(`/program/update/${program.value.program_id}`,{
            program_name:program.value.program_name,
            program_code:program.value.program_code,
            program_active:program.value.program_active
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Action Successful', detail: response.data, life: 3000 });
            fetchProgram()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Action Failed', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{
            
        })
    }

    const updateIndicator = (indicator) => {
        console.log(indicator)
        axios.post('/program/indicator/update',indicator)
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Action Successful', detail: response.data, life: 3000 });
            fetchProgram()
        })
        .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Action Failed', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{
            
        })
    }

</script>