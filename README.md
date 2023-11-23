<p align="center"><img src="https://m-finder.github.io/images/avatar.jpeg"></p>
<p align="center">
<img src="https://img.shields.io/badge/Author-m--finder-red">
<img src="https://img.shields.io/badge/Laravel-9.19.0-red">
<img src="https://img.shields.io/badge/Vue-3.2.37-red">
<img src="https://img.shields.io/badge/Vite-3.0.0-red">
<a href="https://packagist.org/packages/wu/giorgio-spa"><img src="https://img.shields.io/badge/License-MIT-green" alt="License"></a>
</p>

## 关于 About Giorgio Spa

Laravel Vue 单页式管理后台，适用于小型全栈式项目，快速搭建管理后台。
前端框架基于 Vite，Vue3 Typescript 和 Element-Plus。


Laravel Vue Single-Page Administration Panel, suitable for small full-stack projects, enables rapid construction of admin dashboards.
The frontend framework is built on Vite, Vue 3 TypeScript, and Element-Plus.

### 预览 Preview
![](https://repository-images.githubusercontent.com/247018339/204baa68-7615-4332-9b9b-fd7d89204c3c)


### 示例 Demo
[[ vue-next-admin ]](https://gitee.com/lyt-top/vue-next-admin)

### 账号信息 Account
```
URL: http://domain/admin
Account: admin
Password: abc123
```

### 安装 Install

#### 安装扩展包 Composer require
```
composer require wu/giorgio-spa
```

#### 发布资源文件 Publish resources
```
php artisan spa:install
```

#### 数据初始化  Data initialization
```
php artisan spa:init
```

#### 启动服务 run server
```
yarn install && npm run dev
php artisan serve
```

#### 注意事项

* 建议在新项目安装。
* It is recommended to install in a new project.
* 
* 根据实际情况修改你的配置文件。
* Modify your configuration files according to the actual situation.
* 
* 在 `config/permission` 定义权限相关的 `groups`、`methods` 和 `white_list`。
* Define permission-related `groups`, `methods`, and `white_list` in `config/permission`.
* 
* 添加 `$this->call(\GiorgioSpa\Database\Seeders\PermissionSeeder::class);` 到 `Database\Seeders\DatabaseSeeder` 的 `run` 方法。
* Add `$this->call(\GiorgioSpa\Database\Seeders\PermissionSeeder::class);` to the `run` method of `Database\Seeders\DatabaseSeeder`.
* 
* `config/permission` 定义的权限发生变更后需要执行 `php artisan db:seed` 刷新数据。
* After making changes to the permissions defined in `config/permission`, execute `php artisan db:seed` to refresh the data.
* 
* 短信验证码需要自行对接短信平台。
* The SMS verification code needs to be integrated with the SMS platform independently.
* 
* 系统设置以下菜单为演示菜单，不根据实际权限显示或隐藏，可以在 `resources/scripts/admin/router/route.ts` 中找到对应代码并移除。
* To set the following menu as a demonstration menu, irrespective of actual permissions for display or hiding, you can find the corresponding code in `resources/scripts/admin/router/route.ts` and remove it.
* 
* 移除扩展包时需要手动清理 `config` 文件夹下的文件，否则可能会报错：`Class "Laravel\Sanctum\Sanctum" not found`。
* When removing the package, manually clean the files in the `config` folder, otherwise you may encounter an error: `Class "Laravel\Sanctum\Sanctum" not found`.

> 如果报错 WebSocket connection to 'ws://laravel.test:5173/' failed，在 .env 添加如下两行，并根据实际情况修改：
> 
> If you encounter the error "WebSocket connection to 'ws://laravel.test:5173/' failed," add the following two lines to the .env file and modify them according to your actual situation:
```
VITE_KEY_PATH='.config/valet/Certificates/laravel9.test.key'
VITE_CERT_PATH='.config/valet/Certificates/laravel9.test.crt'
```

#### 鸣谢 Thanks

* [[ vue-next-admin ]](https://gitee.com/lyt-top/vue-next-admin)

#### License

The Giorgio spa is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
