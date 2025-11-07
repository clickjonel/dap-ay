<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100">
        <span class="uppercase text-xl font-bold">Populate Activity</span>

        <!-- Barangays -->
        <span class="w-full uppercase font-semibold mt-4 pl-2">Barangays</span>
        <Panel v-for="(activity_barangay, barangayIndex) in barangays" :key="barangayIndex" :header="activity_barangay.barangay.barangay_name" class="w-full mt-2" toggleable>
            <div class="w-full flex flex-col justify-start items-start gap-4">

                <!-- Sub Programs -->
                <Card v-for="(subProgram, subProgramIndex) in activity_barangay.sub_programs" :key="subProgramIndex" class="w-full border border-slate-200">
                    <template #title> 
                        <span class="text-base capitalize underline">{{ subProgram.sub_program_name }}</span> 
                    </template>
                    <template #content>
                       <div class="w-full flex flex-col justify-start items-start gap-2">
            
                          
                            <div v-for="(indicator, indicatorIndex) in subProgram.indicators" :key="indicatorIndex" class="w-full flex flex-col justify-start items-start gap-4 border-y py-4 px-2 bg-gray-100">
                                <div class="w-full flex justify-start items-start gap-2">
                                    <!-- <FloatLabel variant="on" class="w-full">
                                        <InputText v-model="indicator.indicator_name" class="w-full" disabled/>
                                        <label class="text-sm">Indicator Name</label>
                                    </FloatLabel> -->
                                    <FloatLabel variant="on" class="w-full">
                                        <InputNumber v-model="indicator.indicator_total" class="w-full"/>
                                        <label class="text-sm">{{ indicator.indicator_name }}</label>
                                    </FloatLabel>
                                </div>
                                <div class="w-full flex flex-col justify-start items-start gap-4">
                                    <span class="text-sm font-semibold">Set Breakdown of Indicator (optional)</span>
                                    <div class="w-full flex justify-start items-start gap-4">
                                        <FloatLabel variant="on" class="w-full">
                                            <InputText v-model="indicator.breakdownKey" class="w-full" placeholder="eg. Male, Female, 4Ps"/>
                                            <label class="text-sm">Indicator Key Name</label>
                                        </FloatLabel>
                                        <FloatLabel variant="on" class="w-full">
                                            <InputNumber v-model="indicator.breakdownValue" class="w-full"/>
                                            <label class="text-sm">Value</label>
                                        </FloatLabel>
                                        <Button @click="addBreakdown(barangayIndex, subProgramIndex, indicatorIndex)" label="Add Breakdown" severity="info" class="w-[400px]"/>
                                    </div>

                                    <div class="w-full flex flex-col justify-start items-start gap-2">
                                        <span class="text-sm font-semibold">Current Breakdowns:</span>
                                        <div v-if="indicator.breakdowns && indicator.breakdowns.length > 0" class="w-full flex flex-col justify-start items-start gap-1">
                                            <div v-for="(breakdownItem,index) in indicator.breakdowns" :key="index" class="w-full flex justify-between items-center px-4 py-2 bg-white border border-slate-300 rounded-md uppercase">
                                                <span class="text-xs">{{ breakdownItem.key }}: {{ breakdownItem.value }}</span>
                                            </div>
                                        </div>
                                        <span v-else class="text-sm italic text-gray-500">No breakdowns added yet.</span>
                                    </div>

                                </div>
                            </div>
                       </div>
                    </template>
                </Card>

            </div>
        </Panel>

        <Button @click="confirmSave" class="mt-4" label="Save Values"/>

    </div>  
</template>

