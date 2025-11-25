<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Update Team</span>

        <!-- Team -->
        <Panel header="Team" class="w-full">
           <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <FloatLabel variant="on" class="w-full text-xs">
                    <InputText v-model="team.name" class="w-full text-xs"/>
                    <label class="text-sm">Team Name</label>
                </FloatLabel>
                <!-- <div class="w-full flex justify-start items-start gap-4">
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
                </div> -->
                <Button @click="updateTeam" label="Update" size="small"/>
           </div>
        </Panel>

        <!-- Members -->
        <Panel class="w-full">
            <template #header>
                <div class="w-full flex justify-between items-center">
                    <span class="uppercase font-semibold">Team Members</span>
                    <Button @click="addMemberModal.show = true" v-tooltip.left="'Click to add member'" icon="pi pi-plus" size="small" rounded severity="info"/>
                </div>
            </template>

           <div class="w-full flex flex-col justify-start items-start border font-lexend divide-y">
                <div class="w-full flex justify-start items-start divide-x font-medium text-sm bg-sky-100">
                    <span class="w-1/4 p-1">Name</span>
                    <span class="w-1/4 p-1">DOH Deployed</span>
                    <span class="w-1/4 p-1">Position</span>
                    <span class="w-1/4 p-1">Action</span>
                </div>
                <div v-for="member in team.members" class="w-full flex justify-start items-start divide-x font-base text-sm">
                    <span class="w-1/4 p-1">{{ member.name }}</span>
                    <span class="w-1/4 p-1">{{ member.doh_deployed ? 'Yes' : 'No' }}</span>
                    <span class="w-1/4 p-1">{{ member.position }}</span>
                    <span class="w-1/4 p-1">
                        
                    </span>
                </div>
           </div>
        </Panel>

        <!-- Barangays -->
         <Panel class="w-full">
            <template #header>
                <div class="w-full flex justify-between items-center">
                    <span class="uppercase font-semibold">Team Barangays</span>
                    <Button @click="addBarangayModal.show = true" v-tooltip.left="'Click to add barangay'" icon="pi pi-plus" size="small" rounded severity="info"/>
                </div>
            </template>

           <div class="w-full flex flex-col justify-start items-start border font-lexend divide-y">
                <div class="w-full flex justify-start items-start divide-x font-medium text-sm bg-sky-100">
                    <span class="w-1/4 p-1">Province</span>
                    <span class="w-1/4 p-1">Municipality</span>
                    <span class="w-1/4 p-1">Barangay</span>
                    <span class="w-1/4 p-1">Action</span>
                </div>
                <div v-for="brgy in team.barangays" class="w-full flex justify-start items-start divide-x font-base text-sm">
                    <span class="w-1/4 p-1">{{ brgy.name }}</span>
                    <span class="w-1/4 p-1">{{ brgy.municipality.name }}</span>
                    <span class="w-1/4 p-1">{{ brgy.municipality.province.name }}</span>
                    <span class="w-1/4 p-1">
                        
                    </span>
                </div>
           </div>
        </Panel>


    </div>

    <!-- Add Member -->
    <Dialog v-model:visible="addMemberModal.show" modal header="Add Member" :style="{ width: '1000px' }">
        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <div class="w-full flex justify-start items-start gap-4">
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="addMemberModal.member.position" 
                        :options="positions" 
                        optionLabel="position"
                        class="w-full"
                        @change="handleSelectPositionChange"
                    />
                    <label class="text-sm">Position</label>
                </FloatLabel>
                <FloatLabel v-if="addMemberModal.member.position?.position === 'Others'" variant="on" class="w-full">
                    <InputText v-model="addMemberModal.member.position_name" class="w-full"/>
                    <label class="text-sm">Position Name</label>
                </FloatLabel>
                 <FloatLabel variant="on" class="w-full">
                    <Select v-model="addMemberModal.member.role" 
                        :options="[
                            {role:'Team Leader'},
                            {role:'Assistant Team Leader'},
                            {role:'Member'},
                        ]" 
                        optionLabel="role"
                        optionValue="role"
                        class="w-full"
                    />
                    <label class="text-sm">Role</label>
                </FloatLabel>
            </div>

            <div class="w-full flex justify-start items-start gap-4">
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="addMemberModal.member.user" 
                        :options="users" 
                        optionLabel="full_name"
                        class="w-full"
                        filter
                        :disabled="!addMemberModal.member.position?.doh_deployed"
                        @change="handleSelectUserChange"
                    />
                    <label class="text-sm">Member</label>
                </FloatLabel>
                <FloatLabel v-if="!addMemberModal.member.position?.doh_deployed" variant="on" class="w-full">
                    <InputText v-model="addMemberModal.member.member_name" class="w-full"/>
                    <label class="text-sm">Member Name</label>
                </FloatLabel>
            </div>

            <div class="w-full flex justify-start items-start gap-4"    >
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="addMemberModal.member.pk_oriented" 
                        :options="[
                            {value:true,label:'Yes'},
                            {value:false,label:'No'}
                        ]" 
                        optionLabel="label"
                        optionValue="value"
                        class="w-full"
                    />
                    <label class="text-sm">Oriented/Trained on PK?</label>
                </FloatLabel>

                <FloatLabel variant="on" class="w-full">
                    <Select v-model="addMemberModal.member.received_pk_kit" 
                        :options="[
                            {value:true,label:'Yes'},
                            {value:false,label:'No'}
                        ]" 
                        optionLabel="label"
                        optionValue="value"
                        class="w-full"
                    />
                    <label class="text-sm">Member Received PK Kit?</label>
                </FloatLabel>
            </div>

        </div>
        <Button @click="addMember" label="Create" size="small"/>
    </Dialog>

    <!-- Add Barangay -->
    <Dialog v-model:visible="addBarangayModal.show" modal header="Add Barangay" :style="{ width: '1000px' }">
        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <FloatLabel variant="on" class="w-full">
                <Select v-model="addBarangayModal.province" 
                    :options="locationStore.provinces" 
                    optionLabel="name"
                    class="w-full"
                    @change="locationStore.fetchMunicipalities(addBarangayModal.province.id)"
                />
                <label class="text-sm">Province</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <Select v-model="addBarangayModal.municipality" 
                    :options="locationStore.municipalities" 
                    optionLabel="name"
                    class="w-full"
                    @change="locationStore.fetchBarangays(addBarangayModal.municipality.id)"
                />
                <label class="text-sm">Municipality</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <Select v-model="addBarangayModal.barangay" 
                    :options="locationStore.barangays" 
                    optionLabel="name"
                    class="w-full"
                />
                <label class="text-sm">Barangay</label>
            </FloatLabel>
        </div>
        <Button @click="addBarangay" label="Add Selected Barangay" size="small"/>
    </Dialog>

