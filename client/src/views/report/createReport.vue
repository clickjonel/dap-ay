<template>
    <div class="w-full min-h-screen flex flex-col justify-start items-start p-4 bg-gray-100 gap-4">
        <span class="uppercase text-xl font-bold">Create Report</span>

        <Panel header="Report Details" class="w-full">
           <div class="w-full flex flex-col justify-start items-start gap-4">
                <FloatLabel variant="on" class="w-full">
                    <InputText v-model="auth.user.full_name" class="w-full" disabled=""/>
                    <label class="text-sm">Accomplished By</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <DatePicker 
                        v-model="report.start" 
                        class="w-full" 
                        input-class="w-full"
                    />
                    <label class="text-sm">Report Date</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <Select v-model="report.barangay_id" 
                        :options="handledBarangays" 
                        optionLabel="name"
                        optionValue="id"
                        class="w-full"
                    />
                    <label class="text-sm">Select Barangay (This Selection shows barangays you currently handle)</label>
                </FloatLabel>
           </div>
        </Panel>

        <Panel header="Programs" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-4">

                <span class="my-4 italic font-light text-sm text-amber-600">Important: Fill up data by service of each program and disaggregated by Male, Female, Not Identified and 4Ps Benificiary.</span>

                <div v-for="(sub,index) in subPrograms" :key="sub.id" class="w-full flex flex-col justify-start items-start gap-2 bg-gray-100 p-4 rounded-2xl">
                    <span class="text-sm font-medium uppercase bg-sky-200 px-2 rounded-full">{{ sub.name }}</span>
                    <div class="w-full grid grid-cols-4 gap-4">
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="sub.male" class="w-full"/>
                            <label class="text-sm">Male</label>
                        </FloatLabel>
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="sub.male" class="w-full"/>
                            <label class="text-sm">Female</label>
                        </FloatLabel>
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="sub.male" class="w-full"/>
                            <label class="text-sm">4Ps</label>
                        </FloatLabel>
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="sub.male" class="w-full"/>
                            <label class="text-sm">Not Identified</label>
                        </FloatLabel>
                    </div>
                </div>

            </div>
        </Panel>

        <Button @click="save" label="Create Report" class="!bg-[#5A686A] !border-none"/>

    </div>

</template>

<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { 
        Panel,
        FloatLabel,
        InputText,
        Select,
        InputNumber, 
        Button ,
        Dialog,
        DatePicker
    } from 'primevue';
    import axios from '@/utils/axios';
    import { useRoute, useRouter } from 'vue-router';
    import { useToast } from 'primevue';
    import { useLocationStore } from '@/stores/location';
    import { useUsersStore } from '@/stores/users';
    import { useAuthStore } from '@/stores/auth';

    const locationStore = useLocationStore()
    const userStore = useUsersStore()
    const toast = useToast()
    const route = useRoute()
    const router = useRouter()
    const auth = useAuthStore()

    const addBreakdownModal = ref({
        show: false,
        breakdown: {
            name: '',
            value: null
        },
        index: null
    })

    const handledBarangays = ref([])
    const subPrograms = ref([])

    const report = ref({})
    

    onMounted(() => {
        handledBarangays.value = auth.teams?.flatMap(team => team.barangays) || [];
        getActiveSubPrograms()
    })

    const getActiveSubPrograms = () => {
        axios.get('sub-program/selection', {
            params: {}
        })
        .then((response) => {
          subPrograms.value = response.data.map(sub => {
            return {
                ...sub,
                male:0,
                female:0,
                not_identified:0,
                four_ps:0
            }
          })
        })
        .catch((error) => {
            console.log(error)
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load indicators', life: 3000 });
        })
        .finally(() => {

        })
    }

    const save = () => {
        // Validation
        // if (!report.value.start || !report.value.end || !report.value.barangay_id) {
        //     toast.add({ severity: 'error', summary: 'Error', detail: 'Please fill in all report details', life: 3000 });
        //     return;
        // }

        // axios.post('report/create', {
        //     values: indicators.value,
        //     created_by: auth.user.id,
        //     start: report.value.start,
        //     end: report.value.end,
        //     barangay_id: report.value.barangay_id ?? null
        // })
        // .then((response) => {
        //     toast.add({ severity: 'success', summary: 'Report Submitted', detail: response.data, life: 3000 });
        //     router.push('/reports')
        // })
        // .catch((error) => {
        //     toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        // })
        // .finally(() => {

        // })
    }


</script>