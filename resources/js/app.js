import './bootstrap';
import '../css/app.css';
import '@fontsource/montserrat';
import '@fontsource/open-sans';

import { createApp } from 'vue';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';

const app = createApp(App);

app.use(createPinia());
app.use(Toast);

import { useAuthStore } from './stores/auth';
import axios from 'axios';

const authStore = useAuthStore();
if (authStore.token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;
}
app.use(router);

app.mount('#app');
