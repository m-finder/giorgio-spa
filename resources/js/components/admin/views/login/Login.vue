<template>
    <div>
        <div class="logo">
            <img src="/favicon.ico" alt="">
            <div class="title">
                <a>
                    <span>{{ config.title }}  </span><span class="subtitle">{{ config.subheading }}</span>
                </a>
            </div>
        </div>

        <validation-observer ref="form">
            <b-form class="login-form">
                <validation-provider name="登录邮箱" rules="required|email" v-slot="{ errors }">
                    <b-form-input type="email" :disabled="disabled" v-model="form.email" placeholder="登录邮箱" trim/>
                    <b-form-invalid-feedback >{{ errors[0] }}</b-form-invalid-feedback>
                </validation-provider>

                <validation-provider name="登录密码" rules="required|min:6|max:32" v-slot="{ errors }">
                    <b-form-input type="password" :disabled="disabled" v-model="form.password" placeholder="登录密码" trim/>
                    <b-form-invalid-feedback >{{ errors[0] }}</b-form-invalid-feedback>
                </validation-provider>

                <b-button @click="onSubmit" block variant="outline-primary" :disabled="disabled">
                    <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                    登录
                </b-button>

                <b-row class="remember-box">
                    <b-col>
                        <b-form-checkbox v-model="form.status">
                            记住我
                        </b-form-checkbox>
                    </b-col>
                    <b-col class="text-right">
                        <a class="forget" @click="wrapSwitch()">忘记密码？</a>
                    </b-col>
                </b-row>
            </b-form>
        </validation-observer>
    </div>
</template>

<script>
    import {login} from '../../api/login';
    import storage from '../../utils/storage';
    import config from '../../config/config';

    export default {
        name: "Login",
        data() {
            return {
                config: config,
                disabled: false,
                form: {
                    email: null,
                    password: null,
                    status: true
                },
                redirect: null,
                otherQuery: {}
            }
        },
        watch: {
            $route: {
                handler: function (route) {
                    const query = route.query;
                    if (query) {
                        this.redirect = query.redirect;
                        this.otherQuery = this.getOtherQuery(query)
                    }
                },
                immediate: true
            }
        },
        methods: {
            getOtherQuery(query) {
                return Object.keys(query).reduce((acc, cur) => {
                    if (cur !== 'redirect') {
                        acc[cur] = query[cur]
                    }
                    return acc
                }, {})
            },
            wrapSwitch() {
                this.$parent.switchLeft = !this.$parent.switchLeft;
                this.$parent.switchRight = !this.$parent.switchRight;
                setTimeout(() => {
                    this.$parent.notForget = false
                }, 300)
            },
            onSubmit: function () {
                this.$refs.form.validate().then(valid => {
                    if (valid){
                        this.disabled = true;
                        login(this.form).then(res => {
                            this.disabled = false;
                            this.$toast.success('欢迎回来', 'Success');
                            let data = {'user-info': res.data, 'token': res.data.api_token};
                            this.form.status === true ? storage.set(data) : (storage.remove(), storage.sessionSet(data));
                            this.$router.push({path: this.redirect || '/'})
                        }).catch(error => {
                            this.disabled = false;
                            this.$toast.error(error.response.data.message, 'Error');
                        })
                    }
                })
            },
        }
    }
</script>

<style scoped>

</style>
