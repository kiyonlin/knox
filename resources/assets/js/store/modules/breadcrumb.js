import * as M from '../mutation-consts';

const state = {
    paths: []
};

const mutations = {
    [M.CHANGE_PATH] (state, path) {
        if(path.data == '/') {
            state.paths = [];
        } else {
            state.paths = [path];
        }
    }
};

export default {
    state,
    mutations
}