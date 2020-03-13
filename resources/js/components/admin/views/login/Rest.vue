<template>
    <div>
        <div class="title forget-wrap-title">
            <a>
                <span>{{ config.title }}  </span><span class="subtitle">{{ config.subheading }}</span>
            </a>
        </div>
        <validation-observer ref="form">
            <b-form class="forget-form">
                <validation-provider ref="email" name="登录邮箱" rules="required|email" v-slot="{ errors }">
                    <b-form-input type="email" :disabled="disabled" v-model="form.email" placeholder="登录邮箱" trim/>
                    <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                </validation-provider>

                <validation-provider name="验证码" rules="required|alpha_dash" v-slot="{ errors }">
                    <b-row>
                        <b-col cols="7">
                            <b-form-input type="text" :disabled="disabled" v-model="form.code" placeholder="验证码" trim/>
                            <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                        </b-col>
                        <b-col cols="5">
                            <b-button block variant="outline-success" @click="countDown" :disabled="!canClick">
                                {{ content }}
                            </b-button>
                        </b-col>
                    </b-row>
                </validation-provider>

                <validation-provider name="重置密码" rules="required|min:6|max:32" v-slot="{ errors }"
                                     vid="confirmation">
                    <b-form-input type="password" :disabled="disabled" v-model="form.password" placeholder="重置密码" trim/>
                    <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                </validation-provider>

                <validation-provider name="确认密码" rules="required|confirmed:confirmation" v-slot="{ errors }">
                    <b-form-input type="password" :disabled="disabled" v-model="form.confirmation" placeholder="确认密码" trim/>
                    <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                </validation-provider>

                <b-row class="remember-box">
                    <b-col>
                        <b-button type="button" block @click="wrapSwitch()" variant="outline-primary">
                            返回
                        </b-button>
                    </b-col>
                    <b-col class="text-right">
                        <b-button @click="onSubmit" block variant="outline-primary" :disabled="disabled">
                            <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                            提交
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </validation-observer>
    </div>
</template>

<script>
    import config from '../../config/config';
    import {sendMail, resetPasswordByMail} from '../../api/login'

    export default {
        name: "Rest",
        data() {
            return {
                config: config,
                disabled: false,
                content: '获取验证码',
                totalTime: 60,
                canClick: true,
                form: {
                    email: null,
                    code: null,
                    password: null,
                    confirmation: null,
                }
            }
        },
        methods: {
            wrapSwitch() {
                this.$parent.switchLeft = !this.$parent.switchLeft;
                this.$parent.switchRight = !this.$parent.switchRight;
                setTimeout(() => {
                    this.$parent.notForget = true
                }, 300)
            },
            countDown() {
                this.$refs.email.validate().then(res => {
                    if (res.valid) {
                        if (!this.canClick) return;
                        this.sendMail();
                        this.canClick = false;
                        this.content = this.totalTime + 's后重发';
                        let clock = window.setInterval(() => {
                            this.totalTime--;
                            this.content = this.totalTime + 's后重发';
                            if (this.totalTime < 0) {
                                window.clearInterval(clock);
                                this.content = '重发验证码';
                                this.totalTime = 60;
                                this.canClick = true;
                            }
                        }, 1000);
                    }
                })
            },
            sendMail() {
                sendMail(this.form.email).then(res => {
                    this.$toast.success('邮件已发送，请确认查收。', 'Success');
                }).catch(error => {
                    this.canClick = true;
                    this.$toast.error(error.response.data.message, 'Error');
                })
            },
            onSubmit: function () {
                this.$refs.form.validate().then(valid => {
                    if (valid) {
                        this.disabled = true;
                        resetPasswordByMail(this.form).then(res => {
                            this.$toast.success('密码重置成功', 'Success');
                            this.wrapSwitch();
                            this.disabled = false;
                        }).catch(error => {
                            console.log(error.response.data.message);
                            this.disabled = false;
                            this.$toast.error(error.response.data.message, 'Error');
                        })
                    }
                });
            },
        }
    }
</script>

<style scoped>

</style>
