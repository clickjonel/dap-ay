<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <a href='/announcements' class='text-white bg-slate-800 hover:bg-red-500 p-1 rounded'>Return</a>
        <span class="uppercase text-xl font-bold">Create Announcement</span>
        <Panel header="Announcement Details" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <div class="w-full flex">
                    <FloatLabel variant="on" class="w-1/4 text-xs">
                        <InputText v-model="announcement.title" class="w-full text-xs" />
                        <label class="text-sm">Title</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-3/4 text-xs">
                        <InputText v-model="announcement.details" class="w-full text-xs" />
                        <label class="text-sm">Details</label>
                    </FloatLabel>
                </div>

            </div>
        </Panel>
        <Button @click="createAnnouncement" label="Create Announcement" :disabled="announcement.length === 0" />
    </div>
</template>

<script setup>

import { ref, computed } from 'vue';
import {
    Panel,
    FloatLabel,
    InputText,
    Select,
    InputNumber,
    Button,
    Popover
} from 'primevue';
import axios from '@/utils/axios';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'primevue';
import { useLocationStore } from '@/stores/location';
import { useUsersStore } from '@/stores/users';

const locationStore = useLocationStore()
const userStore = useUsersStore()
const toast = useToast()
const route = useRoute()
const router = useRouter()

const announcement = ref({
    created_by_id: 0,
    date_start: '',
    date_end: '',
    title: '',
    details: '',
});
//functions
const createAnnouncement = () => {
    axios.post('announcement/create', announcement.value)
        .then((response) => {
            toast.add({ severity: 'success', summary: 'Created', detail: response.data, life: 3000 });
            // Optional: redirect or reset form
            // router.push('/announcement')
        })
        .catch((error) => {
            const errorMsg = error.response?.data?.errors
                ? Object.values(error.response.data.errors).flat().join('\n')
                : 'Failed to create record'
            toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
        })
}
</script>