<script setup>
    import { onMounted,ref } from 'vue';
    import { useRoute } from 'vue-router';
    import { useToast } from 'primevue';
    import axios from '@/utils/axios';
    import { Panel,FloatLabel,InputText,Select,InputNumber, Button ,Popover,DatePicker,Textarea, Card } from 'primevue';
    import { Confirm } from 'notiflix/build/notiflix-confirm-aio';

    const route = useRoute();
    const toast = useToast();

    const activity = ref({})
    const barangays = ref([])
    
    onMounted(() => {
       fetchActivity()
    });

    const fetchActivity = () => {
        axios.get('/activity/find',{
            params:{
                activity_id:route.params.activity_id,
                relationships:[
                    'activityPrograms.subProgram.program',
                    'activityPrograms.subProgram.indicators',
                    'activityBarangays.barangay.municipality',
                    'activityBarangays.barangay.province',
                    'activityResources'
                ]
            }
        })
        .then((response)=>{
            activity.value = response.data

            barangays.value = activity.value.activity_barangays.map((barangay) => {
                // Create a deep copy of the barangay object
                const barangayCopy = JSON.parse(JSON.stringify(barangay));
                
                // Map sub_programs with deep copies for each barangay
                barangayCopy.sub_programs = activity.value.activity_programs.map((subProgram) => {
                    // Create a deep copy of the sub_program
                    const subProgramCopy = JSON.parse(JSON.stringify(subProgram.sub_program));
                    
                    // Initialize indicators with proper defaults
                    subProgramCopy.indicators = subProgramCopy.indicators.map((indicator) => {
                        return {
                            ...indicator,
                            breakdowns: indicator.breakdowns || [],
                            indicator_total: indicator.indicator_total ?? 0,
                            breakdownKey: '',
                            breakdownValue: ''
                        };
                    });
                    
                    return subProgramCopy;
                });
                
                return barangayCopy;
            });

            console.log(barangays.value)

        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to fetch activity data.', life: 3000});
            console.log(error)
        })
    }

    const addBreakdown = (barangayIndex, subProgramIndex, indicatorIndex) => {
        const indicator = barangays.value[barangayIndex].sub_programs[subProgramIndex].indicators[indicatorIndex];
        
        if(!indicator.breakdownKey || !indicator.breakdownValue){
            toast.add({severity:'warn', summary: 'Warning', detail: 'Please provide both key name and value for the breakdown.', life: 3000});
            return;
        }

        if(!Array.isArray(indicator.breakdowns)){
            indicator.breakdowns = []
        }
        
        indicator.breakdowns.push({
            key: indicator.breakdownKey,
            value: indicator.breakdownValue
        })
        
        indicator.breakdownKey = ''
        indicator.breakdownValue = ''

        toast.add({severity:'success', summary: 'Success', detail: 'Breakdown added successfully.', life: 3000});
        
        // console.log('Updated indicator:', indicator)
    }

    const confirmSave = () => {
        // Prepare indicator values to be sent to the backend
        const indicatorValues = barangays.value.flatMap(barangay =>
            barangay.sub_programs.flatMap(subProgram =>
                subProgram.indicators.map(indicator => ({
                    barangay_id: barangay.barangay.barangay_id,
                    sub_program_id: subProgram.sub_program_id,
                    program_indicator_id: indicator.program_indicator_id,
                    total_served: indicator.indicator_total,
                    breakdown: indicator.breakdowns
                }))
            )
        );

        const hasEmptyIndicators = indicatorValues.some(ind => ind.total_served <= 0);
        Confirm.show(
                'Warning',
                hasEmptyIndicators ? 'Some indicators have a total served value of zero or less. Are you sure you want to proceed?' : 'Are you sure you want to save the indicator values?',
                'Yes',
                'No',
            () => {
                save(indicatorValues)
            },
            () => {
                
            },
            {
                okButtonColor:'white',
                okButtonBackground:'#E1C16E',
                fontFamily:'Poppins',
                titleColor:'#B8860B',
            },
        );


       
        
    }

    const save = (indicatorValues) => {

        axios.post('/activity/populate-indicators', {
            activity_id: activity.value.activity_id,
            indicators: indicatorValues
        })
        .then((response)=>{
            // console.log(response.data)
            toast.add({severity:'success', summary: 'Success', detail: 'Activity indicator values saved successfully.', life: 3000});
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to save activity indicator values. Fill up required fields first and if error appears again after saving, contact system administrator.', life: 3000});
            console.log(error)
        })
        .finally(()=>{
            
        })
    }

</script>