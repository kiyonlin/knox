import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./page/Main.vue')
    },
    {
        path: '/about',
        component: require('./components/About.vue')
    },
    {
        path: '/user',
        component: require('./page/User.vue')
    },
    {
        path: '/login',
        components: {
            welcome: require('./page/Welcome.vue')
        }
    },

    { path: '*', component: require('./components/PageNotFound.vue') }
];

export default new VueRouter({
    routes: routes,
    linkActiveClass: 'is-active'
});