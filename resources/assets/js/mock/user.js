import Mock from 'mockjs';

Mock.mock('/profile', 'get', {
    user: {
        id: 1,
        name: '@cname'
    }
});

Mock.mock('/profile', 'post', {
    user: {
        id: 2,
        name: '@cname'
    }
});