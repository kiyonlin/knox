import Vue from 'vue';
Vue.prototype.getDirty = function (original, current) {
    let dirty = {};
    for (let key in original) {
        if(original[key] != current[key]) {
            dirty[key] = current[key];
        }
    }
    return dirty;
}