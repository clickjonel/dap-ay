<template>
    <Dialog v-model:visible="visible" modal :style="{ width: '800px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Add Team Member</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <FloatLabel class="w-full" variant="on">
                <Select v-model="member.member_role" 
                    :options="member_roles" 
                    optionLabel="role_name" 
                    class="w-full" 
                />
                <label>Select Member Role</label>
            </FloatLabel>
            <FloatLabel v-if="member.member_role?.doh_deployed" class="w-full" variant="on">
                <Select v-model="member.user" 
                    :options="usersStore.users" 
                    optionLabel="full_name" 
                    class="w-full" 
                    filter
                />
                <label>Select User</label>
            </FloatLabel>
            <InputText v-if="!member.member_role?.doh_deployed" v-model="member.member_name" label="Member Name"/>
        </div>

        <template #footer>
            <Button @click="save" label="Add Member" severity="info"/>
        </template>
    </Dialog>
</template>

<script setup>
    import { onMounted, ref } from 'vue';
    import { Dialog,Button,FloatLabel,Select } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    import { useUsersStore } from '@/stores/users';

    const usersStore = useUsersStore();
    const visible = defineModel('visible', { default: false });
    const emit = defineEmits(['done'])
    const toast = useToast()
    const props = defineProps({
        team_id:{
            required:true,
            type:Number
        }
    })

    const member_roles = [
        { code:1 , role_name: 'DOH Deployed Nurse', doh_deployed:true },
        { code:2 , role_name: 'LGU Hired Nurse' , doh_deployed:false },
        { code:3 , role_name: 'DOH Deployed Midwife' , doh_deployed:true },
        { code:4 , role_name: 'LGU Hired Midwife' , doh_deployed:false },
        { code:5 , role_name: 'Barangay Health Worker' , doh_deployed:false },
        { code:6 , role_name: 'Barangay Nutrition Scholar' , doh_deployed:false },
        { code:7 , role_name: 'Others' , doh_deployed:false},
    ]

    const member = ref({
        member_name:'',
        member_role:null,
        user:null
    })


    onMounted(()=>{
        console.log(usersStore.users)
    })

    const save = () => {
        const payload = formatPayload()
        axios.post('team/member/add',payload)
       .then((response)=>{
            toast.add({ severity: 'success', summary: 'Added Member', detail: response.data, life: 3000 });
            member.value = {
                member_name:'',
                member_role:null,
                user:null
            }
            emit('done')
       })
       .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Required Fields', detail: error.response?.data?.message, life: 3000 });
       })
       .finally(()=>{

       })
    }

   const formatPayload = () => {
        var payload = {};

        if(member.value.member_role?.doh_deployed){
            payload = {
                team_id: props.team_id,
                user_id: member.value.user?.user_id,
                member_role: member.value.member_role?.role_name,
                member_name: member.value.user?.full_name
            }
            console.log(payload)
        }
        else{
            payload = {
                team_id: props.team_id,
                user_id: null,
                member_role: member.value.member_role?.role_name,
                member_name: member.value.member_name
            }
            console.log(payload)
        }

        return payload

   }

</script>