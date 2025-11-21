<template>
  
    <div class="w-full min-h-full h-full flex flex-col justify-start items-start gap-4 overflow-y-auto p-2">

        <AnnouncementPosters/>
        
        <Panel v-if="demographics" class="w-full">
            <template #header>
                <div class="w-full flex justify-between items-center">
                    <span class="uppercase text-lg font-bold">Demographics</span>
                    <!-- <Button @click="demographicsView = 'Chart'" v-if="demographicsView === 'Grid'" icon="pi pi-chart-pie" label="View Chart View" size="small"/>
                    <Button @click="demographicsView = 'Grid'" v-if="demographicsView === 'Chart'" icon="pi pi-table" label="View Grid View" size="small"/> -->
                </div>
            </template>

            <!-- Totatlities -->
            <div class="w-full grid grid-cols-3 gap-4 mb-4">
                <Panel header="Total Provinces for CAR" class="w-full">
                    <span class="text-5xl font-black">{{ demographics?.total_provinces }}</span>
                </Panel>
                <Panel header="Total Municipalities for CAR" class="w-full">
                    <span class="text-5xl font-black">{{ demographics?.total_municipalities }}</span>
                </Panel>
                <Panel header="Total Barangays for CAR" class="w-full">
                    <span class="text-5xl font-black">{{ demographics?.total_barangays }}</span>
                </Panel>
            </div>

            <span class="text-lg font-semibold">Provinces Demographics</span>

            <!-- Per Province -->
            <div class="w-full grid grid-cols-4 gap-4 mb-4">
                <Panel v-for="province in demographics.provinces" class="w-full">
                    <template #header>
                        <span>{{ province.name }}</span>
                    </template>
                    <div class="w-full flex flex-col justify-start items-start uppercase">
                        <span>{{ province.municipalities_count }} Municipalities</span>
                        <span>{{ province.barangays_count }} Barangays</span>

                        <span class="font-medium text-sm mt-2">PK Status</span>
                        <ul class="list-disc ml-5 mt-1 text-sm font-light">
                            <li v-for="(count, status) in province.pk_statuses" :key="status">
                            {{ `${count} ${status}` }}
                            </li>
                        </ul>
                    </div>
                </Panel>
            </div>

            <!-- <Panel header="Total Barangays for CAR" class="w-full">
                <span class="text-5xl font-black">{{ barangayData.barangayTotal }}</span>
            </Panel>
            <div class="w-full grid grid-cols-3 gap-4 mt-4">
                 <div v-for="status in barangayData.barangayByPKStatus">
                    <Panel :header="status.status" class="w-full">
                        <span class="text-2xl font-black">{{ status.count }}</span>
                    </Panel>
                 </div>
            </div> -->
        </Panel>

        <Panel header="Indicators" class="w-full">
           <div class="w-full grid grid-cols-2 justify-start items-start gap-4">
                <Panel v-for="indicator in indicatorsValueSum" :header="indicator.name" class="w-full" style="background-color:azure">
                    <span class="text-5xl font-black">{{ indicator.values_sum }}</span>
                </Panel>
           </div>
        </Panel>

        <Panel header="Purokalusugan Teams" class="w-full">
            <Panel header="Total Teams Created" class="w-full">
                <span class="text-5xl font-black">{{ teamData.total }}</span>
            </Panel>
            <div class="w-full grid grid-cols-3 gap-4 mt-4">
                 <div v-for="province in teamData.countPerProvince">
                    <Panel :header="province.province" class="w-full">
                        <span class="text-2xl font-black">{{ province.total }}</span>
                    </Panel>
                 </div>
            </div>
        </Panel>

    </div>

</template>

<script setup>
    import { ref,onMounted } from 'vue';
    import { Panel, Button } from 'primevue';
    import AnnouncementPosters from './announcement/announcementPosters.vue';
    import axios from '@/utils/axios'

    const teamData = ref({})
    const barangayData = ref({})
    const indicatorsValueSum = ref({})
    const demographics = ref(null)

    // const demographicsView = ref('Grid');

    onMounted(()=>{
        axios.get('dashboard/admin',{
            params:{

            }
        })
        .then((response)=>{
            console.log(response.data)
            teamData.value = response.data.team
            barangayData.value = response.data.barangayData
            indicatorsValueSum.value = response.data.indicatorsValueSum
            demographics.value = response.data.demographics
            console.log(demographics.value)
        })
    })
</script>