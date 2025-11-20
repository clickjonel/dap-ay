<template>
   <div class="w-full h-full flex flex-col justify-start items-start gap-4">
        <div class="w-full flex justify-between items-center p-2">
            <Button @click="showFormReport = true" icon="pi pi-print" severity="info" rounded/>
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
                <div v-for="report in reports" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
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

    </div>

    <!-- form Report Print -->
    <div v-if="showFormReport" class="absolute inset-0 bg-white flex flex-col justify-start items-start gap-4 p-2">

        <div class="w-full flex justify-end items-center gap-2 print:hidden">
            <Button @click="print" icon="pi pi-print" label="Print Report" severity="help"/>
            <Button @click="showFormReport = false" label="Close" severity="info"/>
        </div>

        <!-- Header -->
        <div class="w-full flex justify-between items-center gap-4">
            <img :src="dohLogo" alt="" class="size-[120px]">
            <div class="w-full flex flex-col justify-start items-center">
                <span>Republic of the Philippines</span>
                <span>Department of Health</span>
                <span>Center for Health Development</span>
                <span>Cordillera Administrative Region</span>
            </div>
            <img :src="dohLogo" alt="" class="size-[120px]">
        </div>

        <!-- Details head -->
        <div class="w-full flex flex-col justify-start items-start border divide-y">
            <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/4 px-2">Name of Region:</span>
                <span class="w-3/4 text-left px-2">Cordillera Administrative Region</span>
            </div>
            <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/4 px-2">Report Month:</span>
                <span class="w-3/4 text-left px-2">{{ month }}</span>
            </div>
        </div>

        <div class="w-full flex flex-col justify-start items-start border divide-y">
            <div class="w-full grid grid-cols-4 divide-x">
                <div class="w-full col-span-1">
                    <span>Barangay</span>
                </div>
                <div class="w-full col-span-3 flex flex-col justify-start items-start divide-y text-sm font-light">
                    <div class="w-full flex justify-start items-start divide-x">
                        <span class="w-3/4 px-2 py-1">Indicator</span>
                        <span class="w-1/4 px-2 py-1">Total</span>
                    </div>
                </div>
            </div>
            <div v-for="report in reports" class="w-full grid grid-cols-4 divide-x">
                <div class="w-full col-span-1 flex flex-col justify-start items-start">
                    <span class="p-2 text-base uppercase underline">Name: {{ report.barangay?.name }}</span>
                    <span class="px-2 text-sm font-light">Total Purok : {{ report.barangay?.total_purok ?? 'Not Set' }}</span>
                    <span class="px-2 text-sm font-light">Target Purok: {{ report.barangay?.target_purok ?? 'Not Set' }}</span>
                    <span class="px-2 text-sm font-light">Target Population: {{ report.barangay?.target_population ?? 'Not Set' }}</span>
                    <span class="px-2 text-sm font-light">PK Status: {{ report.barangay?.status}}</span>
                </div>
                <div class="w-full col-span-3 flex flex-col justify-start items-start divide-y text-sm font-light">
                    <div v-for="indicator in report.values" class="w-full flex justify-start items-start divide-x uppercase">
                        <span class="w-3/4 px-2 py-1">{{ indicator.indicator?.name }}</span>
                        <span class="w-1/4 px-2 py-1">{{ indicator.value }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="print:hidden">
            <Button @click="addSignatory" icon="pi pi-plus" label="Add Signatory" severity="info"/>
        </div>

         <!-- Add Signatory -->
        <div class="w-full grid grid-cols-3 gap-4">
            <div v-for="signatory in signatories" class="w-full flex flex-col justify-center items-center">
                <span class="italic font-light">
                    <input v-model="signatory.action" type="text" class="bg-transparent outline-none text-sm text-left">
                </span>
                <span class="w-full flex justify-center items-center">
                    <input v-model="signatory.name" type="text" class="w-full bg-transparent outline-none text-sm text-center border-b">
                </span>
                <span class="w-full font-light flex justify-center items-center">
                    <input v-model="signatory.position" type="text" class="w-full bg-transparent outline-none text-sm text-center">
                </span>
            </div>
        </div>
        

    </div>

</template>

<script setup>
    import { ref,onMounted } from 'vue';
    import axios from '@/utils/axios';
    import { FloatLabel,InputText,Button } from 'primevue';
    import { useRouter } from 'vue-router';
    import dohLogo from '@/assets/images/doh-logo.svg'
    import { useAuthStore } from '@/stores/auth';

    const router = useRouter()
    const keyword = ref('')
    const reports = ref([])
    const month = ref('')
    const showFormReport = ref(false)
    const authStore = useAuthStore()
    const signatories = ref([
        {
            name:authStore.user.full_name,
            action:'Accomplished By:',
            position:'Enter Position'
        }
    ])

    onMounted(()=>{
        fetchReports()
    })

    const fetchReports = (page = 1) => {
         axios.get(`report/monthly-user-reports?page=${page}`,{
            params:{
                // keyword:keyword.value
            }
        })
        .then((response)=>{
            reports.value = response.data.reports
            month.value = response.data.month
            console.log(reports.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const print = () => {
        window.print()
    }

    const addSignatory = () => {
        signatories.value.push({
            name:'Enter Name',
            action:'Enter Action',
            position:'Enter Position'
        })
    }

</script>