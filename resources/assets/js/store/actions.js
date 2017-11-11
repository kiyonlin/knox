import * as M from './mutation-consts';

export default {
    doSomething({ commit }, payload) {
        if(payload) {
            commit(M.DO_SOMETHING, {});
        }
    }
};