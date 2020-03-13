<template>
    <section class="content container-fluid">

        <b-row class="p-3">
            <b-col md="6" sm="12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">密码设置</h3>
                    </div>

                    <div class="card-body">

                        <validation-observer ref="form">
                            <b-col cols="12">
                                <validation-provider vid="original_password" name="原始密码" rules="required|min:6|max:32"
                                                     v-slot="{ errors }">
                                    <b-input-group prepend="原始密码">
                                        <b-input type="password" :disabled="disabled" v-model="form.original_password" placeholder="原始密码" trim/>
                                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </validation-provider>
                            </b-col>

                            <b-col cols="12">
                                <validation-provider vid="password" name="新设密码" rules="required|min:6|max:32"
                                                     v-slot="{ errors }">
                                    <b-input-group prepend="新设密码">
                                        <b-input type="password" :disabled="disabled" v-model="form.password" placeholder="新设密码" trim/>
                                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </validation-provider>
                            </b-col>

                            <b-col cols="12">
                                <validation-provider name="确认密码" rules="required|confirmed:password" v-slot="{ errors }">
                                    <b-input-group prepend="确认密码">
                                        <b-input type="password" :disabled="disabled" v-model="form.confirmation" placeholder="确认密码" trim/>
                                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </validation-provider>
                            </b-col>
                        </validation-observer>

                        <b-button @click="submit" variant="outline-primary" :disabled="disabled">
                            <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                            确认修改
                        </b-button>
                    </div>
                </div>
            </b-col>
        </b-row>
    </section>
</template>

<script>
    import {updatePassword} from '../../api/admin';

    const defaultForm = {
        original_password: null,
        password: null,
        confirmation: null
    };

    export default {
        name: "Password",
        data() {
            return {
                disabled: false,
                form: Object.assign({}, defaultForm)
            }
        },
        methods: {
            submit(){
                this.$refs.form.validate().then(valid => {
                    if (valid) {
                        this.disabled = true;
                        updatePassword(this.form).then(res => {
                            this.$toast.success('修改成功。', 'Success');
                            this.form = Object.assign({}, defaultForm);
                            this.disabled = false;
                            this.$refs.form.reset();
                        }).catch(error => {
                            this.$refs.form.setErrors(error.response.data.errors || {});
                            this.disabled = false;
                        })
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>
