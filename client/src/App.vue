<script setup>
  import { onMounted } from 'vue';
  import Toast from 'primevue/toast';
  import { useLocationStore } from '@/stores/location';
  import { useProgramStore } from '@/stores/program';
  import { useUsersStore } from './stores/users';

  const locationStore = useLocationStore()
  const programStore = useProgramStore()
  const usersStore = useUsersStore()

  onMounted(async()=>{
    await locationStore.fetchProvinces()
    await programStore.fetchPrograms()
    await usersStore.fetchUsers()
  })
</script>

<template>

  <router-view></router-view>
  <Toast position="top-right"/>
</template>
