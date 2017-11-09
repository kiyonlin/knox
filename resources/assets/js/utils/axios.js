import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.interceptors.response.use((res) =>{
    return res;
  }, (error) => {
    if (error.response.status === 401) {
        console.log('unauthorized, logging out ...');
        location.href = `http://${location.host}/#/`;
    }
    if (error.response.status === 403) {
        ElementUi.Message.error('对不起，您没有该操作的权限！');
    }
    return Promise.reject(error.response);
  });