<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Update Activity</span>

        <!-- activity -->
        <Panel header="Activity" class="w-full" toggleable>
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
               <FloatLabel variant="on" class="w-full">
                    <InputText v-model="activity.activity_name" class="w-full"/>
                    <label class="text-sm">Activity Name</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputText v-model="activity.activity_venue" class="w-full"/>
                    <label class="text-sm">Activity Venue</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputNumber v-model="activity.activity_budget" :min-fraction-digits="2" class="w-full"/>
                    <label class="text-sm">Activity Budget</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputNumber v-model="activity.activity_actual_budget" :min-fraction-digits="2" class="w-full"/>
                    <label class="text-sm">Activity Actual Budget</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputText v-model="activity.activity_fund_source" class="w-full"/>
                    <label class="text-sm">Activity Fund Source</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <Textarea v-model="activity.activity_proponents" class="w-full" placeholder="Separated by Comma"/>
                    <label class="text-sm">Activity Proponents</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <Textarea v-model="activity.activity_partners" class="w-full" placeholder="Separated by Comma"/>
                    <label class="text-sm">Activity Partners</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <DatePicker v-model="activity.activity_date_start" style="width: 100%":input-style="{ width: '100%' }"/>
                    <label>Start Date</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <DatePicker v-model="activity.activity_date_end" style="width: 100%":input-style="{ width: '100%' }"/>
                    <label>End Date</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="activity.activity_type" 
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
                <FloatLabel v-if="activity.activity_type" variant="on" class="w-full">
                    <Select v-if="activity.activity_type === 'Small Scale Event'" v-model="activity.activity_scope" 
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
                    <Select v-if="activity.activity_type === 'Large Scale Event'" v-model="activity.activity_scope" 
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

                <Button @click="updateActivity" label="Update Activity"/>
           </div>

        </Panel>

        <Panel header="Participant Programs" class="w-full" toggleable>
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
               <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel variant="on" class="w-full">
                        <Select v-model="selectedProgram" 
                            :options="subPrograms" 
                            optionLabel="sub_program_name" 
                            class="w-full" 
                        />
                        <label>Select Program</label>
                    </FloatLabel>
                    <Button @click="addProgram" class="w-[200px]" label="Add Program" severity="info" :disabled="!selectedProgram"/>
               </div>

               <div class="w-full flex flex-col justify-start items-start divide-y">
                    <div class="w-full flex justify-between items-center uppercase font-semibold bg-sky-100 border-y-2">
                        <span class="w-1/3 p-1">Sub Program</span>
                        <span class="w-1/3 p-1">Program</span>
                        <span class="w-1/3 p-1"></span>
                    </div>
                    <div v-for="sub in activity?.activity_programs" class="w-full flex justify-between items-center uppercase border-b">
                        <span class="w-1/3 p-1">{{ sub.sub_program.sub_program_name }}</span>
                        <span class="w-1/3 p-1">{{ sub.sub_program.program.program_name }}</span>
                        <span class="w-1/3 p-1 flex justify-end items-center">
                            <span @click="confirmRemoveProgram(sub.activity_program_id)" class="text-sm px-4 py-1 border rounded-full bg-red-900 text-white cursor-pointer">Delete</span>
                        </span>
                    </div>
               </div>

           </div>
        </Panel>

        <Panel header="Participant Barangays" class="w-full" toggleable>
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel variant="on" class="w-full">
                        <Select v-model="selectedProvince" 
                            :options="locationStore.provinces" 
                            optionLabel="province_name" 
                            class="w-full" 
                            @change="locationStore.fetchMunicipalities(selectedProvince.province_id)"
                        />
                        <label>Select Province</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-full">
                        <Select v-model="selectedMunicipality" 
                            :options="locationStore.municipalities" 
                            optionLabel="municipality_name" 
                            class="w-full" 
                            @change="locationStore.fetchBarangays(selectedMunicipality.municipality_id)"
                        />
                        <label>Select Municipality</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-full">
                        <Select v-model="selectedBarangay" 
                            :options="locationStore.barangays" 
                            optionLabel="barangay_name"
                            optionValue="barangay_id" 
                            class="w-full" 
                            filter
                        />
                        <label>Select Barangay</label>
                    </FloatLabel>
                    <Button @click="addBarangay" class="w-[500px]" label="Add Barangay" severity="info" :disabled="!selectedBarangay"/>
               </div>
                <div class="w-full flex justify-between items-center uppercase font-semibold bg-sky-100 border-y-2">
                    <span class="w-1/4 p-1">Barangay</span>
                    <span class="w-1/4 p-1">Municipality</span>
                    <span class="w-1/4 p-1">Province</span>
                    <span class="w-1/4 p-1"></span>
                </div>
                <div v-for="brgy in activity?.activity_barangays" class="w-full flex justify-between items-center uppercase border-b">
                    <span class="w-1/4 p-1">{{ brgy.barangay.barangay_name }}</span>
                    <span class="w-1/4 p-1">{{ brgy.barangay.municipality.municipality_name }}</span>
                    <span class="w-1/4 p-1">{{ brgy.barangay.province.province_name }}</span>
                    <span class="w-1/4 p-1 flex justify-end items-center">
                        <span @click="confirmRemoveBarangay(brgy.activity_barangay_id)" class="text-sm px-4 py-1 border rounded-full bg-red-900 text-white cursor-pointer">Delete</span>
                    </span>
                </div>
           </div>
        </Panel>

        <Panel header="Issued Resources" class="w-full" toggleable>
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <div class="w-full flex flex-col justify-start items-start gap-4">
                    <div class="w-full flex justify-between items-center gap-4">
                        <FloatLabel variant="on" class="w-full">
                            <InputText v-model="issuedResource.resource_name" class="w-full"/>
                            <label class="text-sm">Search Activity</label>
                        </FloatLabel>
                        <FloatLabel variant="on" class="w-full">
                            <Select v-model="issuedResource.resource_type" 
                                :options="[
                                    {name:'Medicine'},
                                    {name:'Vaccine'},
                                    {name:'Kits'}
                                ]" 
                                optionLabel="name"
                                optionValue="name" 
                                class="w-full" 
                            />
                            <label>Select Type</label>
                        </FloatLabel>
                    </div>
                    <div class="w-full flex justify-between items-center gap-4">
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="issuedResource.resource_total_beneficiary" class="w-full"/>
                            <label class="text-sm">Activity Resource Total Beneficiary</label>
                        </FloatLabel>
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="issuedResource.resource_amount" :minFractionDigits="2" class="w-full"/>
                            <label class="text-sm">Activity Resource Total Amount</label>
                        </FloatLabel>
                    </div>
                    
                    <Button @click="addIssuedResource" label="Add Issued Resource" severity="info"/>
               </div>

                <div class="w-full flex justify-between items-center uppercase font-semibold bg-sky-100 border-y-2">
                    <span class="w-1/5 p-1">Resource</span>
                    <span class="w-1/5 p-1">Type</span>
                    <span class="w-1/5 p-1">Total Beneficiaries</span>
                    <span class="w-1/5 p-1">Total Amount</span>
                    <span class="w-1/5 p-1"></span>
                </div>
                <div v-for="resource in activity?.activity_resources" class="w-full flex justify-between items-center uppercase border-b">
                    <span class="w-1/5 p-1">{{ resource.resource_name }}</span>
                    <span class="w-1/5 p-1">{{ resource.resource_type }}</span>
                    <span class="w-1/5 p-1">{{ resource.resource_total_beneficiary }}</span>
                    <span class="w-1/5 p-1">{{ resource.resource_amount }}</span>
                    <span class="w-1/5 p-1 flex justify-end items-center">
                        <span @click="confirmRemoveResource(resource.activity_resource_id)" class="text-sm px-4 py-1 border rounded-full bg-red-900 text-white cursor-pointer">Delete</span>
                    </span>
                </div>

           </div>
        </Panel>
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
    import { Confirm } from 'notiflix/build/notiflix-confirm-aio';
    import { useLocationStore } from '@/stores/location';

    const toast = useToast()
    const locationStore = useLocationStore()
    const route = useRoute()
    const activity = ref({})
    const subPrograms = ref([])
    const issuedResource = ref({})

    const selectedProgram = ref(null)
    const selectedProvince = ref(null)
    const selectedMunicipality = ref(null)
    const selectedBarangay = ref(null)

    onMounted(()=>{
        fetchActivity()
        fetchPrograms()
    })

    const fetchActivity = () => {
        axios.get('/activity/find',{
            params:{
                activity_id:route.params.activity_id,
                relationships:[
                    'activityPrograms.subProgram.program',
                    'activityBarangays.barangay.municipality',
                    'activityBarangays.barangay.province',
                    'activityResources'
                ]
            }
        })
        .then((response)=>{
            activity.value = response.data
            activity.value.activity_date_start = new Date(activity.value.activity_date_start)
            activity.value.activity_date_end = new Date(activity.value.activity_date_end)
            activity.value.activity_proponents = activity.value.activity_proponents.join(', ')
            activity.value.activity_partners = activity.value.activity_partners.join(', ')
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to fetch activity data.', life: 3000});
        })
    }

    const fetchPrograms = () => {
        axios.get('/program/selection/sub-programs',{
            params:{
                // activity_id:route.params.activity_id,
            }
        })
        .then((response)=>{
            console.log(response.data)
            subPrograms.value = response.data
        })
        .catch((error)=>{
            
        })
        .finally(()=>{

        })
    }

    const updateActivity = () => {
        axios.post('/activity/update',{
            activity_id: activity.value.activity_id,
            activity_name: activity.value.activity_name,
            activity_venue: activity.value.activity_venue,
            activity_budget: activity.value.activity,
            activity_actual_budget: activity.value.activity_actual_budget,
            activity_fund_source: activity.value.activity_fund_source,
            activity_proponents: activity.value.activity_proponents,
            activity_partners: activity.value.activity_partners,
            activity_date_start: activity.value.activity_date_start ? activity.value.activity_date_start.toISOString().split('T')[0] : null,
            activity_date_end: activity.value.activity_date_end ? activity.value.activity_date_end.toISOString().split('T')[0] : null,
            activity_type: activity.value.activity_type,    
            activity_scope: activity.value.activity_scope,
        })
        .then((response)=>{
            toast.add({severity:'success', summary: 'Success', detail: 'Activity updated successfully.', life: 3000});
            fetchActivity()
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to update activity.', life: 3000});
        })
        .finally(()=>{

        })
    }

    const addProgram = () => {
         axios.post('/activity/program/add',{
            activity_id: activity.value.activity_id,
            sub_program_id:selectedProgram.value.sub_program_id
        })
        .then((response)=>{
            toast.add({severity:'success', summary: 'Success', detail: 'Program Added successfully.', life: 3000});
            selectedProgram.value = null
            fetchActivity()
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to add program.', life: 3000});
        })
        .finally(()=>{

        })
    }

    const addBarangay = () => {
        axios.post('/activity/barangay/add',{
            activity_id: activity.value.activity_id,
            barangay_id:selectedBarangay.value
        })
        .then((response)=>{
            toast.add({severity:'success', summary: 'Success', detail: 'Program Added successfully.', life: 3000});
            selectedBarangay.value = null
            fetchActivity()
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to add program.', life: 3000});
        })
        .finally(()=>{

        })
    }

    const removeProgram = (id) => {
         axios.post('/activity/program/delete',{
            activity_program_id: id,
        })
        .then((response)=>{
            toast.add({severity:'success', summary: 'Success', detail: 'Program Deleted successfully.', life: 3000});
            fetchActivity()
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to add program.', life: 3000});
        })
        .finally(()=>{

        })
    }

    const removeBarangay = (id) => {
         axios.post('/activity/barangay/delete',{
            activity_barangay_id: id,
        })
        .then((response)=>{
            toast.add({severity:'success', summary: 'Success', detail: 'Program Deleted successfully.', life: 3000});
            fetchActivity()
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to add program.', life: 3000});
        })
        .finally(()=>{

        })
    }

    const addIssuedResource = () => {
        if(
            issuedResource.value.resource_name && 
            issuedResource.value.resource_type && 
            issuedResource.value.resource_total_beneficiary && 
            issuedResource.value.resource_amount
        ){
            axios.post('/activity/resource/add',{
                activity_id: activity.value.activity_id,
                resource_name: issuedResource.value.resource_name,
                resource_type: issuedResource.value.resource_type,
                resource_total_beneficiary: issuedResource.value.resource_total_beneficiary,
                resource_amount: issuedResource.value.resource_amount,
            })
            .then((response)=>{
                toast.add({severity:'success', summary: 'Success', detail: 'Issued Resource Added successfully.', life: 3000});
                issuedResource.value = {}
                fetchActivity()
            })
            .catch((error)=>{
                toast.add({severity:'error', summary: 'Error', detail: 'Failed to add issued resource.', life: 3000});
            })
            .finally(()=>{

            })

        }
        else{
            toast.add({severity:'warn', summary: 'Warning', detail: 'Please fill out all fields.', life: 3000});
        }
    }

    const removeIssuedResource = (id) => {
        axios.post('/activity/resource/delete',{
            activity_resource_id: id,
        })
        .then((response)=>{
            toast.add({severity:'success', summary: 'Success', detail: 'Resource Deleted successfully.', life: 3000});
            fetchActivity()
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to remove resource.', life: 3000});
        })
        .finally(()=>{

        })
    }

    const confirmRemoveProgram = (id) => {
        Confirm.show(
                'Confirm Delete',
                'Are you sure to delete this program to this activity?',
                'Delete',
                'Cancel',
            () => {
                removeProgram(id)
            },
            () => {
                
            },
            {
                okButtonBackground: '#ef4444',
                okButtonColor: '#ffffff'
            },
        );
    }

    const confirmRemoveBarangay= (id) => {
        Confirm.show(
                'Confirm Delete',
                'Are you sure to delete this barangay to this activity?',
                'Delete',
                'Cancel',
            () => {
                removeBarangay(id)
            },
            () => {
                
            },
            {
                okButtonBackground: '#ef4444',
                okButtonColor: '#ffffff'
            },
        );
    }

    const confirmRemoveResource= (id) => {
        Confirm.show(
                'Confirm Delete',
                'Are you sure to delete this resource to this activity?',
                'Delete',
                'Cancel',
            () => {
                removeIssuedResource(id)
            },
            () => {
                
            },
            {
                okButtonBackground: '#ef4444',
                okButtonColor: '#ffffff'
            },
        );
    }

</script>