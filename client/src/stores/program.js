import { defineStore } from 'pinia';
import axios from '@/utils/axios';

export const useProgramStore = defineStore('program', {
  state: () => ({
    programs: [],
  }),

  actions: {
    async fetchPrograms() {
      try{
        const programs = await axios.get('/program/selection',{params:{}})
        this.programs = programs.data
      }
      catch(error){
        console.error('Error fetching programs', error);
        throw error;
      }
    },
  },
});