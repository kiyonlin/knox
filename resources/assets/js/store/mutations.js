import * as M from './mutation-consts';

export default {
    [M.DO_SOMETHING] (state) {
        state.paths = [];
    },

    [M.TOGGLE_MENU] (state) {
        state.isMenuActive = ! state.isMenuActive;
    }
}