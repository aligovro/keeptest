import './bootstrap';
import axios from './axios';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

const vuetify = createVuetify({
    theme: {
        defaultTheme: 'light',
    },
    icons: {
        defaultSet: 'mdi',
    },
});

window.axios = axios;

const app = createApp(App);
app.use(router);
app.use(vuetify);
app.mount('#app');
