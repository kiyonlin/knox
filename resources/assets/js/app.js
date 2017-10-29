import './bootstrap';
import router from './routes';

Vue.component('welcome', require('./page/Welcome.vue'));
Vue.component('home', require('./page/Home.vue'));

new Vue({
    el: '#app',
    router
});
