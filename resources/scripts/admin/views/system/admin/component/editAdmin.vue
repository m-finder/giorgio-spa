<template>
  <div class="system-edit-user-container">
    <el-dialog title="修改" v-model="isShowDialog" width="769px" @close="closeDialog">
      <el-form :model="ruleForm" :rules="rules" ref="ruleFormRef" size="default" label-width="90px">
        <el-row :gutter="35">
          <el-col :span="24" class="mb20">
            <el-form-item label="名称">
              <el-input v-model="ruleForm.name" placeholder="请输入账户名称" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24" class="mb20">
            <el-form-item label="头像" prop="avatar">
              <upload :file="ruleForm.avatar" @handle-change="handleSelectData('avatar', $event)" />
            </el-form-item>
          </el-col>

          <el-col :span="24" class="mb20">
            <el-form-item label="角色" prop="role_ids" >
              <base-select
                  :multiple="true"
                  :data="ruleForm.role_ids"
                  :loading="selectLoading"
                  :options="roleList"
                  :size="null"
                  @remote-method="getRoleList"
                  @handle-change="handleSelectData('role_ids', $event)"
                  placeholder="选择角色"/>
            </el-form-item>
          </el-col>

          <el-col :span="24" class="mb20">
            <el-form-item label="手机号">
              <el-input v-model="ruleForm.phone" placeholder="请输入手机号" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24" class="mb20">
            <el-form-item label="邮箱">
              <el-input v-model="ruleForm.email" placeholder="请输入邮箱" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24" class="mb20">
            <el-form-item label="状态">
              <el-switch v-model="ruleForm.status" inline-prompt active-value="enabled" inactive-value="disabled" active-text="启" inactive-text="禁"></el-switch>
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
import {adminApi} from "@admin/api/system/admin";
import {roleApi} from "@admin/api/system/role";
import BaseSelect from '@admin/components/baseSelect/index.vue';
import Upload from '@admin/components/upload/index.vue';

// 定义接口来定义对象的类型
interface ruleFormState {
  id: number | undefined;
  name: string | undefined;
  phone: string | undefined;
  status: string | undefined;
  email: string | undefined;
  avatar: string | undefined;
  role_ids: Array | undefined;
}


const ruleForm: ruleFormState = {
  id: undefined,
  name: undefined,
  phone: undefined,
  status: undefined,
  email: undefined,
  avatar: undefined,
  role_ids: [],
}

export default defineComponent({
  name: 'systemEditAdmin',
  emits: ['getList'],
  components: {BaseSelect, Upload},
  setup(props: any, {emit}) {
    const {proxy} = <any>getCurrentInstance();
    const state = reactive({
      isShowDialog: false,
      ruleForm,
      rules: {
        name: [{required: true, message: '名称不能为空', trigger: 'blur'}],
        phone: [{required: true, message: '手机名称不能为空', trigger: 'blur'}],
      },
      loading: false,
      selectLoading: false,
      roleList: [],
    });

    // 打开弹窗
    const openDialog = (row: ruleFormState) => {
      state.ruleForm = row;
      state.ruleForm.role_ids = []
      if (row.roles) {
        row.roles.forEach((item) => {
          state.ruleForm.role_ids.push(item.id)
        })
      }
      state.isShowDialog = true;
      getRoleList();
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
    // 新增
    const onSubmit = () => {
      proxy.$refs.ruleFormRef.validate((valid: boolean) => {
        if (valid) {
          state.loading = true
          adminApi().updateAdmin(state.ruleForm).then((res?) => {
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

    // 获取角色列表
    const getRoleList = (query) => {
      roleApi().getRoleList({name: query, limit: 100, page: 1}).then((res?) => {
        state.roleList = res.data.data
        state.selectLoading = false
      }).catch((err?) => {
        state.roleList = []
        state.selectLoading = false
      })
    }

    // 处理下拉选择数据
    const handleSelectData = (key: string, data: any) => {
      state.ruleForm[key] = data
    }

    return {
      openDialog,
      closeDialog,
      onCancel,
      onSubmit,
      getRoleList,
      handleSelectData,
      ...toRefs(state),
    };
  },
});
</script>
