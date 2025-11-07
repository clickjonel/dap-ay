<template>
    <Dialog v-model:visible="visible" modal :style="{ width: '800px' }">
        <template #header>
            <span class="uppercase font-bold text-lg">Create Team</span>
        </template>

        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <InputText v-model="teamForm.team_name" label="Team Name"/>
            <FloatLabel class="w-full" variant="on">
                <Select v-model="teamForm.province" 
                    :options="locationStore.provinces" 
                    optionLabel="province_name" 
                    class="w-full" 
                    @change="locationStore.fetchMunicipalities(teamForm.province.province_id)"
                />
                <label>Select Province</label>
            </FloatLabel>
            <FloatLabel class="w-full" variant="on">
                <Select v-model="teamForm.municipality" 
                    :options="locationStore.municipalities" 
                    optionLabel="municipality_name" 
                    class="w-full" 
                    :disabled="teamForm.province ? false : true"
                    @change="locationStore.fetchBarangays(
                        teamForm.municipality.municipality_id,
                        false
                    )"
                />
                <label>Select Municipality</label>
            </FloatLabel>
            <FloatLabel class="w-full" variant="on">
                <Select v-model="teamForm.barangay" 
                    :options="locationStore.barangays" 
                    optionLabel="barangay_name" 
                    class="w-full" 
                    :disabled="teamForm.municipality ? false : true"
                    @change="locationStore.fetchBarangays(teamForm.municipality.municipality_id)"
                />
                <label>Select Barangay</label>
            </FloatLabel>
        </div>

        <template #footer>
            <Button label="Create Team" severity="info"/>
        </template>
    </Dialog>
</template>

<script setup>
    import { ref,onMounted } from 'vue';
    import { Dialog,Select,Button,FloatLabel } from 'primevue';
    import InputText from '@/components/inputFields/InputText.vue';
    import { useLocationStore } from '@/stores/location';

    const visible = defineModel('visible', { default: false });
    const locationStore = useLocationStore()
    const provinces = ref([])

    const teamForm = ref({
        team_name: '',
        province: null,
        municipality: null,
        barangay: null,
    })

    console.log(locationStore.provinces)

</script>