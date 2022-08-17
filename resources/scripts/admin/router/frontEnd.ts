import {RouteRecordRaw} from 'vue-router';
import {storeToRefs} from 'pinia';
import {formatTwoStageRoutes, formatFlatteningRoutes, router} from '@admin/router/index';
import {dynamicRoutes, notFoundAndNoPower} from '@admin/router/route';
import pinia from '@admin/stores/index';
import {Session} from '@admin/utils/storage';
import {useUserInfo} from '@admin/stores/userInfo';
import {useTagsViewRoutes} from '@admin/stores/tagsViewRoutes';
import {useRoutesList} from '@admin/stores/routesList';
import {NextLoading} from '@admin/utils/loading';

// 前端控制路由

/**
 * 前端控制路由：初始化方法，防止刷新时路由丢失
 * @method  NextLoading 界面 loading 动画开始执行
 * @method useUserInfo(pinia).setUserInfos() 触发初始化用户信息 pinia
 * @method setAddRoute 添加动态路由
 * @method setFilterMenuAndCacheTagsViewRoutes 设置递归过滤有权限的路由到 vuex routesList 中（已处理成多级嵌套路由）及缓存多级嵌套数组处理后的一维数组
 */
export async function initFrontEndControlRoutes() {
    // 界面 loading 动画开始执行
    // @ts-ignore
    if (window.nextLoading === undefined) NextLoading.start();
    // 无 token 停止执行下一步
    if (!Session.get('token')) return false;
    // 触发初始化用户信息 pinia
    // https://gitee.com/lyt-top/vue-next-admin/issues/I5F1HP
    await useUserInfo(pinia).setUserInfos();
    // 添加动态路由
    await setAddRoute();
    // 设置递归过滤有权限的路由到 vuex routesList 中（已处理成多级嵌套路由）及缓存多级嵌套数组处理后的一维数组
    await setFilterMenuAndCacheTagsViewRoutes();
}

/**
 * 添加动态路由
 * @method router.addRoute
 * @description 此处循环为 dynamicRoutes（@admin/router/route）第一个顶级 children 的路由一维数组，非多级嵌套
 * @link 参考：https://next.router.vuejs.org/zh/api/#addroute
 */
export async function setAddRoute() {
    await setFilterRouteEnd().forEach((route: RouteRecordRaw) => {
        router.addRoute(route);
    });
}

/**
 * 删除/重置路由
 * @method router.removeRoute
 * @description 此处循环为 dynamicRoutes（@admin/router/route）第一个顶级 children 的路由一维数组，非多级嵌套
 * @link 参考：https://next.router.vuejs.org/zh/api/#push
 */
export async function frontEndsResetRoute() {
    await setFilterRouteEnd().forEach((route: RouteRecordRaw) => {
        const routeName: any = route.name;
        router.hasRoute(routeName) && router.removeRoute(routeName);
    });
}

/**
 * 获取有当前用户权限标识的路由数组，进行对原路由的替换
 * @description 替换 dynamicRoutes（@admin/router/route）第一个顶级 children 的路由
 * @returns 返回替换后的路由数组
 */
export function setFilterRouteEnd() {
    let filterRouteEnd: any = formatTwoStageRoutes(formatFlatteningRoutes(dynamicRoutes));
    filterRouteEnd[0].children = [...setFilterRoute(filterRouteEnd[0].children), ...notFoundAndNoPower];
    return filterRouteEnd;
}

/**
 * 获取全部路由数组（未处理成多级嵌套路由）
 * @description 这里主要用于动态路由的添加，router.addRoute
 * @link 参考：https://next.router.vuejs.org/zh/api/#addroute
 * @param children dynamicRoutes（@admin/router/route）第一个顶级 children 的下路由集合
 * @returns 返回全部路由数组
 */
export function setFilterRoute(children: any) {
    let filterRoute: any = [];
    children.forEach((route: any) => {
        filterRoute.push({...route});
    });
    return filterRoute;
}

/**
 * 缓存多级嵌套数组处理后的一维数组
 * @description 用于 tagsView、菜单搜索中：未过滤隐藏的(isHide)
 */
export function setCacheTagsViewRoutes() {
    // 获取有权限的路由，否则 tagsView、菜单搜索中无权限的路由也将显示
    const stores = useUserInfo(pinia);
    const storesTagsView = useTagsViewRoutes(pinia);
    const {userInfos} = storeToRefs(stores);
    let rolesRoutes = setFilterHasMenu(dynamicRoutes, userInfos.value.authList);
    // 添加到 pinia setTagsViewRoutes 中
    storesTagsView.setTagsViewRoutes(formatTwoStageRoutes(formatFlatteningRoutes(rolesRoutes))[0].children);
}

/**
 * 设置递归过滤有权限的路由到 vuex routesList 中（已处理成多级嵌套路由）及缓存多级嵌套数组处理后的一维数组
 * @description 用于左侧菜单、横向菜单的显示
 * @description 用于 tagsView、菜单搜索中：未过滤隐藏的(isHide)
 */
export function setFilterMenuAndCacheTagsViewRoutes() {
    const stores = useUserInfo(pinia);
    const storesRoutesList = useRoutesList(pinia);
    const {userInfos} = storeToRefs(stores);
    storesRoutesList.setRoutesList(setFilterHasMenu(dynamicRoutes[0].children, userInfos.value.authList));
    setCacheTagsViewRoutes();
}

/**
 * 判断路由 `meta.roles` 中是否包含当前登录用户权限字段
 * @param roles 用户权限标识，在 userInfos（用户信息）的 roles（登录页登录时缓存到浏览器）数组
 * @param route 当前循环时的路由项
 * @returns 返回对比后有权限的路由项
 */
export function hasRoles(roles: any, route: any) {
    if (route.meta && route.meta.roles) return roles.some((role: any) => route.meta.roles.includes(role));
    else return true;
}

/**
 * 根据后台接口匹配拥有权限
 * @param routes
 * @param permissions
 */
export function setFilterHasMenu(routes: any, permissions: any) {
    const menu: any = [];
    routes.forEach((route: any) => {
        const item = {...route};
        if(permissions === '*'){
            if (item.children) item.children = setFilterHasMenu(item.children, permissions);
            menu.push(item)
        }else if (hasAuth(item, permissions)) {
            if (item.children) item.children = setFilterHasMenu(item.children, permissions);
            if (item.children && item.children.length > 0) {
                if (item.children.some(hasIsHide) !== true) {
                    item.meta.isHide = true
                }
            }
            if (item.children && item.children.length === 0) {
                return
            } else {
                menu.push(item)
            }
        }
    });
    return menu;
}

/**
 * 判断路由是否隐藏
 * @param item
 */
export function hasIsHide(item: any) {
    return item.meta.isHide === false
}

/**
 * 判断路由权限是否存在于接口返回的权限列表
 * @param route 当前路由
 * @param auths 当前路由的权限
 */
export function hasAuth(route: any, auths: any) {
    return route.meta && route.meta.auth ?
        auths.some((auth: any) => route.meta.auth.includes(auth)) :
        true;
}