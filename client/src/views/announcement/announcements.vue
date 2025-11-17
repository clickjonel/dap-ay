<template>
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="border-4 border-gray-300 border-t-white rounded-full w-12 h-12 animate-spin"></div>
        <span class="text-white ml-4">Please wait...</span>
    </div>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">
        <div class="w-full flex justify-between items-center p-2">
            <FloatLabel variant="on" class="w-[400px]">
                <InputText v-model="keyword" class="w-full" @change="fetchAnnouncements(announcements.current_page)" />
                <label class="text-sm">Search</label>
            </FloatLabel>
            <Button @click="router.push('/announcement/create')" label="Add Announcement"
                class="!bg-[#5A686A] !border-none" />
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2 overflow-y-auto">
            <div class="w-full h-full flex flex-col justify-start items-start text-sm">
                <div class="w-full flex justify-start items-start font-medium uppercase bg-[#D3DDDB] sticky top-0">
                    <span class="w-[20%] p-2">Notes</span>
                    <span class="w-[20%] p-2">Duration</span>
                    <span class="w-[20%] p-2">Title</span>
                    <span class="w-[30%] p-2">Details</span>
                    <span class="w-[10%] p-2">Actions</span>
                </div>
                <div v-for="record in announcements.data"
                    class="w-full flex justify-start items-stretch font-light text-sm border-b bg-white hover:bg-[#F0FCFA]">
                    <span class="w-[20%] p-1 flex flex-col">
                        <small>Created by:{{ record.user.full_name }}</small>
                        <small>Created at: {{ displayReadableDateTime(record.created_at) }} </small>
                    </span>
                    <span class="w-[20%] p-1 flex flex-col">
                        <small :class="determineStatusColor(determineStatus(record.date_start,record.date_end))">
                            {{ determineStatus(record.date_start,record.date_end) }}
                        </small>
                        <small>Start:{{ displayReadableDate(record.date_start) }}</small>
                        <small>End:{{ displayReadableDate(record.date_end) }}</small>
                    </span>
                    <span class="w-[20%] p-1 flex items-center">
                        <img :src="record.image_url_source" alt="No Available Image" class="w-20 h-20"/>
                        <strong>{{ record.title }}</strong>
                    </span>
                    <span class="w-[30%] p-1">{{ record.details }}</span>
                    <span class="w-[10%] p-1">
                        <Button @click="router.push(`/announcement/update/${record.id}`)"
                            v-tooltip="'Update Announcement'" icon="pi pi-pencil" size="small" severity="secondary" rounded
                            outlined />
                        <Button @click="deleteAnnouncement(record)" v-tooltip="'Delete Announcement'"
                            class="hover:text-red-500" icon="pi pi-trash" size="small" severity="secondary" rounded
                            outlined />
                    </span>
                </div>
            </div>
            <div class="w-full min-h-[50px] flex justify-between items-center px-4 border">
                <span class="text-sm font-light">Showing rows {{ announcements.from }} - {{ announcements.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchAnnouncements(announcements.current_page - 1)" icon="pi pi-angle-left"
                        severity="info" rounded aria-label="Previous" :disabled="announcements.current_page === 1" />
                    <span class="border rounded-full size-10 text-xs flex justify-center items-center font-medium">{{
                        announcements.current_page }}</span>
                    <Button @click="fetchAnnouncements(announcements.current_page + 1)" icon="pi pi-angle-right"
                        severity="info" rounded aria-label="Next" :disabled="announcements.next_page_url === null" />
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue';
import { Button, FloatLabel, InputText } from 'primevue';
import axios from '@/utils/axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

//variables
const authStore = useAuthStore();
const router = useRouter();
const keyword = ref('');
const announcements = ref([]);
const toast = useToast();
const isLoading = ref(false);
const serverLog = ref({
    created_by_id: 0,
    action_done: '',
    table_name: '',
    column_id: ''
});

//functions
function displayReadableDateTime(dateTime) {
    const result = new Date(dateTime);
    return result.toLocaleString('en-PH', {
        year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        timeZone: 'Asia/Manila'
    });
}

function displayReadableDate(dateTime) {
    const result = new Date(dateTime);
    return result.toLocaleString('en-PH', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
}

function isConfirmedDeleted() {
    const confirm = window.confirm("This will delete the record");
    if (confirm) {
        return true;
    }
    return false;
}

function determineStatus(dateStart, dateEnd) {
    const modifiedDateStart = dateStart.toLocaleString();
    const modifiedDateEnd = dateEnd.toLocaleString();
    const start = new Date(`${modifiedDateStart}T00:00:00`);
    const end = new Date(`${modifiedDateEnd}T23:59:59`);
    const currentDate = new Date();
    if (currentDate >= start && currentDate <= end) {
        return "Active";
    }
    return "Inactive";
}

function determineStatusColor(status){
    let tailwindCSS = "text-center text-white p-1 rounded w-1/4 ";
    if(status === "Inactive"){
        tailwindCSS += "bg-red-500";
    }
    if(status ==="Active"){
        tailwindCSS += "bg-green-500";
    }    
    return tailwindCSS;
}

const createServerLog = (actionDone, tableName, recordId) => {
    serverLog.value.created_by_id = authStore.user?.id;
    serverLog.value.action_done = actionDone;
    serverLog.value.table_name = tableName;
    serverLog.value.column_id = recordId.toLocaleString();
    axios.post('server-log/create', serverLog.value)
        .then((response) => {
            toast.add({
                severity: 'success', summary: 'Created',
                detail: `${response.data.message}`,
                life: 3000
            });
        })
        .catch((error) => {
            const errorMsg = error.response?.data?.errors
                ? Object.values(error.response.data.errors).flat().join('\n')
                : 'Failed to create record'
            toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
        })
        .finally(() => {
            isLoading.value = false;
        })
}

const fetchAnnouncements = (page) => {
    isLoading.value = true;
    axios.get(`/announcement/list/?page=${page}`, {
        params: {
            keyword: keyword.value
        }
    })
        .then((response) => {
            announcements.value = response.data            
        })
        .catch((error) => {``
            console.log(error)
        })
        .finally(() => {
            isLoading.value = false;
        })
}

const deleteAnnouncement = (record) => {
    if (!isConfirmedDeleted()) {
        return;
    }
    const actionDone = `Deleted the announcement titled ${record.title}`;
    const tableName = "announcements";
    createServerLog(actionDone, tableName, record.id);
    axios.delete(`/announcement/delete`, {
        params: {
            id: record.id
        }
    })
        .then(() => {
            toast.add({
                severity: 'success',
                summary: 'Deleted',
                detail: 'Announcement deleted successfully',
                life: 3000
            });
            fetchAnnouncements(1);
        })
        .catch((error) => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: error.response?.data?.message || 'Error deleting announcement',
                life: 3000
            });
        });
}

//effects
onMounted(() => {
    fetchAnnouncements(1);
})
</script>