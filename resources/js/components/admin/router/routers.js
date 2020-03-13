import Vue from 'vue';
import Router from 'vue-router';
import Layout from '../views/layout/Layout'
Vue.use(Router);

export const baseRouters = [
    {
        path: '/login',
        name: 'Login',
        hidden: true,
        component: () => import('../views/login/Index'),
    },
    {
        path: '/redirect',
        component: Layout,
        hidden: true,
        children: [
            {
                hidden: true,
                path: '/redirect/:path*',
                component: () => import('../components/redirect/Index')
            }
        ]
    },
    {
        path: '/error',
        name: 'Error',
        component: Layout,
        hidden: true,
        redirect: '/error/404',
        children: [
            {
                path: '404',
                hidden: true,
                meta: {title: '404'},
                component: () => import('../views/error/404'),
                name: '404',
            },
        ]
    },
    {
        path: '/reset',
        name: '个人设置',
        component: Layout,
        hidden: true,
        redirect: '/reset/info',
        children: [
            {
                path: 'info',
                meta: {title: '资料设置'},
                icon: 'role',
                name: 'Info',
                hidden: true,
                component: () => import('../views/reset/Info')
            },
            {
                path: 'password',
                meta: {title: '密码设置'},
                icon: 'credits',
                name: 'Password',
                hidden: true,
                component: () => import('../views/reset/Password')
            },
        ]
    },
];


// 解决路由重写
const routerPush = Router.prototype.push;
Router.prototype.push = function push(location) {
    return routerPush.call(this, location).catch(error=> error)
};

export default new Router({
    linkActiveClass: 'open',
    linkExactActiveClass: 'active',
    scrollBehavior: () => ({ y: 0 }),
    routes: baseRouters
});

