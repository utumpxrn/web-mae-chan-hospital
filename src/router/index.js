import { createRouter, createWebHistory } from 'vue-router';
import store from '../store/index';

import HomeView from '../views/HomeView.vue';
import NameView from '../views/NameView.vue';
import PeopleView from '../views/PeopleView.vue';
import TimeView from '../views/TimeView.vue';
import LineChart from '../components/LineChart.vue';
import BarChart from '../components/BarChart.vue';
import LoginView from '../views/LoginView.vue';
import RegisterView from '../views/RegisterView.vue';
import FirstView from '../views/FirstView.vue';

const routes = [
  {
    path: '/',
    name: 'first',
    component: FirstView,
  },
  {
    path: '/dashboard',
    name: 'home',
    component: HomeView,
    meta: { requiresAuth: true },
  },
  {
    path: '/name',
    name: 'name',
    component: NameView,
    meta: { requiresAuth: true },
  },
  {
    path: '/people',
    name: 'people',
    component: PeopleView,
    meta: { requiresAuth: true },
  },
  {
    path: '/time',
    name: 'time',
    component: TimeView,
    meta: { requiresAuth: true },
  },
  {
    path: '/line',
    name: 'line',
    component: LineChart,
    meta: { requiresAuth: true },
  },
  {
    path: '/bar',
    name: 'bar',
    component: BarChart,
    meta: { requiresAuth: true },
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach(({ matched, name }, from, next) => {
  const { isAuthenticated } = store.getters;
  if (isAuthenticated && (name === 'login' || name === 'register')) {
    next({ name: 'home' });
  } else if (matched.some((record) => record.meta.requiresAuth) && !isAuthenticated) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
