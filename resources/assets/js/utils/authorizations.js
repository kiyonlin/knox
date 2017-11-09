import Vue from 'vue';

let user = window.Auth.user;
let permissions = window.Auth.permissions;

Vue.prototype.signedIn = window.Auth.signedIn;
Vue.prototype.menus = window.Auth.modules;

export default {
    check(action, module, id) {
        let permission = `${module}.${action}`;
        if (id) {
            permission + `.${id}`;
        }
        return permissions.includes(permission);
    }
};
