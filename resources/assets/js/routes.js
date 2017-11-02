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
        path: '/users',
        component: require('./page/user/User.vue')
    },
    {
        path: '/roles',
        component: require('./page/role/Role.vue')
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