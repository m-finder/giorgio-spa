# Giorgio-spa

laravel vue 单页后台

laravel8 内置 jetstream inertia-vue 版重构。

# 特别声明

1. 刚开始重构，功能不可用！！！
2. 只能在全新的 laravel 项目中使用！！！

### install

```shell
composer create-prject laravel/laravel laravel
composer require laravel/jetstream
composer require wu/giorgio-spa

php artisan jetstream:install inertia
php artisan admin:install
php artisan migrate
php artisan admin:migrate

npm install && npm run dev
```
