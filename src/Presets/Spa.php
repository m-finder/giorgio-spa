<?php

namespace GiorgioSpa\Presets;

use Illuminate\Support\Arr;
use Laravel\Ui\Presets\Preset;

class Spa extends Preset
{
    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install($dev = true)
    {
        static::updatePackages($dev);
        static::updateWebpackConfiguration();
    }

    /**
     * Update the given package array.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        return [
                "babel-plugin-dynamic-import-node" => "^2.3.0",
                "bootstrap-vue" => "^2.4.0",
                "bootstrap-vue-treeview" => "^1.0.8",
                "echarts" => "^4.6.0",
                "laravel-mix-svg-vue" => "^0.2.6",
                "nprogress" => "^0.2.0",
                "vee-validate" => "^3.2.4",
                "vue-echarts" => "^4.1.0",
                "vue-image-crop-upload" => "^2.5.0",
                "vue-izitoast" => "^1.2.1",
                "vue-router" => "^3.1.5",
                "vuex" => "^3.1.2",
                "webpack-bundle-analyzer" => "^3.6.0"
            ] + Arr::except($packages, []);
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/spa-stubs/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__ . '/spa-stubs/webpack.config.js', base_path('webpack.config.js'));
        copy(__DIR__ . '/spa-stubs/.babelrc', base_path('.babelrc'));
    }

}
