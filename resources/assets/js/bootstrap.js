import Vue from 'vue';
import VueRouter from 'vue-router';
import FormModel from './utils/Form';
import 'element-ui/lib/theme-chalk/index.css';
import 'element-ui/lib/theme-chalk/display.css';
import ElementUi from 'element-ui';

window.Vue = Vue;
window.Form = FormModel;

Vue.use(VueRouter);
Vue.use(ElementUi);

// import Echo from 'laravel-echo'
//
// window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':6006'
// });

import './utils/axios';

import './utils/helpers';

import authorizations from './utils/authorizations';

Vue.prototype.authorize = function (action, module, id) {
    if (!window.Auth.signedIn) return false;

    return authorizations['check'](action, module, id);
};
