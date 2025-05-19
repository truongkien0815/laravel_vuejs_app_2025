
import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router.js';

import  Toast  from 'vue-toastification';
import 'vue-toastification/dist/index.css'


const app = createApp(App);
app.use(router);
// app.use(Toast);

const options = {
  // Cấu hình mặc định
  timeout: 2000,
};

app.use(Toast, options);


app.mount('#app');

