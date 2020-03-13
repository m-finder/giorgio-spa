import request from '../utils/request';

export function getData(query) {
    return request({
        url: '/admin-api/logs/list',
        method: 'get',
        params: query
    })
}

