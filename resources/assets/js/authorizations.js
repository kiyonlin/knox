let user = window.Auth.user;
let permissions = window.Auth.permissions;

export default {
    check(action, module, id) {
        let permission = `${module}.${action}`;
        if (id) {
            permission + `.${id}`;
        }
        return permissions.includes(permission);
    }
};