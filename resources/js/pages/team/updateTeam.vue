<script setup>
    import Main from '@/layouts/main.vue'
    import { router, useForm } from '@inertiajs/vue3'
    import { Icon } from '@iconify/vue'
    import { useToast } from 'primevue/usetoast'
    import InputText from 'primevue/inputtext'
    import ToggleSwitch from 'primevue/toggleswitch'

    defineOptions({ layout: Main })

    const toast = useToast()

    const props = defineProps({
        team: { type: Object, default: () => ({}) },
    })

    const form = useForm({
        name:      props.team.name      ?? '',
        is_active: props.team.is_active ?? true,
        pk_kit:    props.team.pk_kit    ?? false,
        eo_link:   props.team.eo_link   ?? '',
    })

    const submit = () => {
        form.put(`/teams/${props.team.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Saved', detail: 'Team details updated successfully.', life: 2000 })
            },
        })
    }
</script>

<template>
    <div class="h-full flex flex-col gap-4 lg:gap-5 min-h-0">

        <!-- ── Header ─────────────────────────────────────────── -->
        <div class="flex items-center gap-3 shrink-0">
            <button
                type="button"
                @click="router.visit('/teams')"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition-all shrink-0"
            >
                <Icon icon="hugeicons:arrow-left-01" class="text-sm" />
            </button>
            <div>
                <h1 class="text-base lg:text-lg font-bold text-slate-800 leading-none">{{ team.name }}</h1>
                <p class="text-xs text-slate-400 mt-1">Update team details and settings.</p>
            </div>
        </div>

        <!-- ── Body ───────────────────────────────────────────── -->
        <div class="flex-1 min-h-0 overflow-y-auto">

            <!-- Status cards — mobile only, shown above form -->
            <div class="flex gap-3 mb-4 lg:hidden">
                <div class="flex-1 bg-white rounded-xl border border-slate-200 px-4 py-3 flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full shrink-0" :class="team.is_active ? 'bg-emerald-500' : 'bg-slate-300'" />
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                        <p class="text-xs font-semibold mt-0.5" :class="team.is_active ? 'text-emerald-700' : 'text-slate-400'">
                            {{ team.is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                </div>
                <div class="flex-1 bg-white rounded-xl border border-slate-200 px-4 py-3 flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full shrink-0" :class="team.pk_kit ? 'bg-emerald-500' : 'bg-slate-300'" />
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PK Kit</p>
                        <p class="text-xs font-semibold mt-0.5" :class="team.pk_kit ? 'text-emerald-700' : 'text-slate-400'">
                            {{ team.pk_kit ? 'Received' : 'Not Received' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grid: form + sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-5">

                <!-- Main form -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5 flex flex-col gap-5">
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-sm font-bold text-slate-700">Basic Information</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Update the team name and settings.</p>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">

                        <!-- Name -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">
                                Team Name <span class="text-red-400">*</span>
                            </label>
                            <InputText
                                v-model="form.name"
                                placeholder="e.g. Health Team A"
                                class="!text-sm !py-2"
                                :class="form.errors.name ? '!border-red-400' : ''"
                            />
                            <p v-if="form.errors.name" class="text-[11px] text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <!-- Toggles -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <div>
                                    <p class="text-xs font-semibold text-slate-700">Active</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Team will be available for use</p>
                                </div>
                                <ToggleSwitch v-model="form.is_active" />
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <div>
                                    <p class="text-xs font-semibold text-slate-700">PK Kit</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Team has been assigned a PK Kit</p>
                                </div>
                                <ToggleSwitch v-model="form.pk_kit" />
                            </div>
                        </div>

                        <!-- EO Link -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600">EO Link</label>
                            <InputText
                                v-model="form.eo_link"
                                placeholder="e.g. https://example.com/eo"
                                class="!text-sm !py-2"
                                :class="form.errors.eo_link ? '!border-red-400' : ''"
                            />
                            <p v-if="form.errors.eo_link" class="text-[11px] text-red-500">{{ form.errors.eo_link }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end pt-1 border-t border-slate-100">
                            <button
                                type="submit"
                                :disabled="form.processing || !form.isDirty"
                                class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 rounded-lg transition-colors"
                            >
                                <Icon v-if="form.processing" icon="hugeicons:loading-01" class="text-sm animate-spin" />
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>

                <!-- Sidebar — desktop only -->
                <div class="hidden lg:flex flex-col gap-3">
                    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" :class="team.is_active ? 'bg-emerald-500' : 'bg-slate-300'" />
                            <span class="text-xs font-semibold" :class="team.is_active ? 'text-emerald-700' : 'text-slate-400'">
                                {{ team.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">PK Kit</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" :class="team.pk_kit ? 'bg-emerald-500' : 'bg-slate-300'" />
                            <span class="text-xs font-semibold" :class="team.pk_kit ? 'text-emerald-700' : 'text-slate-400'">
                                {{ team.pk_kit ? 'Received' : 'Not Received' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>