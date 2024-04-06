import { computed, ref } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/site/pages/Login.vue';
import Dashboard from '@/site/pages/Dashboard.vue';
import ForgotPassword from '@/site/pages/ForgotPassword.vue';
import ResetPassword from '@/site/pages/ResetPassword.vue';

const token = ref(localStorage.getItem('token'));
const Authenticated = computed(() => token.value !== null && token.value != '');

const routes = [
  {
    meta: {
      title: 'Dashboard'
    },
    path: "/",
    name: "dashboard",
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    meta: {
      title: 'Login'
    },
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    meta: {
      title: 'Forgot Password'
    },
    path: '/forgot-password',
    name: 'forgot.password',
    component: ForgotPassword,
    meta: { requiresAuth: false },
  },
  {
    meta: {
      title: 'Reset Password'
    },
    path: '/reset-password',
    name: 'reset.password',
    component: ResetPassword,
    meta: { requiresAuth: false },
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    //
    return { top: 0 };
  },
})

router.beforeEach((to, from, next) => {
  const requiresAuth = to.meta.requiresAuth;
  const authenticated = Authenticated.value;


 if (!requiresAuth && authenticated && (to.name == 'login' || to.name == 'forgot.password' || to.name == 'reset.password')) {
    next({ name: 'dashboard' });
  }else if(requiresAuth && !authenticated && to.name == 'dashboard'){
    next({name:'login'})
  }else{
    next();
  }
})

export default router;