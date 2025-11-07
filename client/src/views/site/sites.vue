<template>
    <div class="w-full h-full flex flex-col justify-start items-start gap-2">

        <div class="w-full flex justify-between items-center p-2">
            <InputText v-model="keyword" label="Search Keyword" width="w-[300px]" @change="fetchSites(sites.current_page)"/>
            <Button @click="createSiteModal = true" label="Add New PK Site" size="small"/>
        </div>

        <div class="w-full h-full flex flex-col justify-between items-start gap-2">
            <div class="w-full h-[640px] flex flex-col justify-start items-start overflow-y-scroll">
                <div class="w-full flex justify-start items-start divide-x divide-black border-b uppercase font-semibold bg-teal-100 border sticky top-0 z-10">
                    <span class="w-[15%] p-2">Province </span>
                    <span class="w-[15%] p-2">Municipality</span>
                    <span class="w-[20%] p-2">Barangay</span>
                    <span class="w-[15%] p-2">Profile</span>
                    <span class="w-[15%] p-2">Priority(In Order)</span>
                    <span class="w-[20%] p-2">Actions</span>
                </div>
                <div v-for="site in sites.data" class="w-full flex justify-start items-stretch divide-x divide-black border-b font-light border-x text-sm font-poppins">
                    <span class="w-[15%] p-2">{{ site.barangay.province.province_name }}</span>
                    <span class="w-[15%] p-2">{{ site.barangay.municipality.municipality_name }}</span>
                    <span class="w-[20%] p-2">{{ site.barangay.barangay_name }}</ span>
                    <span class="w-[15%] p-2 flex flex-col justify-start items-start gap-1">
                        <Tag severity="info"style="font-size: 12px; font-weight: 300;">Total Purok: {{ site.profile.total_purok }}</Tag>
                        <Tag severity="info"style="font-size: 12px; font-weight: 300;">Total Sitio: {{ site.profile.total_sitio }}</Tag>
                        <Tag severity="info"style="font-size: 12px; font-weight: 300;">Target Purok: {{ site.profile.total_target_purok }}</Tag>
                        <Tag severity="info"style="font-size: 12px; font-weight: 300;">Target Sitio: {{ site.profile.total_target_sitio }}</Tag>
                        <Tag severity="info"style="font-size: 12px; font-weight: 300;">Target Population: {{ site.profile.total_target_population }}</Tag>
                    </span>
                    <span class="w-[15%] p-2 flex flex-col justify-start items-start gap-1">
                        <Tag severity="info" v-for="priority in site.profile.priority_programs" :value="priority.program.program_name" style="font-size: 12px; font-weight: 300;"></Tag>
                    </span>
                    <span class="w-[20%] p-2 flex justify-start items-center gap-2">
                         <Button @click="openUpdateSiteModal(site)" v-tooltip="'Update Site Details'" icon="pi pi-file-edit" severity="help" rounded outlined/>
                         <Button @click="openUpdateSiteProfileModal(site)" v-tooltip="'Update Site Profile'" icon="pi pi-clipboard" severity="help" rounded outlined/>
                         <Button @click="openUpdateSiteProgramModal(site)" v-tooltip="'Update Site Priority Programs'" icon="pi pi-list" severity="help" rounded outlined/>
                    </span>
                </div>
            </div>

            <div class="w-full min-h-[60px] flex justify-between items-center px-4">
                <span class="text-md font-light">Showing rows {{ sites.from }} - {{ sites.to }}</span>
                <div class="flex justify-between items-center gap-4">
                    <Button @click="fetchSites(sites.current_page - 1)" icon="pi pi-angle-left" severity="info" rounded aria-label="Previous" :disabled="sites.current_page === 1"/>
                    <span class="border rounded-full size-12 text-xs flex justify-center items-center font-medium">{{ sites.current_page }}</span>
                    <Button @click="fetchSites(sites.current_page + 1)" icon="pi pi-angle-right" severity="info" rounded aria-label="Next" :disabled="sites.next_page_url === null"/>
                </div>
            </div>

        </div>

    </div>        

    <CreateSiteProfileModal v-model:visible="createSiteModal" @done="handleSiteProfileCreated"/>
    <UpdateSiteModal v-if="updateSiteModal.show" v-model="updateSiteModal.site" v-model:visible="updateSiteModal.show" @done="handleSiteUpdated"/>
    <updateSiteProfileModal v-if="updateSiteProfileModal.show" v-model:visible="updateSiteProfileModal.show" v-model="updateSiteProfileModal.profile" @done="handleSiteProfileUpdated"/>
    <updateSiteProgramsModal v-if="updateSiteProgramsModal.show" v-model:visible="updateSiteProgramsModal.show" v-model="updateSiteProgramsModal.programs" @done="handleSiteProgramsUpdated"/>

