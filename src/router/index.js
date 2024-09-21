import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import AboutView from '../views/AboutView.vue';
import PeopleView from '../views/PeopleView.vue';
import TimeView from '../views/TimeView.vue';
import LineChart from '../components/LineChart.vue';
import BarChart from '../components/BarChart.vue';
import LoginView from '../views/LoginView.vue';
import RegisterView from '../views/RegisterView.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/about',
    name: 'about',
    component: AboutView,
  },
  {
    path: '/people',
    name: 'people',
    component: PeopleView,
  },
  {
    path: '/time',
    name: 'time',
    component: TimeView,
  },
  {
    path: '/line',
    name: 'line',
    component: LineChart,
  },
  {
    path: '/bar',
    name: 'bar',
    component: BarChart,
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

export default router;
