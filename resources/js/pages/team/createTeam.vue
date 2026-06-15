<script setup>
    import Main from '@/layouts/main.vue';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { useToast } from 'primevue';

    defineOptions({
        layout:Main
    })

    const selectedUser = ref(null)
    const selectedBarangay = ref(null)
    const toast = useToast()
    const props = defineProps({
        users: Array,
        barangays: Array
    })

    const team = ref({
        name: '',
        eo_link: '',
        pk_kit: null,
        members: [],
        barangays: []
    })

    function handleSelectHRHChange(member){
        console.log(member)
        if(member.user){
            member.member_name = member.user.name
            member.role = 'DOH Deployed'
        }
        // console.log(team.value.members)
    }

    function addMember(){
        const member = {
            user: null,
            member_name: '',
            role: '',
            position: '',
            oriented: null
        }

        team.value.members.push(member)
    }

    function addBarangay(){
        team.value.barangays.push({brgy:null})
    }

    function handleSave(){
        const payload = {
            name: team.value.name,
            eo_link: team.value.eo_link,
            pk_kit: team.value.pk_kit,
            members: team.value.members.map(member => ({
                user_id:     member.user?.id ?? null,
                name: member.member_name,
                role:        member.role,
                position:    member.position,
                pk_oriented: member.oriented,
            })),
            barangays: team.value.barangays.map(b => b.brgy.id)
        }

        router.post('/teams', payload, {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary:  'Team Created',
                    detail:   'Team has been updated successfully.',
                    life:     2000,
                })
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

    <div class="w-full h-full flex flex-col justify-start items-start p-2">
        <section class="w-full flex flex-col justify-start items-start gap-2 p-2">
            <span class="text-sm uppercase font-semibold uppercase text-gray-700 border-b border-gray-400 w-full">Team Details</span>
            <div class="w-full grid grid-cols-3 gap-4 text-xs py-2">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Team Name</label>
                    <input v-model="team.name" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">EO Link</label>
                    <input v-model="team.eo_link" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Issued PK Kit?</label>
                    <select v-model="team.pk_kit" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
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
                <div v-for="(member,index) in team.members" class="w-full grid grid-cols-12 gap-4">

                    <!-- Select HRH — wider since label is long -->
                    <div class="col-span-3 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Select HRH(Select if DOH Deployed)</label>
                        <select v-model="member.user" @change="handleSelectHRHChange(member)" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
                            <option :value="null">Select</option>
                            <option v-for="user in props.users" :key="user.id" :value="user">{{ user.name }}</option>
                        </select>
                    </div>

                    <!-- Member Name -->
                    <div class="col-span-3 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Member Name</label>
                        <input v-model="member.member_name" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                    </div>

                    <!-- Select Role -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Role</label>
                        <select v-model="member.role" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
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
                        <input v-model="member.position" type="text" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all placeholder:text-gray-400">
                    </div>

                    <!-- Oriented -->
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Oriented?</label>
                        <select v-model="member.oriented" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all">
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
                <button @click="addBarangay" class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors hover:bg-white cursor-pointer">
                    Add Barangay
                </button>
            </div>
            <div class="w-full grid grid-cols-3 gap-4 text-xs py-2">
                 <!-- Select HRH — wider since label is long -->
                 <div v-for="brgy in team.barangays" class="col-span-3 flex flex-col gap-1.5">
                    <label class="text-[10px] font-semibold uppercase tracking-widest text-gray-600">Select Barangay</label>
                    <select v-model="brgy.brgy" class="w-full border border-gray-400 bg-gray-50 outline-none px-3 py-2 text-gray-700 focus:bg-white focus:border-gray-400 transition-all tracking-widest">
                        <option :value="null">Select</option>
                        <option class="tracking-widest" v-for="brgy in props.barangays" :key="brgy.id" :value="brgy">{{ `${brgy.name} - ${brgy?.municipality.name} - ${brgy?.province.name}` }}</option>
                    </select>
                </div>
            </div>
        </section>

        <div class="w-full flex justify-end items-center gap-3">
            <button class="px-4 py-2 border border-gray-300 text-gray-600 text-xs font-medium hover:bg-gray-100 transition-colors">
                Discard Changes
            </button>
            <button @click="handleSave" class="px-4 py-2 bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition-colors">
                Save Team
            </button>
        </div>

    </div>

</template>