</template>

<script setup>
    import { ref, onMounted } from 'vue'; 
    import { Button, Tag } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import axios from '@/utils/axios';
    import { useToast } from 'primevue';
    import CreateSiteProfileModal from '@/components/modals/site/CreateSiteProfileModal.vue';
    import UpdateSiteModal from '@/components/modals/site/UpdateSiteModal.vue';
    import UpdateSiteProfileModal from '@/components/modals/site/UpdateSiteProfileModal.vue';
    import UpdateSiteProgramsModal from '@/components/modals/site/UpdateSiteProgramsModal.vue';

    const toast = useToast()
    const sites = ref([])
    const keyword = ref('')
    const createSiteModal = ref(false)
    const updateSiteModal = ref({
        show:false,
        site:null
    })
    const updateSiteProfileModal = ref({
        show:false,
        profile:null
    })
    const updateSiteProgramsModal = ref({
        show:false,
        programs:null
    })

    onMounted(()=>{
        fetchSites(1)
    })

    // functions
    const fetchSites = (page) => {
        axios.get(`/site/list/?page=${page}`,{
            params:{
                keyword:keyword.value
            }
        })
        .then((response)=>{
            sites.value = response.data
            console.log(sites.value)
        })
        .catch((error)=>{
            console.log(error)
        })
        .finally(()=>{

        })
    }

    const handleSiteProfileCreated = (message) => {
        toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 });
        createSiteModal.value = false
        fetchSites(sites.value.current_page)
    }

    const handleSiteUpdated = (message) => {
        toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 });
        updateSiteModal.value.show = false
        updateSiteModal.value.site = null
        fetchSites(sites.value.current_page)
    }

    const handleSiteProfileUpdated = (message) => {
        toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 });
        updateSiteProfileModal.value.show = false
        updateSiteProfileModal.value.site = null
        fetchSites(sites.value.current_page)
    }

    const handleSiteProgramsUpdated = (message) => {
        toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 });
        updateSiteProgramsModal.value.show = false
        updateSiteProgramsModal.value.programs = null
        fetchSites(sites.value.current_page)
    }

    const openUpdateSiteModal = (site) => {
        updateSiteModal.value.show = true
        updateSiteModal.value.site = {
            province:site.barangay.province,
            municipality:site.barangay.municipality,
            barangay:{
                barangay_id:site.barangay.barangay_id,
                barangay_name:site.barangay.barangay_name
            },
            latitude:site.latitude,
            longitude:site.longitude,
            site_id:site.site_id
        }
    }

    const openUpdateSiteProfileModal = (site) => {
        updateSiteProfileModal.value.show = true,
        updateSiteProfileModal.value.profile = {
            site_profile_id:site.profile.profile_id,
            total_purok: site.profile.total_purok,
            total_sitio: site.profile.total_sitio,
            total_target_purok: site.profile.total_target_purok,
            total_target_sitio: site.profile.total_target_sitio,
            total_target_population: site.profile.total_target_population
        }
    }

    const openUpdateSiteProgramModal = (site) => {
        updateSiteProgramsModal.value.show = true,
        updateSiteProgramsModal.value.programs = site.profile.priority_programs
    }
</script>