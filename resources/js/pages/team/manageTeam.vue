<script setup>
    import Main from '@/layouts/main.vue';
    import { onMounted, ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { Select, useToast } from 'primevue';

    defineOptions({
        layout:Main
    })

    const toast = useToast()
    const props = defineProps({
        users: Array,
        barangays: Array,
        team: Object
    })

    const team = ref({
        name: props.team.name,
        eo_link: props.team.eo_link,
        pk_kit: props.team.pk_kit,
        members: props.team.members.map(member => ({
            id: member.id,
            user_id: member.user?.id,
            user: member?.user ?? null,
            name: member.name,
            role: member.role,
            position: member.position,
            pk_oriented: member.pk_oriented,
        })),
        barangays: props.team.barangays.map(tb => ({
            brgy: props.barangays.find(b => b.id === tb.id) ?? null
        })),
    })

    const selectPt = {
        root:  { class: 'w-full border border-gray-400 bg-gray-50 rounded-none! h-[38px] flex items-center' },
        label: { class: 'text-xs! text-gray-700! py-0! px-3!' },
        trigger: { class: 'px-3' },
        overlay: { class: 'rounded-none! shadow-md border border-gray-200' },
        option: { class: 'text-xs! px-3! py-2!' },
        filterInput: { class: 'text-xs! rounded-none! border! border-gray-300! outline-none! px-3! py-2! w-full!' },
    }

    onMounted(()=>{
        console.log(props.team)
    })

    function handleSelectHRHChange(member) {
        if (member.user) {
            member.name = member.user.name
            member.role = 'DOH Deployed'
        } else {
            member.name = ''
            member.role = ''
        }
    }

    function addMember() {
        team.value.members.push({
            user: null,
            name: '',
            role: '',
            position: '',
            pk_oriented: null
        })
    }

    function addBarangay() {
        team.value.barangays.push({ brgy: null })
    }

    function removeMember(index) {
        team.value.members.splice(index, 1)
    }

    function removeBarangay(index) {
        team.value.barangays.splice(index, 1)
    }

    function handleSave() {
        const payload = {
            name: team.value.name,
            eo_link: team.value.eo_link,
            pk_kit: team.value.pk_kit,
            members: team.value.members.map(member => ({
                id: member.id ?? null,
                user_id: member.user?.id ?? null,
                name: member.name,
                role: member.role,
                position: member.position,
                pk_oriented: member.pk_oriented,
            })),
            barangays: team.value.barangays.filter(b => b.brgy?.id != null).map(b => b.brgy.id)
        }
        //console.log(payload)
        router.put(`/teams/${props.team.id}`, payload, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary:  'Team Updated',
                    detail:   'Team has been updated successfully.',
                    life:     2000,
                })
            },
            onError: (e) => {
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
    <div class="w-full h-full flex flex-col justify-start items-start p-2 gap-2">

        <!-- Team Details -->
        <section class="w-full flex flex-col gap-2 p-2">
            <span class="text-sm uppercase font-semibold text-gray-700 border-b border-gray-400 w-full pb-1">Team Details</span>
            <div class="w-full grid grid-cols-3 gap-4 py-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Team Name</label>
                    <input v-model="team.name" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">EO Link</label>
                    <input v-model="team.eo_link" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Issued PK Kit?</label>
                    <select v-model="team.pk_kit" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all">
                        <option :value="null">Select</option>
                        <option :value="true">Yes, PK Kit was issued</option>
                        <option :value="false">No, No PK Kit issued</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Team Members -->
        <section class="w-full flex flex-col gap-2 p-2">
            <div class="w-full flex justify-between items-center border-b border-gray-400 pb-1">
                <span class="text-sm uppercase font-semibold text-gray-700">Team Members</span>
                <button @click="addMember" class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors cursor-pointer">
                    Add Member
                </button>
            </div>

            <div class="w-full flex flex-col gap-3 py-2">
                <div v-for="(member, index) in team.members" :key="index" class="w-full grid grid-cols-12 gap-3 items-end">

                    <!-- HRH — PrimeVue Select with filter -->
                    <div class="col-span-3 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">HRH (DOH Deployed)</label>
                        <Select
                            v-model="member.user"
                            :options="props.users"
                            option-label="name"
                            :pt="selectPt"
                            placeholder="Select HRH"
                            filter
                            show-clear
                            @change="handleSelectHRHChange(member)"
                        />
                    </div>

                    <!-- Member Name -->
                    <div class="col-span-3 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Member Name</label>
                        <input v-model="member.name" type="text" class="uppercase w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all placeholder:text-gray-400">
                    </div>

                    <!-- Role -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Role</label>
                        <select v-model="member.role" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all">
                            <option value="">Select</option>
                            <option value="DOH Deployed">DOH Deployed</option>
                            <option value="Non Government Organization">Non Government Organization</option>
                            <option value="Barangay LGU">Barangay LGU</option>
                            <option value="Local Government Unit">Local Government Unit</option>
                            <option value="Government Agency">Government Agency</option>
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Position</label>
                        <input v-model="member.position" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all placeholder:text-gray-400">
                    </div>

                    <!-- Oriented -->
                    <div class="col-span-1 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Oriented?</label>
                        <select v-model="member.pk_oriented" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-xs text-gray-700 focus:bg-white transition-all">
                            <option :value="null">Select</option>
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                    </div>

                    <!-- Remove -->
                    <div class="col-span-1 flex items-end pb-0.5">
                        <button @click="removeMember(index)" class="w-full py-2 border border-red-200 text-red-500 text-xs hover:bg-red-50 transition-colors cursor-pointer">
                            Remove
                        </button>
                    </div>

                </div>
            </div>
        </section>

        <!-- Handled Barangays -->
        <section class="w-full flex flex-col gap-2 p-2">
            <div class="w-full flex justify-between items-center border-b border-gray-400 pb-1">
                <span class="text-sm uppercase font-semibold text-gray-700">Handled Barangays</span>
                <button @click="addBarangay" class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors cursor-pointer">
                    Add Barangay
                </button>
            </div>

            <div class="w-full grid grid-cols-3 gap-4 py-2">
                <div v-for="(brgy, index) in team.barangays" :key="index" class="col-span-3 flex items-end gap-3">
                    <div class="flex-1 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Select Barangay</label>
                        <Select
                            v-model="brgy.brgy"
                            :options="props.barangays"
                            :option-label="(b) => `${b.name} - ${b?.municipality?.name} - ${b?.province?.name}`"
                            :pt="selectPt"
                            placeholder="Search barangay..."
                            filter
                            show-clear
                        />
                    </div>
                    <button @click="removeBarangay(index)" class="py-2 px-3 border border-red-200 text-red-500 text-xs hover:bg-red-50 transition-colors cursor-pointer mb-0.5">
                        Remove
                    </button>
                </div>
            </div>
        </section>

        <!-- Actions -->
        <div class="w-full flex justify-end items-center gap-3 p-2">
            <button class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors cursor-pointer">
                Discard Changes
            </button>
            <button @click="handleSave" class="px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition-colors cursor-pointer">
                Save Team
            </button>
        </div>

    </div>
</template>