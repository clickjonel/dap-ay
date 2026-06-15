<script setup>
    import Main from '@/layouts/main.vue';
    import { ref } from 'vue';

    defineOptions({
        layout:Main
    })

    const selectedUser = ref(null)
    const selectedBarangay = ref(null)
    const props = defineProps({
        users: Array,
        barangays: Array
    })

    const team = ref({
        name: '',
        mov_link: '',
        pk_kit: null,
        members: [],
        barangays: []
    })

    function handleSelectHRHChange(){
        console.log(selectedUser.value)
    }

    function addMember(){
        const newTeam = {
            user_id: null,
            member_name: '',
            role: '',
            position: '',
            oriented: null
        }

        team.value.members.push(newTeam)
    }

</script>

<template>

    <div class="w-full h-full flex flex-col justify-start items-start p-2">
        <section class="w-full flex flex-col justify-start items-start gap-2 p-2">
            <span class="text-sm uppercase font-semibold uppercase text-gray-700 border-b border-gray-400 w-full">Team Details</span>
            <div class="w-full grid grid-cols-3 gap-4 text-xs py-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Team Name</label>
                    <input type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">MOV Link</label>
                    <input type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Issued PK Kit?</label>
                    <select class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                        <option :value="null">Select</option>
                        <option :value="true">Yes, PK Kit was issued</option>
                        <option :value="false">No, No PK Kit issued</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="w-full flex flex-col justify-start items-start gap-2 p-2">
            <div class="w-full flex justify-between items-center border-b border-gray-400">
                <span class="text-sm uppercase font-semibold uppercase text-gray-700">Team Members</span>
                <button @click="addMember" class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors hover:bg-white cursor-pointer">
                    Add Member
                </button>
            </div>

            <div class="w-full flex flex-col justify-start items-start gap-4 text-xs py-2">
                <div v-for="member in team.members" class="w-full grid grid-cols-12 gap-4">

                    <!-- Select HRH — wider since label is long -->
                    <div class="col-span-3 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Select HRH(Select if DOH Deployed)</label>
                        <select v-model="member.user_id" @change="handleSelectHRHChange()" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                            <option :value="null">Select</option>
                            <option v-for="user in props.users" :key="user.id" :value="user">{{ user.name }}</option>
                        </select>
                    </div>

                    <!-- Member Name -->
                    <div class="col-span-3 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Member Name</label>
                        <input type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                    </div>

                    <!-- Select Role -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Role</label>
                        <select class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                            <option value="">Select</option>
                            <option value="DOH Deployed">DOH Deployed</option>
                            <option value="Non Government Organization">Non Government Organization</option>
                            <option value="Barangay LGU">Barangay LGU</option>
                            <option value="Local Government Unit">Local Government Unit</option>
                            <option value="Government Agency">Government Agency</option>
                        </select>
                    </div>

                    <!-- Member Position -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Position</label>
                        <input type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                    </div>

                    <!-- Oriented -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Oriented?</label>
                        <select class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                            <option :value="null">Select</option>
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                    </div>

                </div>
            </div>

        </section>

        <section class="w-full flex flex-col justify-start items-start gap-2 p-2">
            <div class="w-full flex justify-between items-center border-b border-gray-400">
                <span class="text-sm uppercase font-semibold uppercase text-gray-700">Handled Barangays</span>
                <button class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors hover:bg-white cursor-pointer">
                    Add Barangay
                </button>
            </div>
            <div class="w-full grid grid-cols-3 gap-4 text-xs py-2">
                <!-- <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Team Name</label>
                    <input type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">MOV Link</label>
                    <input type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Issued PK Kit?</label>
                    <select class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                        <option value="Preparation">Select</option>
                        <option :value="true">Yes, PK Kit was issued</option>
                        <option :value="false">No, No PK Kit issued</option>
                    </select>
                </div> -->
            </div>
        </section>

        <div class="w-full flex justify-end items-center gap-3">
            <button class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors">
                Discard Changes
            </button>
            <button class="px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition-colors">
                Save Team
            </button>
        </div>

    </div>

</template>