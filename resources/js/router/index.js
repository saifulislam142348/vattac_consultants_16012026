import { createRouter, createWebHistory } from 'vue-router';

import Login from '../pages/Login.vue';
import ClientDashboard from '../pages/ClientDashboard.vue';
import AdminDashboard from '../pages/AdminDashboard.vue';
import Home from '../pages/Home.vue';
import About from '../pages/About.vue';
import Services from '../pages/Services.vue';
import Contact from '../pages/Contact.vue';
import ClientProfile from '../pages/ClientProfile.vue';
import AdminTeam from '../pages/admin/AdminTeam.vue'; // Import new page

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/about',
        component: About,
    },
    {
        path: '/services',
        component: Services,
    },
    {
        path: '/contact',
        component: Contact,
    },
    {
        path: '/login',
        component: Login,
    },
    {
        path: '/portal',
        component: ClientDashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/profile',
        component: ClientProfile,
        meta: { requiresAuth: true }
    },
    {
        path: '/admin',
        redirect: '/admin/dashboard',
    },
    {
        path: '/admin/dashboard',
        component: AdminDashboard,
        meta: { requiresAuth: true, adminOnly: true }
    },
    {
        path: '/admin/reports',
        component: () => import('../pages/admin/AdminReports.vue'),
        meta: { requiresAuth: true, adminOnly: true }
    },
    {
        path: '/admin/content',
        component: () => import('../pages/admin/AdminContent.vue'),
        meta: { requiresAuth: true, adminOnly: true }
    },
    {
        path: '/admin/settings',
        component: () => import('../pages/admin/AdminSettings.vue'),
        meta: { requiresAuth: true, adminOnly: true }
    },
    {
        path: '/admin/work',
        component: () => import('../pages/admin/AdminReturnWork.vue'),
        meta: { requiresAuth: true, adminOnly: true }
    },
    {
        path: '/admin/documents',
        component: () => import('../pages/admin/AdminDocuments.vue'),
        meta: { requiresAuth: true, adminOnly: true }
    },
    {
        path: '/admin/team',
        component: AdminTeam,
        meta: { requiresAuth: true, adminOnly: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        return { top: 0 }
    },
});

import { useAuthStore } from '../stores/auth';

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // Check if user is authenticated (if requiresAuth)
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return next('/login');
    }

    // Check if route is admin only
    if (to.meta.adminOnly) {
        if (!authStore.user) {
            await authStore.fetchUser(); // Ensure user data is loaded
        }
        if (authStore.user?.role !== 'admin') {
            return next('/portal'); // Redirect non-admins to portal
        }
    }

    next();
});

export default router;
