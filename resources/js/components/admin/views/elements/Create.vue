<template>
    <validation-observer ref="form">
        <b-modal centered :title="title" v-model="show" @hidden="resetModal">

            <validation-provider vid="name" name="资源名称" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="资源名称">
                        <b-input type="text" :disabled="disabled" v-model="form.name" placeholder="请输入资源名称" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <validation-provider vid="code" name="资源编号" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="资源编号">
                        <b-input type="text" :disabled="disabled" v-model="form.code" placeholder="请输入资源编号" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <validation-provider vid="method" name="请求方法" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="请求方法">
                        <b-form-select :disabled="disabled" v-model="form.method">
                            <template v-slot:first>
                                <option :value="null" disabled>-- 请选择请求方法 --</option>
                            </template>
                            <option value="get">GET</option>
                            <option value="post">POST</option>
                            <option value="put">PUT</option>
                            <option value="delete">DELETE</option>
                        </b-form-select>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <validation-provider vid="path" name="请求路径" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="请求路径">
                        <b-input type="text" :disabled="disabled" v-model="form.path" placeholder="请输入请求路径" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <template slot="modal-footer" class="w-100 modal-footer">
                <b-button variant="primary" :disabled="disabled" size="sm" @click="resetModal">取消</b-button>
                <b-button v-has="'element:add'" :disabled="disabled" variant="danger" size="sm" @click="submitCreate">
                    <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                    确认
                </b-button>
            </template>
        </b-modal>
    </validation-observer>
</template>

<script>
    import {createData} from "../../api/element";

    const defaultForm = {
        menu_id: null,
        name: null,
        code: null,
        method: 'get',
        path: null,
    };
    export default {
        name: "ElementCreate",
        data() {
            return {
                form: Object.assign({}, defaultForm),
                show: false,
                disabled: false
            }
        },
        props: {
            'menu-id': {
                default: null
            },
            'is-create': {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: '添加操作'
            }
        },
        watch: {
            isCreate(value) {
                this.form.menu_id = this.menuId;
                this.show = value;
            }
        },
        methods: {
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
                        this.resetModal();
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
