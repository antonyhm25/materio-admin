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
    path: '/admin/users',
    name:'admin-users',
    component: () => import('@/views/users/UserPage.vue'),
    meta: {
      auth: true,
      permission: 'users:view'
    }
  },
  {
    path: '/admin/users/create',
    name:'admin-users-create',
    component: () => import('@/views/users/UserCreate.vue'),
    meta: {
      auth: true,
      permission: 'users:create-update'
    }
  },
  {
    path: '/admin/users/:id/view',
    name:'admin-users-view',
    component: () => import('@/views/users/UserView.vue'),
    meta: {
      auth: true,
      permission: 'users:view'
    }
  },
  {
    path: '/admin/restaurants',
    name:'admin-restaurants',
    component: () => import('@/views/restaurants/RestaurantPage.vue'),
    meta: {
      auth: true,
      permission: 'restaurants:view'
    }
  },
  {
    path: '/admin/restaurants/create',
    name:'admin-restaurants-create',
    component: () => import('@/views/restaurants/RestaurantCreate.vue'),
    meta: {
      auth: true,
      permission: 'restaurants:create-update'
    }
  },
  {
    path: '/admin/restaurants/:id/view',
    name:'admin-restaurants-view',
    component: () => import('@/views/users/UserView.vue'),
    meta: {
      auth: true,
      permission: 'restaurants:view'
    }
  },
  {
    path: '/admin/mobiles',
    name:'admin-mobiles',
    component: () => import('@/views/users-mobiles/UserPage.vue'),
    meta: {
      auth: true,
      permission: 'users:view'
    }
  },
  {
    path: '/admin/mobiles/:id/view',
    name:'admin-mobiles-view',
    component: () => import('@/views/users/UserView.vue'),
    meta: {
      auth: true,
      permission: 'users:view'
    }
  },
  {
    path: '/meals',
    name:'module-meals',
    component: () => import('@/views/meals/MealPage.vue'),
    meta: {
      auth: true,
      permission: 'meals:create-update'
    }
  },
  {
    path: '/meals/create',
    name:'module-meals-create',
    component: () => import('@/views/meals/MealCreate.vue'),
    meta: {
      auth: true,
      permission: 'meals:create-update'
    }
  },
  {
    path: '/meals/:id/view',
    name:'module-meals-view',
    component: () => import('@/views/meals/MealView.vue'),
    meta: {
      auth: true,
      permission: 'meals:create-update'
    }
  },
  {
    path: '/meal-deals',
    name:'module-meal-deals',
    component: () => import('@/views/meal-deals/MealDealPage.vue'),
    meta: {
      auth: true,
      permission: 'meal-deals:create-update'
    }
  },
  {
    path: '/account/view',
    name:'account-view',
    component: () => import('@/views/auth/MyAccount.vue'),
    meta: {
      auth: true,
      permission: 'account:view'
    }
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
        const permissions = store.getters['auth/user'].permissions;
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
