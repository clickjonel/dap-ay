<script setup>
    import Main from '@/layouts/main.vue';
    import { Icon } from '@iconify/vue';
    import { onMounted, ref, computed } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { useToast } from 'primevue';

    defineOptions({ layout: Main })

    const toast = useToast()
    const props = defineProps({
        provinces: Array,
        barangay: Object,
        indicators: Array,
        programs: Array,
        program_indicators: Array
    })

    const form = ref({
        ...props.barangay,
        geography: {
            longitude: props.barangay.geography?.longitude,
            latitude:  props.barangay.geography?.latitude,
            is_gida:   props.barangay.geography?.is_gida,
        },
        population: {
            total_population: props.barangay.population?.total_population,
            target_population: props.barangay.population?.target_population,
            total_puroks: props.barangay.population?.total_puroks,
            target_puroks: props.barangay.population?.target_puroks,
            total_households: props.barangay.population?.total_households,
            target_households: props.barangay.population?.target_households,
        },
        pk_profile: {
            pk_status: props.barangay.pk_profile?.pk_status,
            pk_site:   props.barangay.pk_profile?.pk_site,
        },
        indicators:        [...props.indicators],
        program_indicators:        [...props.program_indicators],
        priority_programs: [...(props.barangay.priority_programs ?? [])]
    })

    
    onMounted(()=>{
        console.log(props.barangay)
    })
    
    const usedProgramIds = computed(() =>
        form.value.priority_programs.map(pp => pp.program_id)
    )

    const availablePrograms = computed(() =>
        props.programs.filter(p => !usedProgramIds.value.includes(p.id))
    )

    function addProgram() {
        form.value.priority_programs.push({
            id:         null,
            program_id: null,
            program:    null,
            target:     null,
            order:      form.value.priority_programs.length + 1,
        })
    }

    function removeProgram(index) {
        form.value.priority_programs.splice(index, 1)
        form.value.priority_programs.forEach((pp, i) => pp.order = i + 1)
    }

    function handleSave() {
        const payload = {
            name:      form.value.name,
            psgc_code: form.value.psgc_code,
            mov_link:  form.value.mov_link,

            geography: {
                longitude: form.value.geography.longitude,
                latitude:  form.value.geography.latitude,
                is_gida:   form.value.geography.is_gida,
            },

            population: {
                total_population: form.value.population?.total_population,
                target_population: form.value.population?.target_population,
                total_puroks: form.value.population?.total_puroks,
                target_puroks: form.value.population?.target_puroks,
                total_households: form.value.population?.total_households,
                target_households: form.value.population?.target_households,
            },

            pk_profile: {
                pk_status: form.value.pk_profile.pk_status,
                pk_site:   form.value.pk_profile.pk_site,
            },

            indicators: form.value.indicators.map(ind => ({
                org_indicator_id: ind.id,
                value:            ind.value,
            })),

            priority_programs: form.value.priority_programs.map((pp, index) => ({
                id:         pp.id,
                program_id: pp.program_id,
                target:     pp.target,
                order:      index + 1,
            })),
            indicator_targets: form.value.program_indicators.map(ind => ({
                program_indicator_id: ind.id,
                program_id:           ind.program_id,
                target:               ind.target,
                served:               ind.served,
            })),
        }

        router.put(`/barangays/${props.barangay.id}`, payload, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary:  'Barangay Updated',
                    detail:   'Barangay has been updated successfully.',
                    life:     2000,
                })
                //router.visit('/barangays')
            },
            onError:   (e) => {
                console.log(e)
                toast.add({
                    severity: 'error',
                    summary:  'Error',
                    detail:   'Please check the form and try again.',
                    life:     3000,
                })
            },
        })

    }


</script>

