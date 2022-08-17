import request from '@admin/utils/request';

/**
 * 权限api接口集合
 * @method getPermissionList 获取权限列表
 */
export function permissionApi() {
    return {
        getPermissionList: () => {
            return request({
                url: '/admin/permissions/list',
                method: 'get',
            })
        },
        getPermissions: (query: any) => {
            return request({
                url: '/admin/permissions',
                method: 'get',
                params: query
            })
        }
    };
}
