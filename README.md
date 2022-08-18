<p align="center"><img src="https://www.m-finder.com/images/avatar.jpeg"></p>
<p align="center">
<img src="https://img.shields.io/badge/Author-m--finder-red">
<img src="https://img.shields.io/badge/Laravel-9.19.0-red">
<img src="https://img.shields.io/badge/Vue-3.2.37-red">
<img src="https://img.shields.io/badge/Vite-3.0.0-red">
<a href="https://packagist.org/packages/wu/giorgio-spa"><img src="https://img.shields.io/badge/License-MIT-green" alt="License"></a>
</p>

## About Giorgio Spa
Giorgio spa is a single page admin package, based on Laravel, Vite, Vue3 typeScript, Element-plus

#### Preview
![](https://repository-images.githubusercontent.com/247018339/204baa68-7615-4332-9b9b-fd7d89204c3c)


#### Demo
未搭建，界面可参见 [[vue-next-admin]](https://gitee.com/lyt-top/vue-next-admin)

#### Account
```
URL: http://domain/admin
Account: admin
Password: abc123
```

#### Install
```
laravel new website
cd website
edit .env sql config
composer require wu/giorgio-spa
php artisan spa:install
php artisan spa:init
npm install && npm run dev
php artisan serve
```

#### 注意事项

> 只能在全新项目安装
> 
> 配置文件需要根据实际情况更改
> 
> 只在 laravel 9.19 做过验证
> 
> 如果报错 WebSocket connection to 'ws://laravel.test:5173/' failed，在 .env 添加如下两行，并根据实际情况修改：

```
VITE_KEY_PATH='.config/valet/Certificates/laravel9.test.key'
VITE_CERT_PATH='.config/valet/Certificates/laravel9.test.crt'
```

#### 鸣谢

前端框架来自 [[vue-next-admin]](https://gitee.com/lyt-top/vue-next-admin)

#### License

The Giorgio spa is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
