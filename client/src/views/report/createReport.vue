<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Create Report</span>

        <Panel header="Report Details" class="w-full">
           <div class="w-full flex flex-col justify-start items-start gap-4">
                <FloatLabel variant="on" class="w-full">
                    <InputText v-model="auth.user.full_name" class="w-full" disabled=""/>
                    <label class="text-sm">Accomplished By</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <DatePicker 
                        v-model="report.start" 
                        class="w-full" 
                        input-class="w-full"
                    />
                    <label class="text-sm">Report Start</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <DatePicker 
                        v-model="report.end" 
                        class="w-full" 
                        input-class="w-full"
                    />
                    <label class="text-sm">Report End</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="report.barangay" 
                        :options="handledBarangays" 
                        optionLabel="name"
                        optionValue="id"
                        class="w-full"
                    />
                    <label class="text-sm">Select Barangay</label>
                </FloatLabel>
           </div>
        </Panel>

        <Panel header="Report Values" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-4">
                <FloatLabel v-for="ind in indicators" variant="on" class="w-full">
                    <InputNumber v-model="ind.value" class="w-full"/>
                    <label class="text-sm">{{ ind.name }}</label>
                </FloatLabel>
            </div>
        </Panel>

        <Button label="Create Report" class="!bg-[#5A686A] !border-none"/>

    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { 
        Panel,
        FloatLabel,
        InputText,
        Select,
        InputNumber, 
        Button ,
        Popover,
        DatePicker
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'primevue';
    import { useLocationStore } from '@/stores/location';
    import { useUsersStore } from '@/stores/users';
    import { useAuthStore } from '@/stores/auth';

    const locationStore = useLocationStore()
    const userStore = useUsersStore()
    const toast = useToast()
    const route = useRoute()
    const router = useRouter()
    const auth = useAuthStore()

    const handledBarangays = ref([])
    const indicators = ref([])

    const report = ref({})
    

    onMounted(()=>{
        handledBarangays.value = auth.teams?.flatMap(team => team.barangays) || [];
        getActiveIndicators()
    })

    const getActiveIndicators = () => {
         axios.get('indicator/active',{
            params:{}
        })
        .then((response)=>{
            indicators.value = response.data
            console.log(indicators.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

</script>