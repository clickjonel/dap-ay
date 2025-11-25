<template>
   <div class="w-full h-full flex flex-col justify-start items-start gap-4">
        <div v-if="auth.user.user_level === 4" class="w-full flex justify-end items-center p-2">
            <!-- <Button icon="pi pi-filter" severity="info" rounded outlined/> -->
            <Button @click="router.push('/report/create')" label="Create Report" class="!bg-[#5A686A] !border-none"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 overflow-y-auto">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm">
                <div class="w-full flex justify-start items-start font-medium uppercase bg-[#D3DDDB] sticky top-0">
                    <span class="w-[20%] p-2">Created By</span>
                    <span class="w-[20%] p-2">Start Date</span>
                    <span class="w-[20%] p-2">End Date</span>
                    <span class="w-[20%] p-2">Barangay</span>
                    <span class="w-[20%] p-2">Action</span>
                </div>
                <div v-for="report in reports.data" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <span class="w-[20%] p-2">{{ report.created_by.full_name }}</span>
                    <span class="w-[20%] p-2">{{ report.start }}</span>
                    <span class="w-[20%] p-2">{{ report.end ?? 'Total Purok Not Set' }}</span>
                    <span class="w-[20%] p-2">{{ report.barangay.name }}</span>
                    <span class="w-[10%] p-2">
                        <Button v-tooltip="'Manage Report'" icon="pi pi-cog" size="small" severity="secondary" rounded outlined/>
                    </span>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-between items-center">
            <span class="text-md font-base">Showing rows {{ reports.from }} - {{ reports.to }}</span>
            <div class="flex justify-between items-center gap-4">
                <Button @click="fetchReports(reports.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="reports.current_page === 1"/>
                <span class="border rounded-full size-10 text-xs flex justify-center items-center font-medium">{{ reports.current_page }}</span>
                <Button @click="fetchReports(reports.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="reports.next_page_url === null"/>
            </div>
        </div>

    </div>

</template>

<script setup>
    import { ref,onMounted } from 'vue';
    import axios from '@/utils/axios';
    import { FloatLabel,InputText,Button } from 'primevue';
    import { useRouter } from 'vue-router';
    import { useAuthStore } from '@/stores/auth';

    const router = useRouter()
    const auth = useAuthStore()
    const keyword = ref('')
    const reports = ref([])

    onMounted(()=>{
        fetchReports()
    })

    const fetchReports = (page = 1) => {
         axios.get(`report/list?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            reports.value = response.data
            console.log(reports.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }
</script>