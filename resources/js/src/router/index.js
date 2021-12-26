import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: 'dashboard'
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/dashboard/Dashboard.vue'),
    meta: {
      auth: true,
      permission: 'public'
    }
  },
  {
    path: '/typography',
    name: 'typography',
    component: () => import('@/views/typography/Typography.vue'),
  },
  {
    path: '/icons',
    name: 'icons',
    component: () => import('@/views/icons/Icons.vue'),
  },
  {
    path: '/cards',
    name: 'cards',
    component: () => import('@/views/cards/Card.vue'),
  },
  {
    path: '/simple-table',
    name: 'simple-table',
    component: () => import('@/views/simple-table/SimpleTable.vue'),
  },
  {
    path: '/form-layouts',
    name: 'form-layouts',
    component: () => import('@/views/form-layouts/FormLayouts.vue'),
  },
  {
    path: '/pages/account-settings',
    name: 'pages-account-settings',
    component: () => import('@/views/pages/account-settings/AccountSettings.vue'),
  },
  {
    path: '/auth/login',
    name: 'pages-login',
    component: () => import('@/views/auth/Login.vue'),
    meta: {
      layout: 'blank',
    },
    beforeEnter: (to, from, next) => {
      const isAuthenticated = store.getters['auth/check']
      if (isAuthenticated && to.name === 'pages-login') {
        next(from.path)
      } else {
        next()
      }
    }
  },
  {
    path: '/pages/register',
    name: 'pages-register',
    component: () => import('@/views/auth/Register.vue'),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/Error.vue'),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '*',
    redirect: 'error-404',
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(route => route.meta.auth)) {
    const isAuthenticated = store.getters['auth/check'];
    if (!isAuthenticated) {
      next({ name: 'pages-login' });
    } else {
      if (to.meta.permission) {
        const permissions = store.getters['auth/user'].permissions.map(e => e.permission);
        if (permissions.includes(to.meta.permission)
          || to.meta.permission === 'public') {
            next();
        } else {
          next('error-404');
        }
      } else {
        next();
      }
    }
  } else {
    next();
  }
})

export default router
