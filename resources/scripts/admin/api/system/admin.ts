import request from '@admin/utils/request';

export function adminApi() {
    return {
        getAdmins: (data: Object) => {
            return request({
                url: '/admin/admins',
                method: 'get',
                params: data
            })
        },
        createAdmin: (data: Object) => {
            return request({
                url: '/admin/admins',
                method: 'post',
                data
            })
        },
        updateAdmin: (data: any) => {
            return request({
                url: '/admin/admins/' + data.id,
                method: 'put',
                data
            })
        },
        deleteAdmin: (data: any) => {
            return request({
                url: '/admin/admins/' + data.id,
                method: 'delete',
            })
        },

    };
}
