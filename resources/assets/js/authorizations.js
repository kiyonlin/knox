let user = window.Auth.user;
let permissions = window.Auth.permissions;

export default {
    add(item){
        return permissions.includes(`${item}.add`);
    },
    view(item){
        return permissions.includes(`${item}.view`);
    },
    update(item) {
        return permissions.includes(`${item}.update`);
    },
    delete(item) {
        return permissions.includes(`${item}.delete`);
    }
};