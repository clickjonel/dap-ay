<template>
    <Dialog v-model:visible="visible" modal :style="{ width: '800px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Update Team Details</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <InputText v-model="team_name" label="Team Name" size="large"/>
        </div>

        <template #footer>
            <Button @click="save" label="Update" severity="info"/>
        </template>
    </Dialog>
</template>

<script setup>
    import { onMounted, ref } from 'vue';
    import { Dialog,Button } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';

    const visible = defineModel('visible', { default: false });
    const emit = defineEmits(['done'])
    const toast = useToast()
    const props = defineProps({
        team_name:{
            required:true,
            type:String
        },
        team_id:{
            required:true,
            type:Number
        }
    })
    const team_name = ref('')


    onMounted(()=>{
        team_name.value = props.team_name
    })

    const save = () => {
        axios.post('team/update',{
            team_id:props.team_id,
            team_name:team_name.value
        })
       .then((response)=>{
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            emit('done')
       })
       .catch((error)=>{
            toast.add({ severity: 'error', summary: 'Required Fields', detail: error.response?.data?.message, life: 3000 });
       })
       .finally(()=>{

       })
    }

</script>