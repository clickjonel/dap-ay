<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full"/>
                <label class="text-sm">Search Activity</label>
            </FloatLabel>
           <div class="flex justify-center items-center gap-2">
                <Button @click="createActivityModal.show = true" label="Create Activity" size="small"/>
                <Button label="Create Report" size="small"/>
           </div>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-full flex flex-col justify-start items-start">
                <div class="w-full flex justify-start items-start uppercase font-semibold bg-teal-100 border-y-2">
                    <span class="w-[20%] p-2">Activity Name</span>
                    <span class="w-[20%] p-2">Activity Venue</span>
                    <span class="w-[20%] p-2">Activity Partners</span>
                    <span class="w-[20%] p-2">Activity Proponents</span>
                    <span class="w-[15%] p-2">Participants</span>
                    <span class="w-[5%] p-2"></span>
                </div>
                <div v-for="act in activities.data" class="w-full flex justify-start items-stretch font-light border-b text-sm">
                    <span class="w-[20%] p-2">{{ act.activity_name }}</span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start gap-2">
                        <span>{{ act.activity_venue }}</span>
                        <div class="w-full flex justify-start items-center gap-2">
                            <span class="px-2 bg-gray-200 rounded-full text-xs">{{ act.activity_date_start }}</span>
                            <span class="text-xs">to</span>
                            <span class="px-2 bg-gray-200 rounded-full text-xs">{{ act.activity_date_end }}</span>
                        </div>
                    </span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start text-xs gap-1">
                        <span v-for="partner in act.activity_partners" class="px-2 bg-gray-200 rounded-full">{{ partner }}</span>
                    </span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start text-xs gap-1">
                        <span v-for="proponent in act.activity_proponents" class="px-2 bg-gray-200 rounded-full">{{ proponent }}</span>
                    </span>
                    <span class="w-[15%] p-2 flex flex-col justify-start items-start text-xs gap-1">
                        <span class="px-2 bg-sky-200 rounded-full">Programs</span>
                        <span class="px-2 bg-purple-200 rounded-full">Barangays</span>
                    </span>
                    <span class="w-[5%] p-2 flex justify-end items-center gap-2">
                        <!-- <Button v-tooltip="'Update Activity'" icon="pi pi-pen-to-square" severity="help" rounded outlined/>
                        <Button v-tooltip="'Add Barangay Participants'" icon="pi pi-map-marker" severity="info" rounded outlined/>
                        <Button v-tooltip="'Add Programs'" icon="pi pi-wave-pulse" severity="info" rounded outlined/> -->
                        <span @click="openActionsPopover($event,act)" class="pi pi-ellipsis-v cursor-pointer hover:text-blue-900 hover:scale-120"></span>
                    </span>
                </div>
            </div>

            <div class="w-full min-h-[60px] flex justify-between items-center px-4 border">
                <span class="text-md font-light">Showing rows {{ activities.from }} - {{ activities.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchActivities(activities.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="activities.current_page === 1"/>
                    <span class="border rounded-full size-12 text-xs flex justify-center items-center font-medium">{{ activities.current_page }}</span>
                    <Button @click="fetchActivities(activities.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="activities.next_page_url === null"/>
                </div>
            </div>

        </div>

    </div>

    <Popover ref="actionsPopover">
        <div class="flex flex-col gap-2 w-[200px] font-lexend text-sm">
           <span @click="handleActionNavigation('/activity/update/')" class="py-2 px-4 rounded-full bg-sky-200 font-semibold shadow-sm shadow-slate-400 cursor-pointer">Update Activity</span>
           <span v-if="selectedActivity.date_populated === null" @click="handleActionNavigation('/activity/populate/')" class="py-2 px-4 rounded-full bg-sky-200 font-semibold shadow-sm shadow-slate-400 cursor-pointer">Populate Indicators</span>
           <span @click="handleActionNavigation('/activity/report/')" class="py-2 px-4 rounded-full bg-sky-200 font-semibold shadow-sm shadow-slate-400 cursor-pointer">View Report</span>
        </div>
    </Popover>

    <Dialog v-model:visible="createActivityModal.show" modal header="Create Activity" :style="{ width: '1000px' }">
        <div class="w-full flex flex-col justify-start items-start gap-4 p-4"> 
            <FloatLabel variant="on" class="w-full">
                <InputText v-model="createActivityModal.activity.activity_name" class="w-full"/>
                <label class="text-sm">Activity Name</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <InputText v-model="createActivityModal.activity.activity_venue" class="w-full"/>
                <label class="text-sm">Activity Venue</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <InputNumber v-model="createActivityModal.activity.activity_budget" :min-fraction-digits="2" class="w-full"/>
                <label class="text-sm">Activity Budget</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <InputNumber v-model="createActivityModal.activity.activity_actual_budget" :min-fraction-digits="2" class="w-full"/>
                <label class="text-sm">Activity Actual Budget</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <InputText v-model="createActivityModal.activity.activity_fund_source" class="w-full"/>
                <label class="text-sm">Activity Fund Source</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <Textarea v-model="createActivityModal.activity.activity_proponents" class="w-full" placeholder="Separated by Comma"/>
                <label class="text-sm">Activity Proponents</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <Textarea v-model="createActivityModal.activity.activity_partners" class="w-full" placeholder="Separated by Comma"/>
                <label class="text-sm">Activity Partners</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <DatePicker v-model="createActivityModal.activity.activity_date_start" style="width: 100%":input-style="{ width: '100%' }"/>
                <label>Start Date</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <DatePicker v-model="createActivityModal.activity.activity_date_end" style="width: 100%":input-style="{ width: '100%' }"/>
                <label>End Date</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                 <Select v-model="createActivityModal.activity.activity_type" 
                    :options="[
                        {
                            name: 'Small Scale Event'
                        },
                        {
                            name: 'Large Scale Event'
                        }
                    ]" 
                    optionLabel="name" 
                    optionValue="name"
                    class="w-full" 
                />
                <label>Select Activity Type</label>
            </FloatLabel>
            <FloatLabel v-if="createActivityModal.activity.activity_type" variant="on" class="w-full">
                <Select v-if="createActivityModal.activity.activity_type === 'Small Scale Event'" v-model="createActivityModal.activity.activity_scope" 
                    :options="[
                        {
                            name: 'Household'
                        },
                        {
                            name: 'Individual'
                        }
                    ]" 
                    optionLabel="name" 
                    optionValue="name"
                    class="w-full" 
                />
                <Select v-if="createActivityModal.activity.activity_type === 'Large Scale Event'" v-model="createActivityModal.activity.activity_scope" 
                    :options="[
                        {
                            name: 'Municipal Wide'
                        },
                        {
                            name: 'Regional Wide'
                        }
                    ]" 
                    optionLabel="name" 
                    optionValue="name"
                    class="w-full" 
                />
                <label>Select Activity Scope</label>
            </FloatLabel>
        </div>

        <Button @click="createActivity" label="Create Activity" class="shadow-md shadow-slate-400 ml-4"/>
        
    </Dialog>
        
