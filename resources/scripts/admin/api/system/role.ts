import request from '@admin/utils/request';

/**
 * 角色api接口集合
 * @method getRoles 获取角色列表
 * @method getRoleList 获取角色列表
 * @method createRole 创建角色
 * @method updateRole 编辑角色
 * @method deleteRole 删除角色
 * @method authRole   角色授权
 */
export function roleApi() {
    return {
        index: (query: any) => {
            return request({
                url: '/admin/roles',
                method: 'get',
                params: query
            })
        },
        list: (query: any) => {
            return request({
                url: '/admin/roles/list',
                method: 'get',
                params: query
            })
        },
        store: (data: Object) => {
            return request({
                url: '/admin/roles',
                method: 'post',
                data
            })
        },
        update: (data: any) => {
            return request({
                url: '/admin/roles/' + data.id,
                method: 'put',
                data
            })
        },
        destroy: (data: any) => {
            return request({
                url: '/admin/roles/' + data.id,
                method: 'delete',
            })
        },
        auth: (data: any) => {
            return request({
                url: '/admin/roles/' + data.id + '/auth',
                method: 'patch',
                data
            })
        }
    };
}
