import request from '@admin/utils/request';

export function passwordApi() {
    return {
        /**
         * 修改密码
         * @param data
         * @returns {*}
         */
        updatePassword:(data: any)=> {
            return request({
                url: '/admin/password/' + data.id,
                method: 'put',
                data
            })
        },
        /**
         * 重置密码
         * @param data
         * @returns {*}
         */
        resetPassword:(data: any)=> {
            return request({
                url: '/admin/password/' + data.id,
                method: 'patch',
                data
            })
        }

    };
}
