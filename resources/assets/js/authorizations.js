let user = window.Auth.user;

export default {
    update(item) {
        return item.user_id === user.id;
    }
};