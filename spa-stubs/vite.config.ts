import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vuePlugin from '@vitejs/plugin-vue';
import fs from 'fs';
import {resolve} from 'path';
import {homedir} from 'os';

let host = loadEnv('', './', 'APP_URL')
    .APP_URL.replace('https://', '')
    .replace('http://', '');

function detectServerConfig(host: string) {
    // env 未配置密钥地址时会报错，所以给了个不需要真的存在的默认值
    let key = loadEnv('', './', 'VITE_KEY_PATH').VITE_KEY_PATH || 'mock/mock.key';
    let cert = loadEnv('', './', 'VITE_CERT_PATH').VITE_CERT_PATH || 'mock/mock.crt';
    let keyPath = resolve(homedir(), key)
    let certificatePath = resolve(homedir(), cert)

    if (!fs.existsSync(keyPath)) {
        return {}
    }

    if (!fs.existsSync(certificatePath)) {
        return {}
    }

    return {
        hmr: {host},
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    }
}

export default defineConfig({
    server: detectServerConfig(host),
    plugins: [
        laravel({
            input: ['resources/scripts/admin.ts'],
            refresh: true,
        }),
        vuePlugin({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/scripts',
            '@admin': '/resources/scripts/admin',
        },
    },
});
