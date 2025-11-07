<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Update Team</span>

        <Panel header="Team" class="w-full">
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <FloatLabel variant="on" class="w-full text-xs">
                    <InputText v-model="team.team_name" class="w-full text-xs"/>
                    <label class="text-sm">Team Name</label>
                </FloatLabel>
                <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedProvince" 
                            :options="locationStore.provinces" 
                            optionLabel="province_name" 
                            class="w-full" 
                            disabled
                        />
                        <label>Select Province</label>
                    </FloatLabel>
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedMunicipality" 
                            :options="municipalities" 
                            optionLabel="municipality_name" 
                            class="w-full" 
                            disabled
                        />
                        <label>Select Municipality</label>
                    </FloatLabel>
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedBarangay" 
                            :options="barangays" 
                            optionLabel="barangay_name" 
                            optionValue="barangay_id"
                            class="w-full"
                        />
                        <label>Select Barangay</label>
                    </FloatLabel>
                </div>
                <Button @click="handleUpdateTeam" label="Update Team" size="small"/>
           </div>
        </Panel>

        <Panel header="Team Members" class="w-full">
           <div class="w-full flex flex-col justify-start items-start text-sm font-lexend divide-y border">
                <div class="w-full flex justify-start items-start divide-x text-center bg-sky-100 font-medium uppercase">
                    <span class="w-[20%]">Member</span>
                    <span class="w-[10%]">Position</span>
                    <span class="w-[10%]">PK Oriented?</span>
                    <span class="w-[10%]">PK Trained?</span>
                    <span class="w-[10%]">PK Kit Receipt?</span>
                    <span class="w-[20%]">Depolyment Site</span>
                    <span class="w-[20%]"></span>
                </div>
                <div v-for="member in team.members" class="w-full flex justify-start items-stretch divide-x text-center font-light">
                    <span class="w-[20%] p-1">{{ member.member_name }}</span>
                    <span class="w-[10%] p-1">{{ member.member_position }}</span>
                    <span class="w-[10%] p-1">{{ member.member_oriented ? 'Yes' : 'No' }}</span>
                    <span class="w-[10%] p-1">{{ member.member_trained ? 'Yes' : 'No' }}</span>
                    <span class="w-[10%] p-1">{{ member.pk_kit_receipt ? 'Yes' : 'No' }}</span>
                    <span class="w-[20%] p-1">{{ member.site_name }}</span>
                    <span class="w-[20%] flex justify-start items-start gap-4 p-1">
                        <span @click="handleUpdateMemberBtnClick(member)" class="px-2 rounded-full bg-sky-400 text-white cursor-pointer shadow-sm shadow-slate-600">Update</span>
                        <span @click="confirmRemoveMember(member.team_member_id)" class="px-2 rounded-full bg-red-600 text-white cursor-pointer shadow-sm shadow-slate-600">Remove</span>
                    </span>
                </div>
           </div>

           <div v-if="updateMemberFormVisible" class="w-full flex flex-col justify-start items-start mt-4 gap-4"> 
                <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedMember.position" 
                            :options="positions" 
                            optionLabel="position" 
                            class="w-full" 
                            size="small"
                            @change="handleChangePosition"
                        />
                        <label>Select Position</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-full text-xs">
                        <InputText v-model="selectedMember.member_position" class="w-full text-xs" :disabled="selectedMember.position.position !== 'Others'" size="small"/>
                        <label class="text-sm">Position Name</label>
                    </FloatLabel>
                </div>
                <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedMember.member_oriented" 
                            :options="yesNoOptions" 
                            optionLabel="label" 
                            class="w-full" 
                            size="small"
                        />
                        <label>Member Oriented on PK</label>
                    </FloatLabel>
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedMember.member_trained" 
                            :options="yesNoOptions" 
                            optionLabel="label" 
                            class="w-full" 
                            size="small"
                        />
                        <label>Member Trained on PK</label>
                    </FloatLabel>
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedMember.pk_kit_receipt" 
                            :options="yesNoOptions" 
                            optionLabel="label" 
                            class="w-full" 
                            size="small"
                        />
                        <label>Member Has PK Kit Receipt</label>
                    </FloatLabel>
                </div>

                <FloatLabel variant="on" class="w-full text-xs">
                    <InputText v-model="selectedMember.site_name" class="w-full text-xs" size="small"/>
                    <label class="text-sm">Site Deployment</label>
                </FloatLabel>

                <div class="w-full flex justify-start items-start gap-4">
                    <Button @click="handleUpdateMember" label="Update Member" size="small"/>
                    <Button @click="updateMemberFormVisible = false" label="Cancel Update" size="small" severity="info"/>
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
        Popover
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute } from 'vue-router';
    import { useToast } from 'primevue';
    import { useLocationStore } from '@/stores/location';
    import { Confirm } from 'notiflix/build/notiflix-confirm-aio';

    const toast = useToast()
    const route = useRoute()
    const locationStore = useLocationStore()
    const team = ref({})
    const selectedMember = ref({})
    const updateMemberFormVisible = ref(false)
    
    const selectedProvince = ref(null)
    const selectedMunicipality = ref(null)
    const selectedBarangay = ref(null)
    const municipalities = ref([])
    const barangays = ref([])
    const positions = [
        { code: 1, position: 'DOH Deployed Nurse', doh_deployed: true },
        { code: 2, position: 'LGU Hired Nurse', doh_deployed: false },
        { code: 3, position: 'DOH Deployed Midwife', doh_deployed: true },
        { code: 4, position: 'LGU Hired Midwife', doh_deployed: false },
        { code: 5, position: 'Barangay Health Worker', doh_deployed: false },
        { code: 6, position: 'Barangay Nutrition Scholar', doh_deployed: false },
        { code: 7, position: 'Others', doh_deployed: false },
    ]
    const yesNoOptions = [
        { value: true, label: 'Yes' },
        { value: false, label: 'No' }
    ]

    onMounted(async()=>{
       await locationStore.fetchProvinces()
       fetchTeam()
    })

    const fetchTeam = () => {
        axios.get('/team/find',{
            params:{
                team_id:route.params.team_id,
                relationships:[
                    'barangay.municipality.province',
                    'members',
                ]
            }
        })
        .then(async (response)=>{
            team.value = response.data
            
            await locationStore.fetchMunicipalities(team.value.barangay?.municipality?.province_id)
            municipalities.value = locationStore.municipalities

            await locationStore.fetchBarangays(team.value.barangay?.municipality_id, false)
            barangays.value = locationStore.barangays

            selectedProvince.value = locationStore.provinces.find(
                p => p.province_id === team.value.barangay?.municipality?.province?.province_id
            )
            selectedMunicipality.value = municipalities.value.find(
                m => m.municipality_id === team.value.barangay?.municipality_id
            )
            selectedBarangay.value = team.value.barangay?.barangay_id
            
            console.log(team.value)
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to fetch team data.', life: 3000});
        })
    }

    const handleUpdateMemberBtnClick = (member) => {
        updateMemberFormVisible.value = true
        selectedMember.value.team_member_id = member.team_member_id
        selectedMember.value.member_oriented = member.member_oriented === 1 ? yesNoOptions[0] : yesNoOptions[1]
        selectedMember.value.member_trained = member.member_trained === 1 ? yesNoOptions[0] : yesNoOptions[1]
        selectedMember.value.pk_kit_receipt = member.pk_kit_receipt === 1 ? yesNoOptions[0] : yesNoOptions[1]

        selectedMember.value.site_name = member.site_name
        const foundPosition = positions.find(
            position => position.position === member.member_position
        )
        
        selectedMember.value.member_position = ''

        if (foundPosition) {
            selectedMember.value.position = foundPosition
            selectedMember.value.member_position = member.member_position

        } else {
            selectedMember.value.position = positions.find(p => p.position === 'Others')
        }
        selectedMember.value.member_position = member.member_position

    }

    const handleChangePosition = () => {
        if(selectedMember.value.position?.position === 'Others'){
            selectedMember.value.member_position = ''
        }
        else{
            selectedMember.value.member_position = selectedMember.value.position.position
        }
    }

    const handleUpdateMember = () => {
        axios.post('team/member/update', {
            team_member_id : selectedMember.value.team_member_id,
            member_position : selectedMember.value.member_position,
            member_oriented : selectedMember.value.member_oriented?.value,
            member_trained : selectedMember.value.member_trained?.value,
            pk_kit_receipt : selectedMember.value.is_pk_receipt?.value,
            site_name : selectedMember.value.site_name,
        })
        .then((response) => {
            fetchTeam()
            updateMemberFormVisible.value = false
            toast.add({ severity: 'success', summary: 'Updated Member Details', detail: response.data, life: 3000 });
        })
        .catch((error) => {
            const errorMsg = error.response?.data?.errors 
                ? Object.values(error.response.data.errors).flat().join('\n')
                : 'Failed to update member'
            toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
        })
    }

    const handleUpdateTeam = () => {
        axios.post('team/update', {
            team_id : team.value.team_id,
            team_name : team.value.team_name,
            barangay_id : selectedBarangay.value
        })
        .then((response) => {
            fetchTeam()
            toast.add({ severity: 'success', summary: 'Updated Team', detail: response.data, life: 3000 });
        })
        .catch((error) => {
            const errorMsg = error.response?.data?.errors 
                ? Object.values(error.response.data.errors).flat().join('\n')
                : 'Failed to update member'
            toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
        })
    }

    const confirmRemoveMember = (member_id) => {
         Confirm.show(
                'Confirm Delete',
                'Are you sure to remove member?',
                'Remove',
                'Cancel',
            () => {
                handleRemoveMember(member_id)
            },
            () => {
                
            },
            {
                okButtonBackground: '#ef4444',
                okButtonColor: '#ffffff'
            },
        );
    }

    const handleRemoveMember = (member_id) => {
        axios.delete('team/member/delete', {
            data:{
                team_member_id : member_id,
            }
        })
        .then((response) => {
            fetchTeam()
            toast.add({ severity: 'success', summary: 'Updated Team', detail: response.data, life: 3000 });
        })
        .catch((error) => {
            const errorMsg = error.response?.data?.errors 
                ? Object.values(error.response.data.errors).flat().join('\n')
                : 'Failed to update member'
            toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
        })
    }


</script>