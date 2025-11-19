<template>
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="border-4 border-gray-300 border-t-white rounded-full w-12 h-12 animate-spin"></div>
        <span class="text-white ml-4">Please wait...</span>
    </div>

    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <a href='/announcements' class='text-white bg-slate-800 hover:bg-red-500 p-1 rounded'>Return</a>
        <span class="uppercase text-xl font-bold">Update Announcement</span>

        <Panel header="Announcement Details" class="w-full">
            <div v-if="isToDisplayMessageOnTheForm" class="w-full flex justify-center p-2 bg-yellow-100 rounded">
                <strong class="text-red-500 text-center">{{ showMessageOnTheForm() }}</strong>
            </div>

            <div v-if="announcement.id" class="w-full flex flex-col justify-start items-start gap-4 pt-4">
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
                <div class="w-full flex">
                    <FloatLabel variant="on" class="w-full text-xs">
                        <InputText v-model="announcement.image_url_source" class="w-full text-xs" />
                        <label class="text-sm">Image URL Source</label>
                    </FloatLabel>
                </div>
            </div>
            <div v-else class="text-center p-4">
                <p class="text-gray-500">Loading announcement details or announcement not found...</p>
            </div>
        </Panel>

        <Button @click="updateAnnouncement" label="Update Announcement" :disabled="!announcement.id || !isFormValid" />
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import {
    Panel,
    FloatLabel,
    InputText,
    Textarea,
    Button,
    DatePicker
} from 'primevue';
import axios from '@/utils/axios';
import { useRouter, useRoute } from 'vue-router';
import { useToast } from 'primevue';
import { useAuthStore } from '@/stores/auth';

//variables
const authStore = useAuthStore();
const toast = useToast();
const router = useRouter();
const route = useRoute();
const message = ref('');
const isToDisplayMessageOnTheForm = ref(false);
const isLoading = ref(false);

const announcement = ref({
    id: null,
    created_by_id: 0,
    date_start: '',
    date_end: '',
    title: '',
    details: '',
    image_url_source:''
});

// Computed property for basic form validation
const isFormValid = computed(() => {
    return (
        announcement.value.title !== '' &&
        announcement.value.details !== '' &&
        announcement.value.date_start !== '' &&
        announcement.value.date_end !== ''
    );
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
    
    if (announcement.value.date_start === '' || announcement.value.date_start === null) {
        wordsToConcatenate += 'Date Start, '
        counter++;
    }
    if (announcement.value.date_end === '' || announcement.value.date_end === null) {
        wordsToConcatenate += 'Date End, ';
        counter++;
    }
    if (counter > 0) {
        wordsToConcatenate = wordsToConcatenate.slice(0, -2);
        message.value = `Field(s) required: (${wordsToConcatenate})`;
        return false;
    }
    return true;
}

function showMessageOnTheForm() {
    return message.value;
}

const fetchAnnouncement = async (id) => {
    isLoading.value = true;
    try {
        const response = await axios.get(`announcement/find`,{
            params: {
                id:id
            }
        });        
        const data = response.data;        
        if (data) {
            announcement.value.id = data.id;
            announcement.value.date_start = new Date(data.date_start); 
            announcement.value.date_end = new Date(data.date_end);
            announcement.value.title = data.title;
            announcement.value.details = data.details;
            announcement.value.image_url_source = data.image_url_source;
        } else {
            message.value = 'Announcement not found.';
        }
    } catch (error) {
        const errorMsg = error.response?.data?.message || 'Failed to fetch announcement details.';
        toast.add({ severity: 'error', summary: 'Fetch Error', detail: errorMsg, life: 3000 });
        message.value = 'Failed to load announcement.';
    } finally {
        isLoading.value = false;
    }
};

const updateAnnouncement = () => {
    if (!areRequiredFieldsEntered()) {
        isToDisplayMessageOnTheForm.value = true;
        toast.add({ severity: 'warn', summary: 'Validation Failed', detail: message.value, life: 5000 });
        return;
    }

    isLoading.value = true;
    announcement.value.created_by_id = authStore.user?.id;
    
    const payload = {
        ...announcement.value,
        date_start: announcement.value.date_start ? new Date(announcement.value.date_start).toISOString().split('T')[0] : null,
        date_end: announcement.value.date_end ? new Date(announcement.value.date_end).toISOString().split('T')[0] : null,
    };    
    axios.put('announcement/update', payload)
        .then((response) => {
            toast.add({
                severity: 'success', summary: 'Updated',
                detail: `${response.data.message} for ${response.data.data.title}`,
                life: 3000
            });            
            router.push('/announcements')
        })
        .catch((error) => {
            const errorMsg = error.response?.data?.errors
                ? Object.values(error.response.data.errors).flat().join('\n')
                : error.response?.data?.message || 'Failed to update record'
            toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
        })
        .finally(() => {
            isLoading.value = false;
        })

}

//effects
onMounted(() => {
    authStore.initAuth();
    const announcementId = route.params.announcement_id;
    if (announcementId) {
        announcement.value.id = announcementId; // Set the ID initially
        fetchAnnouncement(announcementId);
    } else {
        message.value = 'No announcement ID provided in the route.';
    }
})

watch(message, () => {
    isToDisplayMessageOnTheForm.value = message.value !== '';
}, { immediate: true });
</script>