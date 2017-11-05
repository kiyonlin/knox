import Vue from 'vue';

/**
 * 获取表单中修改过的值
 */
Vue.prototype.getDirty = function (original, current) {
    let dirty = {};
    for (let key in original) {
        if (original[key] != current[key]) {
            dirty[key] = current[key];
        }
    }
    return dirty;
}

/**
 * 获取两个数组的差值
 */
Vue.prototype.arrayDiff = function (a, b) {
    return a.concat(b).filter(v => !a.includes(v) || !b.includes(v));
}