<template>
    <validation-observer ref="form">
        <b-modal centered :title="title" v-model="show" @hidden="resetModal">

            <validation-provider vid="parent_id" name="上级菜单" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="上级菜单">
                        <b-form-select :disabled="disabled" v-model="form.parent_id">
                            <template v-slot:first>
                                <option :value="null" disabled>-- 请选择上级菜单 --</option>
                            </template>
                            <option :value="parents.id">{{ parents.title }}</option>
                        </b-form-select>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="name" name="视图名称" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="视图名称">
                        <b-input type="text" :disabled="disabled" v-model="form.name" placeholder="请输入视图名称(vue 组件 name)"
                                 trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="title" name="菜单名称" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="菜单名称">
                        <b-input type="text" :disabled="disabled" v-model="form.title" placeholder="请输入菜单名称" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="component" name="视图路径" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="视图路径">
                        <b-input type="text" :disabled="disabled" v-model="form.component" placeholder="请输入视图文件路径"
                                 trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="path" name="跳转地址" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="跳转地址">
                        <b-input type="text" :disabled="disabled" v-model="form.path" placeholder="请输入跳转地址" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="redirect" name="重定向地址" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="重定向　">
                        <b-input type="text" :disabled="disabled" v-model="form.redirect" placeholder="请输入重定向地址" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="icon" name="图标文件名称" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="图标文件">
                        <b-input type="text" :disabled="disabled" v-model="form.icon" placeholder="请输入图标文件名称" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>


            <validation-provider vid="order_num" name="排序编号" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="排序编号">
                        <b-input type="text" :disabled="disabled" v-model="form.order_num"
                                 placeholder="请输入排序编号，从小到大正序排列" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <b-col cols="12">
                <b-col cols="6">
                    <b-form-checkbox switch value="1" :disabled="disabled" v-model="form.hidden">是否隐藏</b-form-checkbox>
                </b-col>
                <b-col cols="6">
                    <b-form-checkbox switch value="1" :disabled="disabled" v-model="form.affix">常驻标题栏</b-form-checkbox>
                </b-col>
            </b-col>

            <template slot="modal-footer" class="w-100 modal-footer">
                <b-button variant="primary" :disabled="disabled" size="sm" @click="resetModal">取消</b-button>
                <b-button v-has="'menu:add'" :disabled="disabled" variant="danger" size="sm" @click="submitCreate">
                    <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                    确认
                </b-button>
            </template>
        </b-modal>
    </validation-observer>
</template>

<script>
    import {createData} from "../../api/menu";

    const defaultForm = {
        name: null,
        title: null,
        path: null,
        component: null,
        redirect: null,
        icon: null,
        parent_id: null,
        order_num: null,
        hidden: 0,
        affix: 0
    };
    export default {
        name: "MenuCreate",
        props: {
            'is-create': {
                type: Boolean,
                default: false
            },
            'parent-nodes': {
                type: Object,
                default: {}
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
                router_id: null,
                parents: this.parentNodes,
                disabled: false,
            }
        },
        watch: {
            parentNodes(value) {
                this.parents = value;
                this.form = Object.assign({}, defaultForm);
                this.form.parent_id = this.parents.id;
            },
            isCreate(value) {
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
                        this.$parent.getAll();
                        this.resetModal()
                    }).catch(error => {
                        this.disabled = false;
                        this.$refs.form.setErrors(error.response.data.errors || {});
                    })
                });
            },
            resetModal() {
                this.form = defaultForm;
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
