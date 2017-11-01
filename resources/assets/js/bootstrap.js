import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import FormModel from './utils/Form';
import 'element-ui/lib/theme-chalk/index.css';
import 'element-ui/lib/theme-chalk/display.css';
import ElementUi from 'element-ui';

window.Vue = Vue;
window.axios = axios;
window.Form = FormModel;

Vue.use(VueRouter);
Vue.use(ElementUi);

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.interceptors.response.use((res) =>{
    return res;
  }, (error) => {
    if (error.response.status === 401) {
        console.log('unauthorized, logging out ...');
        router.replace('/login');
    }
    return Promise.reject(error.response);
  });

// import Echo from 'laravel-echo'
//
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6006'
// });

import authorizations from './authorizations';

Vue.prototype.signedIn = window.Auth.signedIn;
Vue.prototype.menus = window.Auth.menus;

window.Vue.prototype.authorize = function (...params) {
    if (!window.Auth.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.Auth.user);
};