</template>

<script setup>
    import { ref, onMounted,computed } from 'vue';
    import { 
        Panel,
        FloatLabel,
        InputText,
        Select,
        InputNumber, 
        Button ,
        Dialog
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute } from 'vue-router';
    import { useToast } from 'primevue';
    import { useLocationStore } from '@/stores/location';
    import { useUsersStore } from '@/stores/users';
    import { useAuthStore } from '@/stores/auth';

    const toast = useToast()
    const route = useRoute()
    const locationStore = useLocationStore()
    const usersStore = useUsersStore()
    const authStore = useAuthStore()
    const team = ref({})
    const users = computed(() => usersStore.users || [])

    const addMemberModal = ref({
        show:false,
        member:{}
    })

    const addBarangayModal = ref({
        show:false,
        barangay:{},
        province:{},
        municipality:{}
    })
    
    const positions = [
        { code: 1, position: 'DOH Deployed Nurse', doh_deployed: true },
        { code: 2, position: 'LGU Hired Nurse', doh_deployed: false },
        { code: 3, position: 'DOH Deployed Midwife', doh_deployed: true },
        { code: 4, position: 'LGU Hired Midwife', doh_deployed: false },
        { code: 5, position: 'Barangay Health Worker', doh_deployed: false },
        { code: 6, position: 'Barangay Nutrition Scholar', doh_deployed: false },
        { code: 7, position: 'Others', doh_deployed: false },
    ]

    onMounted(()=>{
       fetchTeam()
    })

    const fetchTeam = () => {
        axios.get('/team/find',{
            params:{
                team_id:route.params.team_id,
                relationships:[
                    'members',
                    'barangays.municipality.province'
                ]
            }
        })
        .then(async (response)=>{
            team.value = response.data
            
            console.log(team.value)
        })
        .catch((error)=>{
            toast.add({severity:'error', summary: 'Error', detail: 'Failed to fetch team data.', life: 3000});
        })
    }

    const updateTeam = () => {
        axios.post('team/update',{
            id:team.value.id,
            name:team.value.name
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const addMember = () => {
        axios.post('team-member/create',{
            team_id: team.value.id,
            user_id: addMemberModal.value.member.user ? addMemberModal.value.member.user.id : null,
            doh_deployed:  addMemberModal.value.member.position.doh_deployed,
            name: addMemberModal.value.member.member_name,
            position: addMemberModal.value.member.position_name,
            role: addMemberModal.value.member.role,
            pk_oriented: addMemberModal.value.member.pk_oriented,
            received_pk_kit: addMemberModal.value.member.received_pk_kit
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Updated', detail: response.data, life: 3000 });
            addMemberModal.value.member = {}
            addMemberModal.value.show = false
            authStore.fetchUser()
            fetchTeam()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
        console.log(addMemberModal.value.member)
    }

    const addBarangay = () => {
        axios.post('team-barangay/create',{
            team_id: team.value.id,
            barangay_id: addBarangayModal.value.barangay?.id,
        })
        .then((response)=>{
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            addBarangayModal.value.province = {}
            addBarangayModal.value.municipality = {}
            addBarangayModal.value.barangay = {}
            addBarangayModal.value.show = false
            authStore.fetchUser()
            fetchTeam()
        })
        .catch((error)=>{
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(()=>{

        })
    }

    const handleSelectPositionChange = () => {
        addMemberModal.value.member.position_name = ''
        addMemberModal.value.member.doh_deployed = undefined
        addMemberModal.value.member.user = null
        addMemberModal.value.member.member_name = ''

        if(addMemberModal.value.member.position?.position !== 'Others'){
            addMemberModal.value.member.position_name = addMemberModal.value.member.position.position
            addMemberModal.value.member.doh_deployed = addMemberModal.value.member.position.doh_deployed
        }
    }

    const handleSelectUserChange = () => {
        addMemberModal.value.member.member_name = addMemberModal.value.member.user?.full_name
    }


</script>