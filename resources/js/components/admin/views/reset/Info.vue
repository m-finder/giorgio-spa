<template>
    <section class="content container-fluid">

        <b-row class="p-3">
            <b-col md="6" sm="12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">资料设置</h3>
                    </div>

                    <div class="card-body">
                        <validation-observer ref="form">
                            <b-row>
                                <b-col cols="12">
                                    <b-col cols="12">
                                        <img class="avatar" alt="" :src="getAvatar()"/>
                                    </b-col>
                                    <b-col cols="12" class="mt-3">
                                        <b-button @click="toggle" variant="outline-success">上传头像</b-button>
                                    </b-col>
                                </b-col>
                            </b-row>

                            <b-col cols="12" class="mt-3">
                                <validation-provider vid="name" name="用户名称" rules="required" v-slot="{ errors }">
                                    <b-input-group prepend="用户名称">
                                        <b-input type="text" :disabled="disabled" v-model="form.name" placeholder="用户名称" trim/>
                                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </validation-provider>
                            </b-col>

                            <b-col cols="12" class="mt-3">
                                <validation-provider vid="name" name="登录邮箱" rules="required" v-slot="{ errors }">
                                    <b-input-group prepend="登录邮箱">
                                        <b-input :disabled="disabled" v-model="form.email" disabled="disabled" readonly="readonly"
                                                 placeholder="登录邮箱" trim/>
                                        <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </validation-provider>
                            </b-col>
                        </validation-observer>

                        <b-button @click="submit" variant="outline-primary" :disabled="disabled">
                            <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                            确认修改
                        </b-button>

                        <avatar-uploader url="/admin-api/admins/avatar/upload" img-format="jpg" img-bgc="#fff"
                                         v-model="show" field="avatar" @crop-success="cropSuccess"
                                         @crop-upload-success="cropUploadSuccess" @crop-upload-fail="cropUploadFail"
                                         :no-rotate="false" :headers="headers"/>
                    </div>
                </div>
            </b-col>
        </b-row>
    </section>
</template>

<script>
    import storage from "../../utils/storage";
    import uploader from 'vue-image-crop-upload';
    import {updateInfo, getDetailByAuth} from '../../api/admin';

    const defaultForm = {
        avatar: '/images/avatar.png',
        name: null,
    };

    const token = document.head.querySelector('meta[name="csrf-token"]');

    export default {
        name: "Info",
        components: {
            'avatar-uploader': uploader
        },
        data() {
            return {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + storage.get('token') || storage.sessionGet('token'),
                    'X-CSRF-TOKEN': token.content || null,
                },
                show: false,
                form: Object.assign({}, defaultForm),
                disabled: false,
            }
        },
        methods: {
            submit() {
                this.$refs.form.validate().then(valid => {
                    if (valid) {
                        this.disabled = true;
                        updateInfo(this.form).then(res => {
                            this.$toast.success('修改成功。', 'Success');
                            this.disabled = false;
                            this.$refs.form.reset();
                            this.storageSave(this.form)
                        }).catch(error => {
                            this.$refs.form.setErrors(error.response.data.errors || {});
                            this.disabled = false;
                        })
                    }
                })
            },
            storageSave(data) {
                storage.get('user-info') ? storage.set({'user-info': data}) : storage.sessionSet({'user-info': data})
            },
            toggle() {
                let {show} = this;
                this.show = !show;
            },
            getAvatar() {
                return this.form.avatar || '/images/avatar.jpg';
            },
            cropSuccess(data, field, key) {
                this.form.avatar = data;
                console.log('-------- 剪裁成功 --------');
            },
            cropUploadSuccess(data, field, key) {
                console.log('-------- 上传成功 --------');
                this.form.avatar = data
            },
            cropUploadFail(status, field, key) {
                console.log('-------- 上传失败 --------');
                console.log(status);
                console.log('field: ' + field);
                console.log('key: ' + key);
            },
            getDetail() {
                getDetailByAuth().then(res => {
                    this.form = res.data;
                })
            }
        },
        created() {
            this.getDetail()
        }
    }
</script>

<style lang="scss" scoped>
    .avatar {
        width: 200px;
        height: 200px;
        border: 1px solid #dcdcdc;
        border-radius: 15px;
    }
</style>
