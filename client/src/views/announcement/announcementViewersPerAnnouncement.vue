<template>
    <div class="p-1 text-xs text-gray-700">
        <template v-if="isLoading">
            <div class="h-4 bg-gray-200 rounded animate-pulse w-4/5"></div>
        </template>
        <template v-else-if="error">
            <span class="text-red-500">{{ error }}</span>
        </template>
        <template v-else>
            <div class="flex flex-wrap gap-1">
                <span v-if="viewers.length === 0" class='text-slate-500 font-bold'>
                    Everyone will be able to see this announcement
                </span>
                <span v-for="viewer in viewers" :key="viewer.id"
                    class="bg-slate-500 text-white rounded p-1">
                    {{ viewer.team.name }}
                </span>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';
import axios from '@/utils/axios';

const props = defineProps({
    announcementId: {
        type: [Number, String],
        required: true
    }
});

const viewers = ref([]);

const isLoading = ref(true);
const error = ref(null);
const fetchViewers = async () => {
    isLoading.value = true;
    error.value = null;
    const endpoint = '/announcement-viewer/list-by-announcement-id';

    try {
        const response = await axios.get(endpoint, {
            params: {
                announcement_id: props.announcementId
            }
        })

        console.log('response data ', response.data)
        viewers.value = response.data;

    } catch (e) {
        error.value = 'Failed to load viewers.';
        console.error("API Fetch Error:", e);
    } finally {
        isLoading.value = false;
    }
}



//effects
onMounted(() => {
    fetchViewers();
});
</script>