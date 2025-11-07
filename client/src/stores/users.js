import { defineStore } from 'pinia';
import axios from '@/utils/axios';

export const useUsersStore = defineStore('users', {
  state: () => ({
    users: [],
  }),

  actions: {
    async fetchUsers() {
      try{
        const users = await axios.get('/user/selection',{params:{}})
        this.users = users.data
      }
      catch(error){
        console.error('Error fetching users', error);
        throw error;
      }
    },
  },
});