</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, Dialog, FloatLabel, InputText, InputNumber, Select, Textarea, DatePicker, Popover } from 'primevue';
    import axios from '@/utils/axios';
    import { useRouter } from 'vue-router';
    import { useToast } from 'primevue/usetoast';

    const router = useRouter()
    const keyword = ref('')
    const activities = ref([])
    const toast = useToast()
    const actionsPopover = ref()
    const selectedActivity = ref(null)

    const createActivityModal = ref({
        show:false,
        activity:{
            activity_name:'',
            activity_venue:'',
            activity_budget:undefined,
            activity_actual_budget:undefined,
            activity_fund_source:'',
            activity_proponents:'',
            activity_partners:'',
            activity_date_start:null,
            activity_date_end:null,
            activity_type:'',
            activity_scope:''
        }
    })

    onMounted(()=>{
        fetchActivities(1)
    })

    const handleActionNavigation = (url) => {
        router.push(url + selectedActivity.value.activity_id)
    }

    const fetchActivities = (page) => {
        axios.get(`/activity/list/?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            activities.value = response.data
            console.log(activities.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const createActivity = () => {
        axios.post('/activity/create',{
            activity_name: createActivityModal.value.activity.activity_name,
            activity_venue: createActivityModal.value.activity.activity_venue,
            activity_budget: createActivityModal.value.activity.activity_budget,
            activity_actual_budget: createActivityModal.value.activity.activity_actual_budget,
            activity_fund_source: createActivityModal.value.activity.activity_fund_source,
            activity_proponents: createActivityModal.value.activity.activity_proponents,
            activity_partners: createActivityModal.value.activity.activity_partners,
            activity_date_start: createActivityModal.value.activity.activity_date_start ? createActivityModal.value.activity.activity_date_start.toISOString().split('T')[0] : null,
            activity_date_end: createActivityModal.value.activity.activity_date_end ? createActivityModal.value.activity.activity_date_end.toISOString().split('T')[0] : null,
            activity_type: createActivityModal.value.activity.activity_type, 
            activity_scope: createActivityModal.value.activity.activity_scope
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Success', detail: response.data, life: 5000 });
            createActivityModal.value.activity = {
                activity_name:'',
                activity_venue:'',
                activity_budget:undefined,
                activity_actual_budget:undefined,
                activity_fund_source:'',
                activity_proponents:'',
                activity_partners:'',
                activity_date_start:null,
                activity_date_end:null,
                activity_type:'',
                activity_scope:''
            }
            createActivityModal.value.show = false
            fetchActivities(1)
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Failed', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const openActionsPopover = (event,activity) => {
        selectedActivity.value = activity
        actionsPopover.value.toggle(event);
    }
</script>

