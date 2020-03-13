import request from '../utils/request';

export function getData(query) {
    return request({
        url: '/admin-api/roles/list',
        method: 'get',
        params: query
    })
}

export function getAll() {
    return request({
        url: '/admin-api/roles/all',
        method: 'get'
    })
}

export function getAuth(id) {
    return request({
        url: '/admin-api/roles/' + id + '/auth',
        method: 'get'
    })
}

export function setAuth(id, data) {
    return request({
        url: '/admin-api/roles/' + id + '/set/auth',
        method: 'post',
        data: data
    })
}

export function getDetail(id) {
    return request({
        url: '/admin-api/roles/' + id + '/detail',
        method: 'get',
    })
}

export function deleteData(id) {
    return request({
        url: '/admin-api/roles/' + id + '/delete',
        method: 'delete',
        params: {id: id}
    })
}

export function updateData(data) {
    return request({
        url: '/admin-api/roles/' + data.id + '/update',
        method: 'put',
        data: data
    })
}

export function createData(data) {
    return request({
        url: '/admin-api/roles/create',
        method: 'post',
        data: data
    })
}
