<template>
    <validation-observer ref="form">
        <b-modal centered :title="title" v-model="show" @hidden="resetModal">
            <validation-provider vid="name" name="角色名称" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="角色名称-英">
                        <b-input :disabled="disabled" type="text" v-model="form.name" placeholder="角色名称" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <validation-provider vid="alias" name="角色别名" rules="required" v-slot="{ errors }">
                <b-col cols="12">
                    <b-input-group prepend="角色别名-中">
                        <b-input :disabled="disabled" type="text" v-model="form.alias" placeholder="角色别名" trim/>
                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                    </b-input-group>
                </b-col>
            </validation-provider>

            <template slot="modal-footer" class="w-100 modal-footer">
                <b-button variant="primary" :disabled="disabled" size="sm" @click="resetModal">取消</b-button>
                <b-button v-has="'role:add'" :disabled="disabled" variant="danger" size="sm" @click="submitCreate">
                    <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                    确认
                </b-button>
            </template>
        </b-modal>
    </validation-observer>
</template>

<script>
    import {createData} from "../../api/role";

    const defaultForm = {
        name: null,
        alias: null
    };
    export default {
        name: "RoleCreate",
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
                disabled: false
            }
        },
        watch: {
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
