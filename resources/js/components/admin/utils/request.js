import axios from 'axios/index'
import storage from "./storage";
import alert from './alert';
import router from '../router/routers'

const service = axios.create({
    timeout: 50000
});

service.interceptors.request.use(
    config => {
        // do something before request is sent
        const token = storage.get('token')||storage.sessionGet('token');
        if (token) {
            config.headers['Accept'] = 'application/json';
            config.headers['Authorization'] = 'Bearer ' + token;
        }
        return config
    },
    error => {
        console.log(error) // for debug
        Promise.reject(error)
    }
);

// 报错统一拦截
service.interceptors.response.use(
    response => {
        return response
    },
    error => {
        if (error.response && error.response.status) {
            let msg = error.response.data.message;
            switch (error.response.status) {
                case 401:
                    msg = '登录已失效，请重新登录。';
                    storage.remove('user-info') && storage.sessionRemove('user-info');
                    storage.remove('token') && storage.sessionRemove('token');
                    router.push({path: '/login'});
                    alert.error(msg);
                    break;
                case 404:
                    msg = '您要操作的对象不存在。';
                    alert.error(msg);
                    break;
                case 500:
                    alert.error('系统出错：' + msg);
                    break;
                default:
                    alert.error(msg);
            }
        }
        return Promise.reject(error)
    }
);

export default service
