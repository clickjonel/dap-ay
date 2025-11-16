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
            <span>Total</span>
            <span>Total per province</span>
        </Panel>

    </div>

</template>

<script setup>
    import { Panel } from 'primevue';
    import AnnouncementPosters from './announcement/announcementPosters.vue';
</script>