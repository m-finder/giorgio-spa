import request from '../utils/request';

export function getData(query) {
    return request({
        url: '/admin-api/elements/list',
        method: 'get',
        params: query
    })
}

export function getAll() {
    return request({
        url: '/admin-api/elements/all',
        method: 'get'
    })
}

export function getDetail(id) {
    return request({
        url: '/admin-api/elements/' + id + '/detail',
        method: 'get',
    })
}

export function deleteData(id) {
    return request({
        url: '/admin-api/elements/' + id + '/delete',
        method: 'delete',
        params: {id: id}
    })
}

export function updateData(data) {
    return request({
        url: '/admin-api/elements/' + data.id + '/update',
        method: 'put',
        data: data
    })
}

export function createData(data) {
    return request({
        url: '/admin-api/elements/create',
        method: 'post',
        data: data
    })
}
