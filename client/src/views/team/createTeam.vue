<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Create Team</span>

        <Panel header="Team Profile" class="w-full">
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
                            @change="handleProvinceChange"
                        />
                        <label>Select Province</label>
                    </FloatLabel>
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedMunicipality" 
                            :options="locationStore.municipalities" 
                            optionLabel="municipality_name" 
                            class="w-full" 
                            :disabled="!selectedProvince"
                            @change="handleMunicipalityChange"
                        />
                        <label>Select Municipality</label>
                    </FloatLabel>
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="team.barangay_id" 
                            :options="locationStore.barangays" 
                            optionLabel="barangay_name" 
                            optionValue="barangay_id"
                            class="w-full"
                            :disabled="!selectedMunicipality"
                        />
                        <label>Select Barangay</label>
                    </FloatLabel>
                </div>
           </div>
        </Panel>

        <Panel header="Team Members List" class="w-full" v-if="team.members.length > 0">
           <div class="w-full flex flex-col justify-start items-start font-lexend divide-y border">
               <div class="w-full flex justify-center items-center text-center text-xs font-medium uppercase divide-x bg-sky-100">
                    <span class="w-1/6 py-2">Member Name</span>
                    <span class="w-1/6 py-2">Position</span>
                    <span class="w-1/6 py-2">Oriented on PK?</span>
                    <span class="w-1/6 py-2">Trained on PK?</span>
                    <span class="w-1/6 py-2">PK Kit Received?</span>
                    <span class="w-1/6 py-2">Deployment Site</span>
               </div>
               <div v-for="(teamMember, index) in team.members" :key="index" class="w-full flex justify-center items-center text-center text-xs font-light divide-x">
                    <span class="w-1/6 py-2">{{ teamMember.member_name }}</span>
                    <span class="w-1/6 py-2">{{ teamMember.member_position }}</span>
                    <span class="w-1/6 py-2">{{ teamMember.member_oriented ? 'Yes' : 'No' }}</span>
                    <span class="w-1/6 py-2">{{ teamMember.member_trained ? 'Yes' : 'No' }}</span>
                    <span class="w-1/6 py-2">{{ teamMember.pk_kit_receipt ? 'Yes' : 'No' }}</span>
                    <span class="w-1/6 py-2">{{ teamMember.site_name }}</span>
               </div>
           </div>
        </Panel>

        <Panel header="Add Members" class="w-full">
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">

                <!-- Position -->
               <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="selectedPosition" 
                            :options="positions" 
                            optionLabel="position" 
                            class="w-full" 
                            @change="handlePositionChange"
                        />
                        <label>Select Position</label>
                    </FloatLabel>

                    <FloatLabel v-if="selectedPosition?.position === 'Others'" variant="on" class="w-full">
                        <InputText v-model="customPosition" class="w-full"/>
                        <label class="text-sm">Position Name</label>
                    </FloatLabel>
               </div>

               <!-- Member Name -->
               <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel v-if="selectedPosition?.doh_deployed" class="w-full text-xs" variant="on">
                        <Select v-model="selectedUser" 
                            :options="userStore.users" 
                            optionLabel="full_name" 
                            class="w-full" 
                            filter
                            @change="handleUserChange"
                        />
                        <label>Select User</label>
                    </FloatLabel>

                    <FloatLabel variant="on" class="w-full">
                        <InputText v-model="memberName" class="w-full" :disabled="selectedPosition?.doh_deployed"/>
                        <label class="text-sm">Member Name</label>
                    </FloatLabel>
               </div>

               <!-- Training/Orientation -->
               <div class="w-full flex justify-start items-start gap-4">
                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="memberOriented" 
                            :options="yesNoOptions" 
                            optionLabel="label" 
                            optionValue="value"
                            class="w-full" 
                        />
                        <label>Member Oriented on PK?</label>
                    </FloatLabel>

                   <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="memberTrained" 
                            :options="yesNoOptions" 
                            optionLabel="label" 
                            optionValue="value"
                            class="w-full" 
                        />
                        <label>Member Trained on PK?</label>
                    </FloatLabel>

                    <FloatLabel class="w-full text-xs" variant="on">
                        <Select v-model="pkKitReceipt" 
                            :options="yesNoOptions" 
                            optionLabel="label" 
                            optionValue="value"
                            class="w-full" 
                        />
                        <label>Member Received PK Functional and Complete Kit?</label>
                    </FloatLabel>
               </div>

                <FloatLabel variant="on" class="w-full text-xs">
                    <InputText v-model="siteName" class="w-full"/>
                    <label class="text-sm">Site of Deployment</label>
                </FloatLabel>
           </div>
 
           <Button @click="submitMember" label="Add Member" severity="info" size="small" class="mt-4"/>
        </Panel>

         <Button @click="createTeam" label="Create Team" :disabled="team.members.length === 0"/>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';
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
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'primevue';
    import { useLocationStore } from '@/stores/location';
    import { useUsersStore } from '@/stores/users';

    const locationStore = useLocationStore()
    const userStore = useUsersStore()
    const toast = useToast()
    const route = useRoute()
    const router = useRouter()
    
    // Team data
    const team = ref({
        team_name: '',
        barangay_id: undefined,
        members: []
    })

    // Location selects
    const selectedProvince = ref(null)
    const selectedMunicipality = ref(null)

    // Member form fields (flat structure)
    const selectedPosition = ref(null)
    const customPosition = ref('')
    const selectedUser = ref(null)
    const memberName = ref('')
    const memberOriented = ref(undefined)
    const memberTrained = ref(undefined)
    const pkKitReceipt = ref(undefined)
    const siteName = ref('')

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

    // Computed final position value
    const finalPosition = computed(() => {
        if (selectedPosition.value?.position === 'Others') {
            return customPosition.value
        }
        return selectedPosition.value?.position || ''
    })

    const handleProvinceChange = () => {
        locationStore.fetchMunicipalities(selectedProvince.value?.province_id)
        selectedMunicipality.value = null
        team.value.barangay_id = undefined
    }

    const handleMunicipalityChange = () => {
        locationStore.fetchBarangays(selectedMunicipality.value?.municipality_id, false)
        team.value.barangay_id = undefined
    }

    const handlePositionChange = () => {
        memberName.value = ''
        selectedUser.value = null
        customPosition.value = ''
    }

    const handleUserChange = () => {
        memberName.value = selectedUser.value?.full_name || ''
    }

    const resetMemberForm = () => {
        selectedPosition.value = null
        customPosition.value = ''
        selectedUser.value = null
        memberName.value = ''
        memberOriented.value = undefined
        memberTrained.value = undefined
        pkKitReceipt.value = undefined
        siteName.value = ''
    }

    const submitMember = () => {
        // Validation
        const isOthers = selectedPosition.value?.position === 'Others'
        const positionValid = isOthers ? customPosition.value.trim() !== '' : selectedPosition.value !== null
        
        if (!memberName.value.trim()) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Member name is required', life: 3000 });
            return
        }
        
        if (!positionValid) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Position is required', life: 3000 });
            return
        }
        
        if (memberOriented.value === undefined || memberTrained.value === undefined || pkKitReceipt.value === undefined) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Please answer all Yes/No questions', life: 3000 });
            return
        }
        
        if (!siteName.value.trim()) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Site of deployment is required', life: 3000 });
            return
        }

        // Create member object
        const newMember = {
            member_name: memberName.value.trim(),
            member_position: finalPosition.value,
            member_trained: memberTrained.value,
            member_oriented: memberOriented.value,
            pk_kit_receipt: pkKitReceipt.value,
            site_name: siteName.value.trim(),
            user_id: selectedUser.value?.user_id || null
        }

        team.value.members.push(newMember)
        toast.add({ severity: 'success', summary: 'Added', detail: 'Member added to list', life: 3000 });
        resetMemberForm()
    }

    const createTeam = () => {
        // Validation
        if (!team.value.team_name.trim()) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Team name is required', life: 3000 });
            return
        }
        
        if (!team.value.barangay_id) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Please select a barangay', life: 3000 });
            return
        }
        
        if (team.value.members.length === 0) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Please add at least one member', life: 3000 });
            return
        }

        axios.post('team/create', team.value)
            .then((response) => {
                toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
                // Optional: redirect or reset form
                // router.push('/teams')
            })
            .catch((error) => {
                const errorMsg = error.response?.data?.errors 
                    ? Object.values(error.response.data.errors).flat().join('\n')
                    : 'Failed to create team'
                toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
            })
    }
</script>