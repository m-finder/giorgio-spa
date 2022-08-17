<template>
  <div class="system-edit-role-container">
    <el-dialog title="修改角色" v-model="isShowDialog" width="769px" @close="closeDialog">
      <el-form :model="ruleForm" :rules="rules" ref="ruleFormRef" size="default" label-width="90px">
        <el-row :gutter="35">
          <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="12" class="mb20">
            <el-form-item label="角色名称" prop="name">
              <el-input v-model="ruleForm.name" placeholder="请输入角色名称" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="角色描述" prop="brief">
              <el-input v-model="ruleForm.brief" type="textarea" placeholder="请输入角色描述" maxlength="150"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <template #footer>
				<span class="dialog-footer">
					<el-button @click="onCancel" size="default">取 消</el-button>
					<el-button type="primary" v-loading="loading" @click="onSubmit" size="default">修 改</el-button>
				</span>
      </template>
    </el-dialog>
  </div>
</template>

<script lang="ts">
import {reactive, toRefs, defineComponent, getCurrentInstance} from 'vue';
import {roleApi} from "@admin/api/system/role";

// 定义接口来定义对象的类型
interface ruleFormState {
  id: number | undefined;
  name: string | undefined;
  brief: string | undefined;
}

const ruleForm: ruleFormState = {
  id: undefined,
  name: undefined,
  brief: undefined
}

export default defineComponent({
  name: 'systemEditRole',
  emits: ['getList'],
  setup(props: any, { emit }) {
    const {proxy} = <any>getCurrentInstance();
    const state = reactive({
      isShowDialog: false,
      ruleForm,
      rules: {
        id: [{required: true, message: '角色ID不能为空', trigger: 'blur'}],
        name: [{required: true, message: '角色名称不能为空', trigger: 'blur'}],
      },
      loading: false
    });
    // 打开弹窗
    const openDialog = (row: ruleFormState) => {
      state.ruleForm = row;
      state.isShowDialog = true;
    };
    // 关闭弹窗
    const closeDialog = () => {
      resetField()
      state.isShowDialog = false;
    };
    // 取消
    const onCancel = () => {
      closeDialog();
    };
    // 编辑
    const onSubmit = () => {
      proxy.$refs.ruleFormRef.validate((valid: boolean) => {
        if (valid) {
          state.loading = true
          roleApi().updateRole(state.ruleForm).then((res?) => {
            proxy.$notify.success({
              title: '成功',
              message: '操作成功'
            })
            state.loading = false
            closeDialog();
            emit('getList')
          }).catch((err?) => {
            state.loading = false
            proxy.$notify.error({
              title: '错误',
              message: err.msg
            })
          })
        }
      })
    };

    // 重置表单
    const resetField = () => {
      proxy.$refs.ruleFormRef.resetFields();
    }
    return {
      openDialog,
      closeDialog,
      onCancel,
      onSubmit,
      ...toRefs(state),
    };
  },
});
</script>

<style scoped lang="scss">

</style>
