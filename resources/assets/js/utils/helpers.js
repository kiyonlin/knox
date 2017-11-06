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

Vue.prototype.deleteConfirm = function (callback) {
    this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    })
    .then(callback)
    .catch(() => this.$message('已取消删除'));
}