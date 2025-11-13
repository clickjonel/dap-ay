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
           </div>
        </Panel>

        <!-- <Panel header="Report Approvals" class="w-full">
          
        </Panel> -->

        <Panel header="Report Barangays" class="w-full">
          
        </Panel>

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

    const report = ref({})
    

</script>