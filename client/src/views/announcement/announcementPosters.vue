<template>
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="border-4 border-gray-300 border-t-white rounded-full w-12 h-12 animate-spin"></div>
        <span class="text-white ml-4">Please wait...</span>
    </div>
    <Panel header="Announcement Posters" class="w-full">
        <div class="relative flex items-center justify-center py-4">
            <button @click="prevPoster" :disabled="currentPosterIndex === 0 && !wrapAround" class="
          z-10 absolute left-0 
          bg-white hover:bg-gray-100 disabled:opacity-50 
          rounded-full w-8 h-8 md:w-10 md:h-10 
          text-lg font-bold shadow-md 
          flex items-center justify-center 
          transition duration-200
        " aria-label="Previous Poster">
                <span aria-hidden="true">&lt;</span>
            </button>

            <div v-if="currentPoster" class="
          w-full max-w-xl 
          p-6 
          border border-gray-200 
          rounded-lg 
          shadow-xl 
          text-center 
          bg-white 
          transition-opacity duration-300 ease-in-out
        ">
                <div class='flex justify-center'>
                    <img :src="determineImageToDisplay(currentPoster)" :alt = "determineAltImageToDisplay(currentPoster)" class="h-full w-full"/>
                </div>
                <div class="space-y-4">
                    <h3 class="text-2xl font-bold text-slate-700">{{ currentPoster.title }}</h3>
                    <p class="text-base text-gray-700">{{ currentPoster.details }}</p>
                </div>
            </div>
            <div v-else class="p-6 text-gray-500">
                No announcements available.
            </div>

            <button @click="nextPoster" :disabled="currentPosterIndex === posters.length - 1 && !wrapAround" class="
          z-10 absolute right-0
          bg-white hover:bg-gray-100 disabled:opacity-50
          rounded-full w-8 h-8 md:w-10 md:h-10 
          text-lg font-bold shadow-md 
          flex items-center justify-center 
          transition duration-200
        " aria-label="Next Poster">
                <span aria-hidden="true">&gt;</span>
            </button>
        </div>

        <div class="flex justify-center mt-4 space-x-2">
            <span v-for="(poster, index) in posters" :key="poster.id" @click="currentPosterIndex = index" :class="[
                'block h-3 w-3 rounded-full cursor-pointer transition-all',
                index === currentPosterIndex ? 'bg-slate-700 w-6' : 'bg-gray-300'
            ]" :aria-label="`Go to poster ${index + 1}`"></span>
        </div>
    </Panel>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Panel } from 'primevue';
import axios from '@/utils/axios';
import AnnouncementImage from '@/assets/images/announcement.gif';

//variables
const wrapAround = true;
const isLoading = ref(false);
const posters = ref([]);
const currentPosterIndex = ref(0);

//functions
const currentPoster = computed(() => {
    return posters.value[currentPosterIndex.value];
});

const nextPoster = () => {
    const newIndex = currentPosterIndex.value + 1;
    if (newIndex >= posters.value.length) {
        currentPosterIndex.value = wrapAround ? 0 : posters.value.length - 1;
    } else {
        currentPosterIndex.value = newIndex;
    }
};

const prevPoster = () => {
    const newIndex = currentPosterIndex.value - 1;
    if (newIndex < 0) {
        currentPosterIndex.value = wrapAround ? posters.value.length - 1 : 0;
    } else {
        currentPosterIndex.value = newIndex;
    }
};

const fetchAnnouncements = () => {
    isLoading.value = true;
    axios.get(`/announcement/posters/list`)
        .then((response) => {
            posters.value = response.data.data;
        })
        .catch((error) => {
            console.log(error)
        })
        .finally(() => {
            isLoading.value = false;
        })
}

function determineImageToDisplay(poster){
    if(poster.image_url_source===null){
        return AnnouncementImage;
    }
    return poster.image_url_source;
}
function determineAltImageToDisplay(poster){
    if(poster.image_url_source===null){
        return "No Image Provided";
    }
    return poster.title;
    
}
//effects
onMounted(() => {
    fetchAnnouncements();
})
</script>