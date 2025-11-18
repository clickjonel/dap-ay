<template>
  
    <div class="w-full min-h-full h-full flex flex-col justify-start items-start gap-4 overflow-y-auto p-2">

        <AnnouncementPosters/>
        
        <Panel header="Purokalusugan Sites" class="w-full">
            <Panel header="Total Barangays for CAR" class="w-full">
                <span class="text-5xl font-black">{{ barangayData.barangayTotal }}</span>
            </Panel>
            <div class="w-full grid grid-cols-3 gap-4 mt-4">
                 <div v-for="status in barangayData.barangayByPKStatus">
                    <Panel :header="status.status" class="w-full">
                        <span class="text-2xl font-black">{{ status.count }}</span>
                    </Panel>
                 </div>
            </div>
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
    import { Panel } from 'primevue';
    import AnnouncementPosters from './announcement/announcementPosters.vue';
    import axios from '@/utils/axios'

    const teamData = ref({})
    const barangayData = ref({})
    const indicatorsValueSum = ref({})

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
        })
    })
</script>