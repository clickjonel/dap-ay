<template>
     <AnnouncementPosters/>
    <div class="w-full flex flex-col justify-start items-start gap-4">
        <Panel header="Baseline Data" class="w-full">
            <span>Totals of Indicators(Targets Purok,)</span>
        </Panel>

        <Panel header="Baseline Data" class="w-full">
            <span>Indicator Total By Group</span>
        </Panel>

        <Panel header="Purokalusugan Activities" class="w-full">
            <span>Total this year</span>
            <span>Total household</span>
            <span>Total individual</span>
            <span>Total municipal</span>
            <span>Total regional</span>
        </Panel>

        <Panel header="Purokalusugan Teams" class="w-full">
            <Panel header="Total Teams Created" class="w-full m-2">
                <span class="text-5xl font-black">{{ teamData.total }}</span>
            </Panel>
            <div class="w-full grid grid-cols-3 gap-4 p-2">
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

    onMounted(()=>{
        axios.get('dashboard/admin',{
            params:{

            }
        })
        .then((response)=>{
            console.log(response.data)
            teamData.value = response.data.team
        })
    })
</script>