import { defineStore } from 'pinia';
import axios from '@/utils/axios';

export const useSubProgramStore = defineStore('subProgram', {
  state: () => ({
    subPrograms: [],
  }),

  actions: {
    async fetchSubPrograms() {
      try{
        const subPrograms = await axios.get('/sub-program/selection',{params:{}})
        this.subPrograms = subPrograms.data
      }
      catch(error){
        console.error('Error fetching sub programs', error);
        throw error;
      }
    },
  },
});