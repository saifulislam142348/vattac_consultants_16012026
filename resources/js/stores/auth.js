import { defineStore } from 'pinia';
import axios from 'axios';
import router from '../router';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        errors: [],
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role === 'admin',
    },
    actions: {
        async login(credentials) {
            try {
                this.errors = [];
                const response = await axios.post('/api/login', credentials);
                this.token = response.data.access_token;
                this.user = response.data.user;

                localStorage.setItem('token', this.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;

                router.push(this.user.role === 'admin' ? '/admin/dashboard' : '/portal');
            } catch (error) {
                this.errors = error.response?.data?.errors || ['Login failed'];
            }
        },
        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (e) {
                console.error(e);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                router.push('/login');
            }
        },
        async fetchUser() {
            if (!this.token) return;
            try {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                const response = await axios.get('/api/user');
                this.user = response.data;
            } catch (error) {
                this.token = null;
                localStorage.removeItem('token');
            }
        }
    },
});
