import Vue from 'vue';
import Vuex from 'vuex';

import state from './state';
import getters from './getters';
import mutations from './mutations';
import actions from './actions';

import breadcrumb from './modules/breadcrumb';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
    modules: {
        breadcrumb
    },
    strict: debug,
    plugins: []
});