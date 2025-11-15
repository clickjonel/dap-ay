<template>
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="border-4 border-gray-300 border-t-white rounded-full w-12 h-12 animate-spin"></div>
        <span class="text-white ml-4">Please wait...</span>
    </div>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <a href='/announcements' class='text-white bg-slate-800 hover:bg-red-500 p-1 rounded'>Return</a>
        <span class="uppercase text-xl font-bold">Create Announcement</span>
        <Panel header="Announcement Details" class="w-full">
            <div v-if="isToDisplayMessageOnTheForm" class="w-full flex justify-center p-2 bg-yellow-100 rounded">
                <strong class="text-red-500 text-center">{{ showMessageOnTheForm() }}</strong>
            </div>
            <div class="w-full flex flex-col justify-start items-start gap-4 pt-4">
                <div class="w-full flex gap-1">
                    <FloatLabel variant="on" class="w-1/2 text-xs">
                        <InputText v-model="announcement.title" class="w-full text-xs" />
                        <label class="text-sm">Title</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-1/4">
                        <DatePicker v-model="announcement.date_start" class="w-full" input-class="w-full" />
                        <label class="text-sm">Date Start</label>
                    </FloatLabel>
                    <FloatLabel variant="on" class="w-1/4">
                        <DatePicker v-model="announcement.date_end" class="w-full" input-class="w-full" />
                        <label class="text-sm">Date End</label>
                    </FloatLabel>
                </div>
                <div class="w-full flex">
                    <FloatLabel variant="on" class="w-full text-xs">
                        <Textarea v-model="announcement.details" class="w-full text-xs" rows='10' />
                        <label class="text-sm">Details</label>
                    </FloatLabel>
                </div>
            </div>
        </Panel>
        <Button @click="createAnnouncement" label="Create Announcement" :disabled="announcement.length === 0" />
    </div>
</template>

<script setup>

import { ref, watch,onMounted } from 'vue';
import {
    Panel,
    FloatLabel,
    InputText,
    Textarea,
    Button,
    DatePicker
} from 'primevue';
import axios from '@/utils/axios';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue';
import { useAuthStore } from '@/stores/auth';

//variables
const authStore = useAuthStore();
const toast = useToast();
const router = useRouter();
const message = ref('');
const isToDisplayMessageOnTheForm = ref(false);
const isLoading = ref(false)
const announcement = ref({
    created_by_id: 0,
    date_start: '',
    date_end: '',
    title: '',
    details: '',
});

const serverLog = ref({
    created_by_id: 0,
    action_done: '',
    table_name: '',
    column_id: ''    
});

//functions
function areRequiredFieldsEntered() {
    let wordsToConcatenate = '';
    let counter = 0;
    if (announcement.value.title === '') {
        wordsToConcatenate += 'Title, ';
        counter++;
    }
    if (announcement.value.details === '') {
        wordsToConcatenate += 'Details, ';
        counter++;
    }
    if (announcement.value.date_start === '') {
        wordsToConcatenate += 'Date Start, '
        counter++;
    }
    if (announcement.value.date_end === '') {
        wordsToConcatenate += 'Date End, ';
        counter++;
    }
    if (counter > 0) {
        message.value = "Field(s) required: (" + wordsToConcatenate + ")";
        return false;
    }
    return true;
}
function showMessageOnTheForm() {
    return message.value;
}

const createServerLog=(actionDone,tableName,recordId)=>{
    serverLog.value.created_by_id = authStore.user?.id;
    serverLog.value.action_done=actionDone;
    serverLog.value.table_name=tableName;
    serverLog.value.column_id=recordId.toLocaleString();
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

const createAnnouncement = () => {
    if (!areRequiredFieldsEntered()) {
        toast.add({ severity: 'Creation Failed', summary: 'Message', detail: message.value, life: 5000 });
        return;
    }
    isLoading.value = true;
    announcement.value.created_by_id = authStore.user?.id;
    const actionDone = `Created an announcement entitled ${announcement.value.title}`;
    const tableName="announcements";
    const recordId = "Unable to obtain from initial creation. Refer to title or creation date instead";
    createServerLog(actionDone,tableName,recordId);   
    axios.post('announcement/create', announcement.value)
        .then((response) => {
            toast.add({
                severity: 'success', summary: 'Created',
                detail: `${response.data.message} for ${response.data.data.title}`,
                life: 3000
            });
            router.push('/announcements')
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

//effects
onMounted(() => {
    //check if there is user token, if true fetch user data
    authStore.initAuth();
})

watch(message, () => {
    if (message.value !== '') {
        isToDisplayMessageOnTheForm.value = true;
    } else {
        isToDisplayMessageOnTheForm.value = false;
    }
}, { immediate: true });
</script>