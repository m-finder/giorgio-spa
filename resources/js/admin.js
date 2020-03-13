/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

import Vue from 'vue';
import Router from 'vue-router';
import BootstrapVue from 'bootstrap-vue'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style
import storage from "./components/admin/utils/storage";
import store from './components/admin/store';
import SvgVue from 'svg-vue';
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import _import from './components/admin/router/_import';
import Layout from './components/admin/views/layout/Layout'
import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import  validate from './components/admin/utils/validate'

// vee-validate Register it globally
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
NProgress.configure({showSpinner: false});
Vue.use(BootstrapVue);
Vue.use(SvgVue);

// VueIziToast
let defaultAlertOptions = {
    position: 'topCenter',
    timeout: 10000,
};
Vue.use(VueIziToast, defaultAlertOptions);

import App from './components/admin/App';
import getPageTitle from './components/admin/utils/get-page-title';
import router, {baseRouters} from './components/admin/router/routers';
import {getAdminAuth} from './components/admin/api/admin';

function saveElements(elements) {
    store.dispatch('GenerateElements', elements.map(item => item.code))
}

function filterAsyncRouter(asyncRouterMap) {
    return asyncRouterMap.filter(route => {
        try {
            route.component = route.component == 'Layout' ? Layout : _import(route.component)
        } catch (e) {
            route.component = _import('/error/404')
        }
        if (route.children && route.children.length) route.children = filterAsyncRouter(route.children);
        return true
    })
}

function getAsyncRouter(token, to, next) {
    getAdminAuth().then(res => {
        let asyncRouter = filterAsyncRouter(res.data.menus);
        let asyncElements = res.data.elements;
        addRouters(asyncRouter, to, next)
        saveElements(asyncElements)
    })
}

function addRouters(asyncRouter, to, next) {
    store.dispatch('GenerateRoutes', asyncRouter).then(() => {
        // 404只能放在异步路由，否则造成刷新404
        asyncRouter.push({path: '*', redirect: '/error'});
        // 添加动态路由 解决重复添加问题
        router.match = new Router({
            linkActiveClass: 'open',
            linkExactActiveClass: 'active',
            scrollBehavior: () => ({y: 0}),
            routes: baseRouters
        }).match;
        router.addRoutes(asyncRouter);
        // hack方法 确保addRoutes已完成 ,set the replace: true so the navigation will not leave a history record
        next({...to, replace: true});
    });
}

router.beforeEach(async (to, from, next) => {
    document.title = getPageTitle(to.meta.title);
    NProgress.start();
    if (storage.get('token')) {
        if (to.path === '/login') {
            next('/')
        } else {
            if (store.getters.addRouters.length == 0) {
                getAsyncRouter(storage.get('token'), to, next);
            } else {
                next();
            }
            NProgress.done()
        }
    } else {
        to.path === '/login' ? next() : next(`/login?redirect=${to.path}`);
        NProgress.done();
    }
});

router.afterEach(() => {
    NProgress.done()
});

// https://cn.vuejs.org/v2/api/index.html#productionTip
Vue.config.productionTip = false;

// 资源权限检查
Vue.directive('has', {
    inserted: function (el, binding) {
        !Vue.prototype.$_has(binding.value) && el.parentNode.removeChild(el);
    }
});

Vue.prototype.$_has = function (value) {
    let elements = store.getters.elements;
    return elements.includes(value);
};

const app = new Vue({
    el: '#app',
    store,
    components: {App},
    router,
});
