import { defineStore } from 'pinia'
import axios from '@/utils/axios'


export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    teams:[],
    token: localStorage.getItem('token') || '',
    isAuthenticated: false,
    isAdmin: false
  }),

  actions: {
    setUser(user) {
      this.user = user
      this.isAuthenticated = true
    },

    setTeams(teams) {
      this.teams = teams
    },

    setToken(token) {
      this.token = token
      localStorage.setItem('token', token)
    },

    clearUser() {

      this.token = ''
      this.isAuthenticated = false
      this.user = null
      this.teams = []
      localStorage.removeItem('token')
    },

    async fetchUser() {
      try {
        const response = await axios.get('user')
        this.setUser(response.data)
        this.setTeams(response.data.teams)
      }
      catch (error) {
        this.clearUser()
      }
    },

    async initAuth() {
      if (this.token) {
        await this.fetchUser()
      }
    }
  }
})

