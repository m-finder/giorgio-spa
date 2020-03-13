import request from '../utils/request';

export function getData(query) {
    return request({
        url: '/admin-api/admins/list',
        method: 'get',
        params: query
    })
}

export function getDetail(id) {
    return request({
        url: '/admin-api/admins/' + id + '/detail',
        method: 'get',
    })
}

export function getAdminAuth() {
    return request({
        url: '/admin-api/admins/auth',
        method: 'get',
    })
}

export function deleteData(id) {
    return request({
        url: '/admin-api/admins/' + id + '/delete',
        method: 'delete',
    })
}

export function updateData(data) {
    return request({
        url: '/admin-api/admins/' + data.id + '/update',
        method: 'put',
        data: data
    })
}

export function createData(data) {
    return request({
        url: '/admin-api/admins/create',
        method: 'post',
        data: data
    })
}

export function updatePassword(data) {
    return request({
        url: '/admin-api/admins/reset/password',
        method: 'put',
        data: data
    })
}

export function getDetailByAuth() {
    return request({
        url: '/admin-api/admins/detail/by/auth',
        method: 'get',
    })
}

export function updateInfo(data) {
    return request({
        url: '/admin-api/admins/reset/info',
        method: 'put',
        data: data
    })
}

