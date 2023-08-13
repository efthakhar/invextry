import { createApp } from "vue";
import { createPinia } from "pinia";
import "./plugins/axios-settings.js";
import router from "./router";
import "./assets/css/main.css";

import App from "./views/App.vue";

const app = createApp(App);
const store = createPinia();

app.use(store);
app.use(router);

app.config.globalProperties.$demoIMG = '/img/placeholder.png'

app.mount("#invextry-admin");
