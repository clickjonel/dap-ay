<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Create Team</span>

        <Panel header="Team" class="w-full">
           <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
                <FloatLabel variant="on" class="w-full text-xs">
                    <InputText v-model="team" class="w-full text-xs"/>
                    <label class="text-sm">Team Name</label>
                </FloatLabel>
           </div>
        </Panel>

         <Button @click="createTeam" label="Create Team"/>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
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

    const toast = useToast()
    const route = useRoute()
    const router = useRouter()
    
    const team = ref('')



    const createTeam = () => {
        // Validation
        if (!team.value.trim()) {
            toast.add({ severity: 'error', summary: 'Required', detail: 'Team name is required', life: 3000 });
            return
        }
        
        axios.post('team/create', {
            name:team.value
        })
        .then((response) => {
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            // Optional: redirect or reset form
            router.push('/teams')
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Error', detail: error.response.message, life: 3000 });
        })
    }
</script>