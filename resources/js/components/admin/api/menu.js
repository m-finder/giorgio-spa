import request from '../utils/request';

export function getData(query) {
    return request({
        url: '/admin-api/menus/list',
        method: 'get',
        params: query
    })
}

export function getAll() {
    return request({
        url: '/admin-api/menus/all',
        method: 'get'
    })
}
export function getAllWithElements() {
    return request({
        url: '/admin-api/menus/all/with/elements',
        method: 'get'
    })
}

export function getParents() {
    return request({
        url: '/admin-api/menus/parents',
        method: 'get'
    })
}

export function getDetail(id) {
    return request({
        url: '/admin-api/menus/' + id + '/detail',
        method: 'get',
    })
}

export function deleteData(id) {
    return request({
        url: '/admin-api/menus/' + id + '/delete',
        method: 'delete',
        params: {id: id}
    })
}

export function updateData(data) {
    return request({
        url: '/admin-api/menus/' + data.id + '/update',
        method: 'put',
        data: data
    })
}

export function createData(data) {
    return request({
        url: '/admin-api/menus/create',
        method: 'post',
        data: data
    })
}
