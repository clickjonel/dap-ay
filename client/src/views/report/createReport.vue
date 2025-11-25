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
                    <label class="text-sm">Report Start</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <DatePicker 
                        v-model="report.end" 
                        class="w-full" 
                        input-class="w-full"
                    />
                    <label class="text-sm">Report End</label>
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

        <Panel header="Current Indicators" class="w-full">
            <div class="w-full flex flex-col justify-start items-start gap-4">

                <span class="my-4 italic font-light text-sm text-amber-600">Important: Indicators are initially set to zero, update indicator values as applicable. Include Breakdowns if possible, an icon next to the indicator will show once the indicator is more than 0. Check values inputted before submitting to ensure data correctness to avoid data mismatch.</span>

                <div v-for="(ind,index) in indicators" :key="index" class="w-full flex flex-col justify-start items-start gap-2 bg-gray-100 p-4 rounded-2xl">
                    <div class="w-full flex justify-center items-center gap-4">
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber v-model="ind.value" class="w-full"/>
                            <label class="text-sm">{{ ind.name }}</label>
                        </FloatLabel>

                        <Button 
                            @click="openBreakdownModal(index)" 
                            v-tooltip.left="'Add Breakdown of Total Inputted'" 
                            icon="pi pi-plus" 
                            rounded 
                            outlined 
                            severity="help" 
                            size="small"
                        />
                    </div>
                    <div class="w-full flex flex-col justify-start items-start">
                        <span class="text-sm font-medium">Breakdown/s:</span>
                        <div class="w-full flex justify-start items-start gap-2 text-xs ml-2">
                            <ul class="list-disc ml-5 mt-1 text-xs font-light">
                                <li v-for="(breakdown, bIndex) in ind.breakdowns" :key="bIndex" class="flex items-center gap-2">
                                    <span>{{ breakdown.name }}: {{ breakdown.value }}</span>
                                    <Button 
                                        @click="removeBreakdown(index, bIndex)" 
                                        icon="pi pi-times" 
                                        text 
                                        rounded 
                                        severity="danger" 
                                        size="small"
                                        class="!w-4 !h-4"
                                    />
                                </li>
                                <li v-if="ind.breakdowns.length === 0" class="text-gray-400 italic">
                                    No breakdowns added yet
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </Panel>

        <Button @click="save" label="Create Report" class="!bg-[#5A686A] !border-none"/>

    </div>

    <Dialog v-model:visible="addBreakdownModal.show" modal header="Add Breakdown" :style="{ width: '400px' }">
        <div class="w-full flex flex-col justify-start items-start gap-4 p-4">
            <FloatLabel variant="on" class="w-full">
                <InputText v-model="addBreakdownModal.breakdown.name" class="w-full"/>
                <label class="text-sm">Breakdown Name (Male,Female,etc)</label>
            </FloatLabel>
            <FloatLabel variant="on" class="w-full">
                <InputNumber v-model="addBreakdownModal.breakdown.value" class="w-full"/>
                <label class="text-sm">Breakdown Value</label>
            </FloatLabel>
            <div class="w-full flex gap-2">
                <Button @click="pushBreakdown" label="Add" class="!bg-[#5A686A] !border-none" size="small"/>
                <Button @click="closeBreakdownModal" label="Cancel" outlined severity="secondary" size="small"/>
            </div>
        </div>
    </Dialog>

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
    const indicators = ref([])

    const report = ref({})
    

    onMounted(() => {
        handledBarangays.value = auth.teams?.flatMap(team => team.barangays) || [];
        getActiveIndicators()
    })

    const getActiveIndicators = () => {
        axios.get('indicator/active', {
            params: {}
        })
        .then((response) => {
            indicators.value = response.data
            indicators.value = indicators.value.map(ind => {
                ind.value = 0;
                ind.breakdowns = []
                return ind;
            });
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
        if (!report.value.start || !report.value.end || !report.value.barangay_id) {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Please fill in all report details', life: 3000 });
            return;
        }

        axios.post('report/create', {
            values: indicators.value,
            created_by: auth.user.id,
            start: report.value.start,
            end: report.value.end,
            barangay_id: report.value.barangay_id ?? null
        })
        .then((response) => {
            toast.add({ severity: 'success', summary: 'Report Submitted', detail: response.data, life: 3000 });
            router.push('/reports')
        })
        .catch((error) => {
            toast.add({ severity: 'error', summary: 'Error', detail: error.response?.data?.message, life: 3000 });
        })
        .finally(() => {

        })
    }

    const openBreakdownModal = (index) => {
        addBreakdownModal.value.index = index
        addBreakdownModal.value.show = true
    }

    const closeBreakdownModal = () => {
        addBreakdownModal.value.breakdown = { name: '', value: null }
        addBreakdownModal.value.show = false
        addBreakdownModal.value.index = null
    }

    const pushBreakdown = () => {
        if (addBreakdownModal.value.breakdown.name !== '' && addBreakdownModal.value.breakdown.value !== null) {
            // Push to the breakdowns array (FIXED: was pushing to indicator object directly)
            indicators.value[addBreakdownModal.value.index].breakdowns.push({
                name: addBreakdownModal.value.breakdown.name,
                value: addBreakdownModal.value.breakdown.value
            })
            
            // Reset modal
            addBreakdownModal.value.breakdown = { name: '', value: null }
            addBreakdownModal.value.show = false
            addBreakdownModal.value.index = null
            
            toast.add({ severity: 'success', summary: 'Success', detail: 'Breakdown added', life: 3000 });
        }
        else {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Breakdown Name and Value is required.', life: 3000 });
        }
    }

    const removeBreakdown = (indicatorIndex, breakdownIndex) => {
        indicators.value[indicatorIndex].breakdowns.splice(breakdownIndex, 1)
        toast.add({ severity: 'info', summary: 'Removed', detail: 'Breakdown removed', life: 3000 });
    }

</script>