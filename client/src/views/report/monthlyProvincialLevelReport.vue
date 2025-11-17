<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-4 p-4 overflow-y-auto">
        <div class="w-full flex justify-start items-start gap-4">
            <FloatLabel variant="on" class="w-1/2">
                <Select v-model="province" 
                    :options="locationStore.provinces" 
                    optionLabel="name"
                    class="w-full"
                    size="small"
                    @change="locationStore.fetchMunicipalities(province.id)"
                />
                <label class="text-sm">Select Province</label>
            </FloatLabel>
            <Button @click="fetchReports" :label="`Create Report for ${province?.name ?? ''}`" size="small" :disabled="!province"/>
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
                <span class="w-1/3 px-2">Month of Report</span>
                <span class="w-2/3 px-2">{{ month }}</span>
            </div>

        </div>

        <div class="w-full flex flex-col justify-start items-start border divide-y text-sm">
            <div class="w-full flex justify-start items-stretch divide-x text-left font-semibold uppercase">
                <span class="w-1/3 p-1">Municipality</span>
                <div class="w-2/3 flex justify-start items-stretch divide-x">
                    <span class="w-2/3 p-1">Indicator</span>
                    <span class="w-1/3 p-1">Total</span>
                </div>
            </div>
            <div v-for="municipality in report.municipalities" class="w-full flex justify-start items-stretch divide-x text-left text-xs font-lexend">
                <span class="w-1/3 p-1">{{ municipality.name }}</span>
                <div class="w-2/3 flex flex-col justify-start items-stretch divide-y">
                   <div v-for="indicator in municipality.aggregated_indicators" class="w-full flex justify-start items-stretch divide-x">
                        <span class="w-2/3 p-1">{{ indicator.indicator_name }}</span>
                        <span class="w-1/3 p-1">{{ indicator.total_value }}</span>
                   </div>
                   <!-- <div class="w-full flex justify-start items-start divide-x font-bold bg-sky-100">
                        <span class="w-2/3 p-1">Total</span>
                        <span class="w-1/3 p-1">{{ report.valueSum }}</span>
                   </div> -->
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

    const report = ref([])
    const month = ref('')
    const province = ref(null)


    const fetchReports = () => {
         axios.get('report/monthly-provincial-level',{
            params:{
                province_id:province.value.id
            }
        })
        .then((response)=>{
            report.value = response.data.province
            month.value = response.data.month
            console.log(response.data)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }
</script>