<template>
    <b-container class="login-page" fluid>
        <b-container class="login-wrap">
            <b-row>
                <b-col :class="translateLeft" :sm="12" :xs="12" :md="5" :lg="5" :xl="5">
                    <!-- 登录 -->
                    <div v-if="notForget">
                        <login-form/>
                    </div>

                    <!-- 忘记密码 -->
                    <div v-else>
                        <reset-password/>
                    </div>
                </b-col>

                <b-col :class="translateRight" :sm="0" :xs="0" :md="7" :lg="7" :xl="7">
                    <div class="wallpaper"/>
                </b-col>

            </b-row>
        </b-container>
    </b-container>
</template>

<script>
    import Login from './Login'
    import Rest from './Rest'

    export default {
        name: 'Login',
        components:{
            'login-form': Login,
            'reset-password': Rest
        },
        data() {
            return {
                loading: false,
                forgetDisabled: false,
                switchLeft: false,
                notForget: true,
                forgetForm: {
                    email: null
                },
            }
        },
        computed: {
            translateLeft() {
                return {
                    'translate-left': true,
                    'login-col': true,
                    'switch-left': this.switchLeft
                }
            },
            translateRight() {
                return {
                    'translate-right': true,
                    'login-col': true,
                    'switch-right': this.switchLeft
                }
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

        }
    }
</script>

<style lang="scss" scoped>
    /*
        login.scss 在这里直接 import 的话，页面刷新时会闪烁
        全部复制到这里的话，图片地址不好处理
     */
</style>
