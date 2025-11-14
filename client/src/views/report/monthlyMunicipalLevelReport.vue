<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-4 p-4 overflow-y-auto">
        <div class="w-full flex justify-start items-start gap-4">
            <FloatLabel variant="on" class="w-1/3">
                <Select v-model="province" 
                    :options="locationStore.provinces" 
                    optionLabel="name"
                    class="w-full"
                    size="small"
                    @change="locationStore.fetchMunicipalities(province.id)"
                />
                <label class="text-sm">Select Province</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-1/3">
                <Select v-model="municipality" 
                    :options="locationStore.municipalities" 
                    optionLabel="name"
                    class="w-full"
                    size="small"
                />
                <label class="text-sm">Select Municipality</label>
            </FloatLabel>
            <Button @click="fetchReports" :label="`Create Report for ${municipality?.name ?? ''}`" size="small" :disabled="!municipality"/>
        </div>

        <div class="w-full flex flex-col justify-start items-start border divide-y text-sm">
            <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/3 px-2">Name of Region</span>
                <span class="w-2/3 px-2">Cordillera Administrative Region</span>
            </div>
            <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/3 px-2">Name of Province</span>
                <span class="w-2/3 px-2">{{ province?.name }}</span>
            </div>
            <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/3 px-2">Name of Municipality</span>
                <span class="w-2/3 px-2">{{ municipality?.name }}</span>
            </div>
            <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/3 px-2">Month of Report</span>
                <span class="w-2/3 px-2">{{ month }}</span>
            </div>
            <!-- <div class="w-full flex justify-start items-start divide-x">
                <span class="w-1/3 p-1">Date of Report</span>
                <span class="w-2/3 p-1">Cordillera Administrative Region</span>
            </div> -->
        </div>

        <div class="w-full flex flex-col justify-start items-start border divide-y text-sm">
            <div class="w-full flex justify-start items-stretch divide-x text-left font-semibold uppercase">
                <span class="w-1/3 p-1">Barangay</span>
                <div class="w-2/3 flex justify-start items-stretch divide-x">
                    <span class="w-2/3 p-1">Indicator</span>
                    <span class="w-1/3 p-1">Total</span>
                </div>
            </div>
            <div v-for="report in reports" class="w-full flex justify-start items-stretch divide-x text-left font-light text-xs">
                <span class="w-1/3 p-1">{{ report.barangay.name }}</span>
                <div class="w-2/3 flex flex-col justify-start items-stretch divide-y">
                   <div v-for="value in report.values" class="w-full flex justify-start items-stretch divide-x">
                        <span class="w-2/3 p-1">{{ value.indicator.name }}</span>
                        <span class="w-1/3 p-1">{{ value.value }}</span>
                   </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref,onMounted } from 'vue';
    import axios from '@/utils/axios';
    import { FloatLabel,Panel,Button, Select,Card } from 'primevue';
    import { useRouter } from 'vue-router';
    import { useLocationStore } from '@/stores/location';

    const router = useRouter()
    const locationStore = useLocationStore() 

    const reports = ref([])
    const month = ref('')
    const province = ref(null)
    const municipality = ref(null)


    const fetchReports = () => {
         axios.get('report/monthly-municipal-level',{
            params:{
                municipality_id:municipality.value.id
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
</script>