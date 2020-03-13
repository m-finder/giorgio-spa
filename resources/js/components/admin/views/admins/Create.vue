<template>
    <validation-observer ref="form">
        <b-modal centered :title="title" v-model="show" @hidden="resetModal">
            <validation-provider vid="name" name="用户名称" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="用户名称">
                        <b-input :disabled="disabled" type="text" v-model="form.name" placeholder="用户名称" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="email" name="登录邮箱" rules="required|email" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="登录邮箱">
                        <b-input :disabled="disabled" type="email" v-model="form.email" placeholder="登录邮箱" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="password" name="登录密码" rules="required|min:6|max:32" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="登录密码">
                        <b-input :disabled="disabled" type="password" v-model="form.password" placeholder="登录密码" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider name="确认密码" rules="required|confirmed:password" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="确认密码">
                        <b-input :disabled="disabled" type="password" v-model="form.confirmation" placeholder="确认密码" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="role_id" name="所属角色" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="所属角色">
                        <b-form-select :disabled="disabled" v-model="form.role_id">
                            <template v-slot:first>
                                <option :value="null" disabled>-- 请选择所属角色进行绑定 --</option>
                            </template>
                            <template v-for="(role, i) in roles">
                                <option :value="role.id">{{role.alias}}</option>
                            </template>
                        </b-form-select>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <template slot="modal-footer" class="w-100 modal-footer">
                <b-button variant="primary" :disabled="disabled" size="sm" @click="resetModal">取消</b-button>
                <b-button v-has="'admin:add'" :disabled="disabled" variant="danger" size="sm" @click="submitCreate">
                    <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                    确认
                </b-button>
            </template>
        </b-modal>
    </validation-observer>
</template>

<script>
    import {createData} from "../../api/admin";
    import {getAll} from "../../api/role";

    const defaultForm = {
        name: null,
        email: null,
        password: null,
        role_id: null,
        confirmation: null,
    };
    export default {
        name: "AdminCreate",
        props: {
            'is-create': {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: '添加操作'
            }
        },
        data() {
            return {
                form: Object.assign({}, defaultForm),
                show: this.isCreate,
                disabled: false,
                roles: [],
            }
        },
        watch: {
            isCreate(value) {
                this.show = value;
                this.getRole();
            }
        },
        methods: {
            getRole() {
                getAll().then(res => {
                    this.roles = res.data;
                })
            },
            submitCreate() {
                this.disabled = true;
                this.$refs.form.validate().then(valid => {
                    if (!valid) {
                        this.disabled = false;
                        return false;
                    }
                    createData(this.form).then(res => {
                        this.$toast.success('添加成功。', 'Success');
                        this.$parent.getList();
                        this.resetModal()
                    }).catch(error => {
                        this.disabled = false;
                        this.$refs.form.setErrors(error.response.data.errors || {});
                    })
                })
            },
            resetModal() {
                this.form = Object.assign({}, defaultForm);
                this.$nextTick(() => {
                    this.$refs.form.reset();
                });
                this.disabled = this.show = this.$parent.isCreate = false;
            }
        }
    }
</script>

<style scoped>

</style>
