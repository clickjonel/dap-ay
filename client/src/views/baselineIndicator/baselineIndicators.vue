<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full"/>
                <label class="text-sm">Search Baseline Indicator</label>
            </FloatLabel>
           <div class="flex justify-center items-center gap-2">
                <Button label="Create Activity" size="small"/>
                <Button label="Create Report for this Month" size="small"/>
           </div>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-full flex flex-col justify-start items-start">
                <div class="w-full flex justify-start items-start uppercase font-semibold bg-teal-100 border-y-2">
                    <span class="w-[20%] p-2">Indicator Name</span>
                    <span class="w-[20%] p-2">Indicator Description</span>
                    <span class="w-[20%] p-2">Indicator Status</span>
                    <span class="w-[15%] p-2">Participants</span>
                    <span class="w-[5%] p-2"></span>
                </div>
                <!-- <div v-for="act in activities.data" class="w-full flex justify-start items-stretch font-light border-b text-sm">
                    <span class="w-[20%] p-2">{{ act.activity_name }}</span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start gap-2">
                        <span>{{ act.activity_venue }}</span>
                        <div class="w-full flex justify-start items-center gap-2">
                            <span class="px-2 bg-gray-200 rounded-full text-xs">{{ act.activity_date_start }}</span>
                            <span class="text-xs">to</span>
                            <span class="px-2 bg-gray-200 rounded-full text-xs">{{ act.activity_date_end }}</span>
                        </div>
                    </span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start text-xs gap-1">
                        <span v-for="partner in act.activity_partners" class="px-2 bg-gray-200 rounded-full">{{ partner }}</span>
                    </span>
                    <span class="w-[20%] p-2 flex flex-col justify-start items-start text-xs gap-1">
                        <span v-for="proponent in act.activity_proponents" class="px-2 bg-gray-200 rounded-full">{{ proponent }}</span>
                    </span>
                    <span class="w-[15%] p-2 flex flex-col justify-start items-start text-xs gap-1">
                        <span class="px-2 bg-sky-200 rounded-full">Programs</span>
                        <span class="px-2 bg-purple-200 rounded-full">Barangays</span>
                    </span>
                    <span class="w-[5%] p-2 flex justify-end items-center gap-2">
                        <span @click="openActionsPopover($event,act)" class="pi pi-ellipsis-v cursor-pointer hover:text-blue-900 hover:scale-120"></span>
                    </span>
                </div> -->
            </div>

            <!-- <div class="w-full min-h-[60px] flex justify-between items-center px-4 border">
                <span class="text-md font-light">Showing rows {{ activities.from }} - {{ activities.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchActivities(activities.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="activities.current_page === 1"/>
                    <span class="border rounded-full size-12 text-xs flex justify-center items-center font-medium">{{ activities.current_page }}</span>
                    <Button @click="fetchActivities(activities.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="activities.next_page_url === null"/>
                </div>
            </div> -->

        </div>

    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, Dialog, FloatLabel, InputText, InputNumber, Select, Textarea, DatePicker, Popover } from 'primevue';
    import axios from '@/utils/axios';
    import { useRouter } from 'vue-router';
    import { useToast } from 'primevue/usetoast';

    const router = useRouter()
    const keyword = ref('')
    const activities = ref([])
    const toast = useToast()
</script>