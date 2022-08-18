import request from '@admin/utils/request';

export function adminApi() {
    return {
        index: (data: Object) => {
            return request({
                url: '/admin/admins',
                method: 'get',
                params: data
            })
        },
        store: (data: Object) => {
            return request({
                url: '/admin/admins',
                method: 'post',
                data
            })
        },
        update: (data: any) => {
            return request({
                url: '/admin/admins/' + data.id,
                method: 'put',
                data
            })
        },
        destroy: (data: any) => {
            return request({
                url: '/admin/admins/' + data.id,
                method: 'delete',
            })
        },

    };
}
