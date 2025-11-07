import { defineStore } from 'pinia';
import axios from '@/utils/axios';

export const useLocationStore = defineStore('location', {
  state: () => ({
    provinces: [],
    municipalities: [],
    barangays: [],
  }),

  getters: {
    getMunicipalitiesByProvince: (state) => (provinceId) => {
      return state.municipalities[provinceId] || [];
    },
    
    getBarangaysByMunicipality: (state) => (municipalityId) => {
      return state.barangays[municipalityId] || [];
    },
  },

  actions: {
    async fetchProvinces() {
      try{
        const provinces = await axios.get('/province/selection',{params:{}})
        this.provinces = provinces.data
      }
      catch(error){
        console.error('Error fetching provinces', error);
        throw error;
      }
    },

    async fetchMunicipalities(provinceId) {
      try{
        const municipalities = await axios.get('/municipality/selection',{params:{province_id:provinceId}})
        this.municipalities = municipalities.data
        
      }
      catch(error){
        console.error('Error fetching municipalities', error);
        throw error;
      }
    },

    async fetchBarangays(municipalityId,pkSite) {
      try{
          const barangays = await axios.get('/barangay/selection',{
            params:{
              municipality_id:municipalityId,
              pk_site: pkSite
            }
          })
          this.barangays = barangays.data
        }
        catch(error){
          console.error('Error fetching barangays', error);
          throw error;
        }
    },
  },
});