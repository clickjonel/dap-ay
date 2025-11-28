<template>
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="border-4 border-gray-300 border-t-white rounded-full w-12 h-12 animate-spin"></div>
        <span class="text-white ml-4">Please wait...</span>
    </div>

    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <a href='/announcements' class='text-white bg-slate-800 hover:bg-red-500 p-1 rounded'>Return</a>
        <span class="uppercase text-xl font-bold">Announcement Update</span>
        <div class='w-full flex justify-center'>
            <h2 class='text-blue-700 font-bold text-xl text-center'>{{ announcement.title }}</h2>
        </div>
        <Panel header="Announcement Viewers" class="w-full">
            <h2>Select what teams will be able to see this announcement: </h2>
            <small class='text-orange-500'>The selection below is based on the teamsOfThisUser you are assigned to. When none is
                selected, the announcement is seen by all users of the system</small>
            <div v-for="record in teamsOfThisUser" :key="record.id">
                <label>
                    <input type="checkbox" :value="record.id" v-model="selectedTeamIds" />
                    {{ record.name }}
                </label>
            </div>
        </Panel>
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
const teamsOfThisUser = ref([]);
const selectedTeamIds = ref([]);
const viewers = ref([]);
const announcement = ref({
    id: null,
    created_by_id: 0,
    date_start: '',
    date_end: '',
    title: '',
    details: '',
    image_url_source: ''
});
const announcementViewer = ref({
    id: null,
    announcement_id: 0,
    team_id: 0
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
        const response = await axios.get(`announcement/find`, {
            params: {
                id: id
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
            console.log('announcement ',announcement.value)
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

const fetchViewers = async () => {        
    const endpoint = '/announcement-viewer/list-by-announcement-id';
    try {
        const response = await axios.get(endpoint, {
            params: {
                announcement_id: route.params.announcement_id
            }
        })
        console.log('response data ', response.data)
        viewers.value = response.data;
    } catch (e) {        
        console.error("API Fetch Error:", e);
    } finally {

    }
}
const handleAnnouncementViewer = () => {
    const iterations = selectedTeamIds.value;
    if (iterations.length == 0) {
        return;
    }
    for (let i = 0; i < iterations.length; i++) {
        const payload = {
            ...announcementViewer.value,
            announcement_id: announcement.value.id,
            team_id: iterations[i]
        };
        axios.post('announcement-viewer/create', payload)
            .then((response) => {
                const logMessage = `${response.data} for announcement viewer`;
                console.log(`create log : `, logMessage);
            })
            .catch((error) => {
                const errorMsg = error.response?.data?.errors
                    ? Object.values(error.response.data.errors).flat().join('\n')
                    : error.response?.data?.message || 'Failed to update record'
                toast.add({ severity: 'error', summary: 'Error', detail: errorMsg, life: 3000 });
            })
            .finally(() => {

            })
    }

}
const deleteAnnouncementViewers = () => {
    axios.delete(`/announcement-viewer/delete-by-announcement-id`, {
        params: {
            announcement_id: announcement.value.id
        }
    })
        .then((response) => { // <-- Add 'response' as the argument
            const logMessage = `${response.data.message} for announcement viewer`; // <-- Access data from the response object
            console.log(`delete log : `, logMessage);
        })
        .catch((error) => {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: error.response?.data?.message || 'Error deleting announcement viewers',
                life: 1000
            });
        });
}
const updateAnnouncement = () => {
    if (!areRequiredFieldsEntered()) {
        isToDisplayMessageOnTheForm.value = true;
        toast.add({ severity: 'warn', summary: 'Validation Failed', detail: message.value, life: 5000 });
        return;
    }

    isLoading.value = true;
    announcement.value.created_by_id = authStore.user?.id;
    deleteAnnouncementViewers();
    handleAnnouncementViewer();
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
function initializeSelectedTeams() {
    const viewerTeamIds = viewers.value.map(viewer => viewer.team_id);    
    const initiallyCheckedIds = teamsOfThisUser.value
        .filter(team => viewerTeamIds.includes(team.id))        
        .map(team => team.id);   
    selectedTeamIds.value = initiallyCheckedIds;
}

//effects
onMounted(async () => { 
    authStore.initAuth();
    const announcementId = route.params.announcement_id;
    teamsOfThisUser.value = authStore.user.teams;

    if (announcementId) {
        announcement.value.id = announcementId;        
        await fetchAnnouncement(announcementId);
        await fetchViewers(); 
        initializeSelectedTeams(); 
        
    } else {
        message.value = 'No announcement ID provided in the route.';
    }
});

watch(message, () => {
    isToDisplayMessageOnTheForm.value = message.value !== '';
}, { immediate: true });

// watch(selectedTeamIds, (newValue) => {
//     console.log('Selected Team IDs changed to:', newValue);
// }, { deep: true });
</script>