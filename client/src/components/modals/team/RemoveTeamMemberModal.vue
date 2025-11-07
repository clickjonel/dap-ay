<template>
    <Dialog v-model:visible="visible" modal :style="{ width: '800px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Remove Team Member</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <FloatLabel class="w-full" variant="on">
                <Select v-model="member" 
                    :options="props.members" 
                    optionLabel="member_name" 
                    class="w-full" 
                />
                <label>Select Member</label>
            </FloatLabel>
        </div>

        <template #footer>
            <Button @click="remove" label="Remove Member" severity="info"/>
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
        members:{
            required:true,
            type:Array
        }
    })

    const member = ref(null)

    const remove = () => {
        if(member.value){
             axios.delete('team/member/remove',{
                data:{
                    team_member_id:member.value.team_member_id
                }
             })
            .then((response)=>{
                    toast.add({ severity: 'success', summary: 'Removed Member', detail: response.data, life: 3000 });
                    emit('done')
            })
            .catch((error)=>{
                    toast.add({ severity: 'error', summary: 'Required Fields', detail: error.response?.data?.message, life: 3000 });
            })
            .finally(()=>{

            })
        }
        else{
            toast.add({ severity: 'error', summary: 'Required Fields', detail: 'Select Member to Remove First', life: 3000 });
        }
    }

</script>