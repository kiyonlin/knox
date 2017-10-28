import './bootstrap';
import router from './routes';

Vue.component('welcome', require('./components/Welcome.vue'));
Vue.component('home', require('./components/Home.vue'));

new Vue({
    el: '#app',
    router
});
