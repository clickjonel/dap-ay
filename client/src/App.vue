<script setup>
  import { onMounted } from 'vue';
  import Toast from 'primevue/toast';
  import { useLocationStore } from '@/stores/location';
  import { useProgramStore } from '@/stores/program';
  import { useUsersStore } from './stores/users';
  import { useSubProgramStore } from '@/stores/subPrograms';

  const locationStore = useLocationStore()
  const programStore = useProgramStore()
  const usersStore = useUsersStore()
  const subProgramStore = useSubProgramStore()

  onMounted(async()=>{
     try {
      await Promise.all([
        locationStore.fetchProvinces(),
        programStore.fetchPrograms(),
        usersStore.fetchUsers(),
        subProgramStore.fetchSubPrograms(),
      ]);
    } catch (error) {
      console.error("Failed to fetch initial data:", error);
      // Show a toast notification
    }
  })
</script>

<template>

  <router-view></router-view>
  <Toast position="top-right"/>
</template>
