import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './../css/app.css';
import 'bootstrap-horizon/src/bootstrap-horizon.css';

import Vue from 'vue';

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import axios from 'axios';
Vue.prototype.$http = axios;

let token = localStorage.getItem('token');
if (token) {
    Vue.prototype.$http.defaults.headers.Authorization = 'bearer ' + token;
}

import store from "./store/index";

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

import router from './router/router';

// Routes


Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    router,
    store,
    axios,
    el: '#app',
});
