<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-4">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchBarangays(barangays.current_page)"/>
                <label class="text-sm">Search</label>
            </FloatLabel>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 overflow-y-auto">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm">
                <div class="w-full flex justify-start items-start font-medium uppercase bg-[#D3DDDB] sticky top-0">
                    <span class="w-[15%] p-2">Barangay</span>
                    <span class="w-[15%] p-2">PK Status</span>
                    <span class="w-[15%] p-2">Total Purok</span>
                    <span class="w-[15%] p-2">Target Purok</span>
                    <span class="w-[15%] p-2">Target Population</span>
                    <span class="w-[15%] p-2">Coordinates</span>
                    <span class="w-[10%] p-2">Action</span>
                </div>
                <div v-for="brgy in barangays.data" class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <span class="w-[15%] p-1 uppercase">{{ `${brgy.province?.name}-${brgy.municipality?.name}-${brgy.name}` }}</span>
                    <span class="w-[15%] p-1">{{ brgy.status }}</span>
                    <span class="w-[15%] p-1">{{ brgy.total_purok ?? 'Total Purok Not Set' }}</span>
                    <span class="w-[15%] p-1">{{ brgy.target_purok  ?? 'Target Purok Not Set' }}</span>
                    <span class="w-[15%] p-1">{{ brgy.target_population  ?? 'Population Not Set' }}</span>
                    <span class="w-[15%] p-1">{{ brgy.latitude && brgy.longitude ? `${brgy.latitude} - ${brgy.longitude}` : 'Coordinates Not Set' }}</span>
                    <span class="w-[10%] p-1">
                        <Button @click="router.push(`/barangay/update/${brgy.id}`)" v-tooltip="'Manage Barangay'" icon="pi pi-cog" size="small" severity="secondary" rounded outlined/>
                        {{ brgy.id }}
                    </span>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-between items-center">
            <span class="text-md font-base">Showing rows {{ barangays.from }} - {{ barangays.to }}</span>
            <div class="flex justify-between items-center gap-4">
                <Button @click="fetchBarangays(barangays.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="barangays.current_page === 1"/>
                <span class="border rounded-full size-10 text-xs flex justify-center items-center font-medium">{{ barangays.current_page }}</span>
                <Button @click="fetchBarangays(barangays.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="barangays.next_page_url === null"/>
            </div>
        </div>

    </div>

</template>

<script setup>
    import { ref,onMounted } from 'vue';
    import axios from '@/utils/axios';
    import { FloatLabel,InputText,Button } from 'primevue';
    import { useRouter } from 'vue-router';

    const router = useRouter()
    const keyword = ref('')
    const barangays = ref([])

    onMounted(()=>{
        fetchBarangays()
    })

    const fetchBarangays = (page = 1) => {
         axios.get(`barangay/list?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            barangays.value = response.data
            console.log(barangays.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }


</script>