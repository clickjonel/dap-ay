<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-2">
        <span class="text-xl uppercase font-bold">Purokalusugan Activity Report</span>

        <span class="text-lg uppercase font-semibold mt-2">Activity Details</span>
        <div class="w-full flex flex-col justify-start items-start outline divide-y text-xs font-lexend">
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Name</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_name }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Venue</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_venue }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Budget</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_budget }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Actual Budget</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_actual_budget }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Fund Source</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_fund_source }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Proponents</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_proponents.join(', ') }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Partners</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_partners.join(', ') }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Date/s</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_date_start}} - {{ activity.activity?.activity_date_end }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Type</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_type }}</span>
            </div>
            <div class="w-full flex justify-start items-start gap-4 divide-x">
                <span class="w-1/4 font-medium pl-2"> Activity Scope</span>
                <span class="w-3/4 font-light">{{ activity.activity?.activity_scope }}</span>
            </div>
        </div>

        <span class="uppercase font-semibold mt-2">Activity Serviced Per Barangay</span>
        <div class="w-full flex flex-col justify-start items-start font-lexend gap-4">

            <Panel v-for="(barangayArray,barangayKey) in activity.values" :header="barangayKey" class="w-full">
                <div class="w-full flex flex-col justify-start items-stretch border-x border-t divide-y">
                    <div class="w-full flex flex-col justify-start items-stretch text-xs divide-y">
                        <div class="w-full flex justify-start items-stretch divide-x">
                            <span class="w-1/4 font-medium flex justify-start items-center pl-2 uppercase">Program</span>
                            <div class="w-3/4 flex flex-col justify-start items-stretch divide-y">

                                <div class="w-full flex flex-col justify-start items-stretch">

                                    <div class="w-full flex justify-start items-stretch divide-x">
                                        <span class="w-1/3 text-center flex justify-center items-center font-medium uppercase">Indicator</span>
                                        <span class="w-1/3 text-center flex justify-center items-center font-medium uppercase">Total Served</span>
                                        <span class="w-1/3 flex flex-col justify-start items-stretch divide-y">
                                            <span class="w-full flex justify-start items-start gap-4 divide-x text-center font-medium">
                                                <span class="w-1/2 uppercase">Breakdown Key</span>
                                                <span class="w-1/2 uppercase">Breakdown Value</span>
                                            </span>
                                        </span>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
               </div>
               <div class="w-full flex flex-col justify-start items-stretch border divide-y">
                    <div v-for="(subProgram,subProgramKey) in barangayArray" class="w-full flex flex-col justify-start items-stretch text-xs divide-y">
                        <div class="w-full flex justify-start items-stretch divide-x">
                            <span class="w-1/4 font-medium flex justify-start items-center pl-2">{{ subProgramKey }}</span>
                            <div class="w-3/4 flex flex-col justify-start items-stretch divide-y">

                                <div v-for="indicator in subProgram" class="w-full flex flex-col justify-start items-stretch">

                                    <div class="w-full flex justify-start items-stretch divide-x">
                                        <span class="w-1/3 text-center flex justify-center items-center font-medium">{{ indicator.program_indicator?.indicator_name }}</span>
                                        <span class="w-1/3 text-center flex justify-center items-center font-light">{{ indicator.total_served }}</span>
                                        <span class="w-1/3 flex flex-col justify-start items-stretch divide-y">
                                            <span v-for="breakdown in indicator.breakdown" class="w-full flex justify-start items-start gap-4 divide-x text-center font-light">
                                                <span class="w-1/2">{{ breakdown.key }}</span>
                                                <span class="w-1/2">{{ breakdown.value }}</span>
                                            </span>
                                        </span>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
               </div>

            </Panel>

             <Panel header="Activity Resources Used" class="w-full">
                <div class="w-full flex flex-col justify-start items-start border text-xs font-lexend divide-y">
                    <div class="w-full flex justify-between items-center uppercase font-medium divide-x text-center">
                        <span class="w-1/4 p-1">Resource</span>
                        <span class="w-1/4 p-1">Type</span>
                        <span class="w-1/4 p-1">Total Beneficiaries</span>
                        <span class="w-1/4 p-1">Total Amount</span>
                    </div>
                    <div v-for="resource in activity?.resources" class="w-full flex justify-between items-center divide-x text-center font-light">
                        <span class="w-1/4">{{ resource.resource_name }}</span>
                        <span class="w-1/4">{{ resource.resource_type }}</span>
                        <span class="w-1/4">{{ resource.resource_total_beneficiary }}</span>
                        <span class="w-1/4">{{ resource.resource_amount }}</span>
                    </div>
                </div>
            </Panel>

        </div>

    </div>
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
        Popover,
        DatePicker,
        Textarea
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute } from 'vue-router';
    import { useToast } from 'primevue';
    import { useLocationStore } from '@/stores/location';

    const route = useRoute();
    const activity = ref({})

    onMounted(()=>{
        fetchActivity()
    })

    const fetchActivity = () => {
        axios.get('/activity/report',{
            params:{
                activity_id:route.params.activity_id,
            }
        })
        .then((response)=>{
            activity.value = response.data
           console.log(activity.value)
        })
        .catch((error)=>{
            // toast.add({severity:'error', summary: 'Error', detail: 'Failed to fetch activity data.', life: 3000});
        })
    }

</script>