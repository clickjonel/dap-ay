import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth';

import Login from '@/views/login.vue'
import Main from '@/layout/main.vue';

const routes = [
    {
      path: '/',
      name: 'Login',
      component: Login,
      meta:{
        requiresAuth:false
      }
    },
    {
      path: '/admin',
      name: 'Admin',
      component: Main,
      meta:{
        requiresAuth:true
      },
      children:[
        {
          path: '/dashboard',
          name: 'Dashboard',
          component: () => import('@/views/dashboard.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/programs',
          name: 'Programs',
          component: () => import('@/views/program/programs.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/sites',
          name: 'PK Sites',
          component: () => import('@/views/site/sites.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/teams',
          name: 'PK Teams',
          component: () => import('@/views/team/teams.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/activities',
          name: 'PK Activities',
          component: () => import('@/views/activity/activities.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/reports',
          name: 'PK Reports',
          component: () => import('@/views/report/reports.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/users',
          name: 'PK Users',
          component: () => import('@/views/user/users.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/baseline-indicators',
          name: 'PK Baseline Indicators',
          component: () => import('@/views/baselineIndicator/baselineIndicators.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/indicators',
          name: 'PK Indicators',
          component: () => import('@/views/indicator/indicators.vue'),
          meta:{
            requiresAuth:true
          }
        },
        {
          path: '/server-logs',
          name: 'Server Logs',
          component: () => import('@/views/serverLogs/serverLogs.vue'),
          meta:{
            requiresAuth:true
          }
        },
      ]
    },
    {
      path: '/program/update/:program_id',
      name: 'Update Program',
      component: () => import('@/views/program/updateProgram.vue'),
      meta:{
        requiresAuth:true
      }
    },
    {
      path: '/activity/update/:activity_id',
      name: 'Update Activity',
      component: () => import('@/views/activity/updateActivity.vue'),
      meta:{
        requiresAuth:true
      }
    },
    {
      path: '/activity/populate/:activity_id',
      name: 'Populate Activity',
      component: () => import('@/views/activity/populateActivity.vue'),
      meta:{
        requiresAuth:true
      }
    },
    {
      path: '/activity/report/:activity_id',
      name: 'View Activity Report',
      component: () => import('@/views/activity/viewReport.vue'),
      meta:{
        requiresAuth:true
      }
    },
    {
      path: '/team/create',
      name: 'Create Team',
      component: () => import('@/views/team/createTeam.vue'),
      meta:{
        requiresAuth:true
      }
    },
    {
      path: '/team/update/:team_id',
      name: 'Update Team',
      component: () => import('@/views/team/updateTeam.vue'),
      meta:{
        requiresAuth:true
      }
    },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

let isInitialized = false;

router.beforeEach(async (to, _from, next) => {
  try {
    const authStore = useAuthStore()
    if (!isInitialized) {
      await authStore.initAuth()
      isInitialized = true
    }

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

    if (requiresAuth && !authStore.isAuthenticated) {
      next({ name: 'Login' })
    }

    else if (!requiresAuth && authStore.isAuthenticated && to.name === 'Login') {
      next({ name: 'Dashboard' })
    }

    else {
      next()
    }
  } catch (error) {
    console.error('Navigation guard error:', error)
    next({ name: 'Login' }) // Fallback to login on error
  }

})

export default router
