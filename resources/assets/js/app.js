import './bootstrap';
import router from './routes';

Vue.component('welcome', require('./page/welcome/Welcome.vue'));
Vue.component('home', require('./page/home/Home.vue'));

new Vue({
    el: '#app',
    router
});
