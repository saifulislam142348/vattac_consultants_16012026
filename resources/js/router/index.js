import { createRouter, createWebHistory } from 'vue-router';

import Login from '../pages/Login.vue';
import SubmissionForm from '../pages/SubmissionForm.vue';
import AdminDashboard from '../pages/AdminDashboard.vue';
import Home from '../pages/Home.vue';

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/login',
        component: Login,
    },
    {
        path: '/portal',
        component: SubmissionForm,
    },
    {
        path: '/admin/dashboard',
        component: AdminDashboard,
    },
    {
        path: '/about',
        component: { template: '<h1>About Us</h1>' },
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
