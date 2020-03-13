<template>
    <validation-observer ref="form">
        <b-modal centered :title="title" v-model="show" @hidden="resetModal">
            <div v-if="loading" class="text-center text-danger my-2">
                <b-spinner class="align-middle"/>
                <strong>Loading...</strong>
            </div>

            <div v-else>
                <validation-provider vid="name" name="角色名称" rules="required" v-slot="{ errors }">
                    <b-col cols="12">
                        <b-input-group prepend="角色名称-英">
                            <b-input type="text" :disabled="disabled" v-model="form.name" placeholder="角色名称" trim/>
                            <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                        </b-input-group>
                    </b-col>
                </validation-provider>

                <validation-provider vid="alias" name="角色别名" rules="required" v-slot="{ errors }">
                    <b-col cols="12">
                        <b-input-group prepend="角色别名-中">
                            <b-input type="text" :disabled="disabled" v-model="form.alias" placeholder="角色别名" trim/>
                            <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                        </b-input-group>
                    </b-col>
                </validation-provider>
            </div>

            <template slot="modal-footer" class="w-100 modal-footer">
                <b-button variant="primary" :disabled="disabled" size="sm" @click="resetModal">取消</b-button>
                <b-button v-has="'role:edit'" :disabled="disabled" variant="danger" size="sm" @click="submitUpdate">
                    <span v-if="submitting" class="spinner-border spinner-border-sm"/>
                    确认
                </b-button>
            </template>
        </b-modal>
    </validation-observer>
</template>

<script>
    import {getDetail, updateData} from "../../api/role";

    const defaultForm = {
        id: null,
        name: null,
        alias: null
    };
    export default {
        name: "RoleEdit",
        props: {
            id: {
                default: null
            },
            isEdit: {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: '编辑操作'
            }
        },
        data() {
            return {
                form: Object.assign({}, defaultForm),
                show: this.isEdit,
                disabled: true,
                loading: true,
                submitting: false
            }
        },
        watch: {
            isEdit(value) {
                this.show = value;
                if (value) {
                    this.disabled = this.loading = true;
                    this.getDetail();
                }
            }
        },
        methods: {
            getDetail() {
                getDetail(this.id).then(res => {
                    this.form = res.data;
                    this.disabled = this.loading = false;
                })
            },
            submitUpdate() {
                this.submitting = this.disabled = true;
                this.$refs.form.validate().then(valid => {
                    if (!valid) {
                        this.submitting = this.disabled = false;
                        return false;
                    }
                    updateData(this.form).then(res => {
                        this.$toast.success('编辑成功。', 'Success');
                        this.$parent.getList();
                        this.resetModal();
                    }).catch(error => {
                        this.submitting = this.disabled = false;
                        this.$refs.form.setErrors(error.response.data.errors || {});
                    })
                })
            },
            resetModal() {
                this.form = Object.assign({}, defaultForm);
                this.$nextTick(() => {
                    this.$refs.form.reset();
                });
                this.submitting = this.disabled = this.show = this.$parent.isEdit = false;
            }
        }
    }
</script>

<style scoped>

</style>
