<template>


  <div class="system-user-container">
    <el-card shadow="hover">
      <el-form ref="ruleFormRef" :rules="rules" :model="ruleForm" size="default" label-width="120px">
        <el-row :gutter="35">
          <el-col :offset="4" :span="12" class="mb20">
            <el-form-item label="原密码" prop="origin_password">
              <el-input v-model.trim="ruleForm.origin_password" show-password clearable/>
            </el-form-item>
          </el-col>
          <el-col :offset="4" :span="12" class="mb20">
            <el-form-item label="新密码" prop="password">
              <el-input v-model.trim="ruleForm.password" show-password clearable/>
            </el-form-item>
          </el-col>
          <el-col :offset="4" :span="12" class="mb20">
            <el-form-item label="新密码确认" prop="confirm_password">
              <el-input v-model.trim="ruleForm.confirm_password" show-password clearable/>
            </el-form-item>
          </el-col>
          <el-col :offset="4" :span="12" class="mb20" style="text-align: center;">
              <el-button type="primary" @click="updateData">修改完成</el-button>
          </el-col>
        </el-row>
      </el-form>

    </el-card>

  </div>
</template>

<script lang="ts">
import {toRefs, reactive, defineComponent, getCurrentInstance} from 'vue';
import {passwordApi} from "@admin/api/system/password";
import Cookies from 'js-cookie';

// 定义接口来定义对象的类型
interface ruleFormState {
  id: number | undefined;
  origin_password: string | undefined;
  confirm_password: string | undefined;
  password: string | undefined;
}


const ruleForm: ruleFormState = {
  id: undefined,
  origin_password: undefined,
  confirm_password: undefined,
  password: undefined
}

export default defineComponent({
  name: 'updatePassword',
  setup() {
    const {proxy} = <any>getCurrentInstance();

    const state = reactive({
      ruleForm,
      rules: {
        origin_password: [{required: true, message: '原密码不能为空', trigger: 'blur'}],
        confirm_password: [{required: true, message: '新密码不能为空', trigger: 'blur'}],
        password: [{required: true, message: '新密码确认不能为空', trigger: 'blur'}]
      },
    });

    const updateData = () => {
      proxy.$refs['ruleFormRef'].validate(valid => {
        if (valid) {
          state.ruleForm.id = Cookies.get('userId')
          passwordApi().updatePassword(state.ruleForm).then(res => {
            proxy.$notify.success({
              title: '成功',
              message: '更新成功，下次登陆生效'
            })
          }).catch(err => {
            proxy.$notify.error({
              title: '失败',
              message: err.msg
            })
          })
        }
      })
    }

    return {
      updateData,
      ...toRefs(state),
    };
  },
});
</script>

<style>
.app-container {
  min-height: 90vh;
}

.password-btn {
  margin-left: 50px;
  width: 500px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