<template>
    <div class="w-full flex flex-col bg-white">

        <!-- Page Header -->
        <div class="w-full px-4 md:px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div>
                <h1 class="text-sm font-semibold text-gray-800 tracking-tight">{{ form.name }}</h1>
                <p class="text-xs text-gray-400 mt-0.5">Barangay Profile Management</p>
            </div>
            <button @click="handleSave" class="px-3 md:px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition-colors">
                Save Changes
            </button>
        </div>

        <!-- Content -->
        <div class="w-full flex flex-col divide-y divide-gray-100">

            <!-- Barangay Details -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-gray-800"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">Barangay</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Basic Information</p>
                </div>
                <div class="flex-1 px-4 md:px-6 py-4 md:py-5 bg-white">
                    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5 text-xs">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">PSGC Code</label>
                            <input v-model="form.psgc_code" type="text"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="e.g. 030101001">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Barangay Name</label>
                            <input v-model="form.name" type="text"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="Barangay name">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">MOV Link</label>
                            <input v-model="form.mov_link" type="text"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geography -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-emerald-500"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">Geography</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Location & Classification</p>
                </div>
                <div class="flex-1 px-4 md:px-6 py-4 md:py-5 bg-white">
                    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5 text-xs">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Longitude</label>
                            <input v-model="form.geography.longitude" type="text"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Latitude</label>
                            <input v-model="form.geography.latitude" type="text"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">GIDA Area?</label>
                            <select v-model="form.geography.is_gida"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                                <option :value="true">Yes — GIDA</option>
                                <option :value="false">No — Non-GIDA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Population -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-emerald-500"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">Population</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Total and Target Populations</p>
                </div>
                <div class="w-full flex flex-col gap-2 px-4 md:px-6 py-4 md:py-5 bg-white">
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 text-xs">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Total Population</label>
                            <input v-model="form.population.total_population" type="number"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Target Population</label>
                            <input v-model="form.population.target_population" type="number"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                    </div>
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 text-xs">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Total Puroks</label>
                            <input v-model="form.population.total_puroks" type="number"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Target Puroks</label>
                            <input v-model="form.population.target_puroks" type="number"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                    </div>
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 text-xs">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Total Households</label>
                            <input v-model="form.population.total_households" type="number"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Target Households</label>
                            <input v-model="form.population.target_households" type="number"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="0.000000">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PK Profile -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-violet-500"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">PK Profile</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Purokalusugan</p>
                </div>
                <div class="flex-1 px-4 md:px-6 py-4 md:py-5 bg-white">
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 text-xs">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">PK Status</label>
                            <select v-model="form.pk_profile.pk_status"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                                <option value="Preparation">Preparation</option>
                                <option value="Implementing with MOVs">Implementing with MOVs</option>
                                <option value="Implementing without MOVs">Implementing without MOVs</option>
                                <option value="Processing of MOVs">Processing of MOVs</option>
                                <option value="Monitoring Implementation">Monitoring Implementation</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">PK Site?</label>
                            <select v-model="form.pk_profile.pk_site"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                                <option :value="true">Yes — PK Site</option>
                                <option :value="false">No — Not a PK Site</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizational Indicators -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-amber-400"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">Org. Indicators</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Organizational Data</p>
                    <p class="text-[11px] text-gray-300 pl-3 mt-2">{{ form.indicators.length }} indicators</p>
                </div>
                <div class="flex-1 px-4 md:px-6 py-4 md:py-5 bg-white">
                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-5 text-xs">
                        <div v-for="ind in form.indicators" :key="ind.id" class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">{{ ind.name }}</label>
                            <input v-model="ind.value" type="text"
                                class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                placeholder="—">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Programs -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-rose-500"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">Priority Programs</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Barangay Priority Order</p>
                    <p class="text-[11px] text-gray-300 pl-3 mt-2">{{ form.priority_programs.length }} programs</p>
                </div>
                <div class="flex-1 px-4 md:px-6 py-4 md:py-5 bg-white overflow-x-auto">

                    <div class="w-full flex justify-end items-center mb-4">
                        <button @click="addProgram" :disabled="!availablePrograms.length"
                            class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                            + Add Priority Program
                        </button>
                    </div>

                    <div class="min-w-[500px]">
                        <!-- Header -->
                        <div class="grid grid-cols-12 text-[10px] font-semibold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2 mb-2">
                            <span class="col-span-1 text-center">#</span>
                            <span class="col-span-5 pl-3">Program</span>
                            <span class="col-span-4">Target / Baseline</span>
                            <span class="col-span-2 text-right">Action</span>
                        </div>

                        <!-- Rows -->
                        <div class="flex flex-col divide-y divide-gray-50">
                            <div v-for="(pp, index) in form.priority_programs" :key="index"
                                class="grid grid-cols-12 items-center gap-3 py-3 text-xs">
                                <div class="col-span-1 flex justify-center">
                                    <div class="size-6 bg-rose-50 border border-rose-200 flex items-center justify-center text-[10px] font-bold text-rose-400">
                                        {{ index + 1 }}
                                    </div>
                                </div>
                                <div class="col-span-5 pl-3">
                                    <template v-if="pp.id === null">
                                        <select v-model="pp.program_id"
                                            class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                                            <option :value="null">Select Program</option>
                                            <option v-for="prog in props.programs" :key="prog.id" :value="prog.id">{{ prog.name }}</option>
                                        </select>
                                    </template>
                                    <template v-else>
                                        <span class="font-semibold text-sky-500 tracking-tight">{{ pp.program.name }}</span>
                                    </template>
                                </div>
                                <div class="col-span-4">
                                    <input v-model="pp.target" type="text"
                                        class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                        placeholder="—">
                                </div>
                                <div class="col-span-2 flex justify-end items-center">
                                    <button @click="removeProgram(index)"
                                        class="p-1.5 text-gray-300 hover:text-red-400 hover:bg-red-50 border border-transparent hover:border-red-200 transition-all"
                                        title="Remove program">
                                        <Icon icon="mdi:trash-can-outline" class="text-base" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Indicator Targets -->
            <div class="w-full flex flex-col md:flex-row">
                <div class="md:w-56 shrink-0 md:border-r border-gray-100 px-4 md:px-6 py-4 md:py-5 bg-gray-50 md:bg-white border-b md:border-b-0">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-1 h-4 bg-rose-500"></div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-gray-700">Indicator Targets</span>
                    </div>
                    <p class="text-[11px] text-gray-400 pl-3">Barangay Target Per Indicator</p>
                </div>
                <div class="flex-1 px-4 md:px-6 py-4 md:py-5 bg-white overflow-x-auto">
                    <div class="min-w-[500px]">
                        <!-- Header -->
                        <div class="grid grid-cols-12 text-[10px] font-semibold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2 mb-2">
                            <span class="col-span-1 text-center">#</span>
                            <span class="col-span-5 pl-3">Indicator</span>
                            <span class="col-span-3">Target</span>
                            <span class="col-span-3">Served</span>
                        </div>
                        <!-- Rows -->
                        <div class="flex flex-col divide-y divide-gray-50">
                            <div v-for="(ind, index) in form.program_indicators" :key="index"
                                class="grid grid-cols-12 items-center gap-3 py-3 text-xs">
                                <div class="col-span-1 flex justify-center">
                                    <div class="size-6 bg-rose-50 border border-rose-200 flex items-center justify-center text-[10px] font-bold text-rose-400">
                                        {{ index + 1 }}
                                    </div>
                                </div>
                                <div class="col-span-5 pl-3">
                                    <span class="font-medium text-sky-500 tracking-tight text-[10px]">{{ ind.name }}</span>
                                </div>
                                <div class="col-span-3">
                                    <input v-model="ind.target" type="number"
                                        class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                        placeholder="—">
                                </div>
                                <div class="col-span-3">
                                    <input v-model="ind.served" type="number"
                                        class="w-full border border-gray-200 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-300"
                                        placeholder="—">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="w-full px-4 md:px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end items-center gap-3">
            <button @click="router.visit('/barangays')" class="px-3 md:px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors">
                Discard Changes
            </button>
            <button @click="handleSave" class="px-3 md:px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition-colors">
                Save Changes
            </button>
        </div>

    </div>
</template>