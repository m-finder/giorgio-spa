<template>
  <div class="system-add-user-container">
    <el-dialog title="新增" v-model="isShowDialog" width="769px" @close="closeDialog">
      <el-form :model="ruleForm" :rules="rules" ref="ruleFormRef" size="default" label-width="90px">
        <el-row :gutter="35">
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="名称" prop="name">
              <el-input v-model="ruleForm.name" placeholder="请输入名称" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="24" class="mb20">
            <el-form-item label="头像" prop="avatar">
              <upload :file="ruleForm.avatar" @handle-change="handleSelectData('avatar', $event)" />
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="角色" prop="role_ids">
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
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="手机号" prop="phone">
              <el-input v-model="ruleForm.phone" placeholder="请输入手机号" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="邮箱" prop="email">
              <el-input v-model="ruleForm.email" placeholder="请输入" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="账户密码" prop="password">
              <el-input v-model="ruleForm.password" placeholder="请输入" type="password" clearable></el-input>
            </el-form-item>
          </el-col>
          <el-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" class="mb20">
            <el-form-item label="用户状态" prop="status">
              <el-switch v-model="ruleForm.status" inline-prompt active-value="enabled" inactive-value="disabled" active-text="启" inactive-text="禁"></el-switch>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <template #footer>
				<span class="dialog-footer">
					<el-button @click="onCancel" size="default">取 消</el-button>
					<el-button type="primary" @click="onSubmit" size="default">新 增</el-button>
				</span>
      </template>
    </el-dialog>
  </div>
</template>

<script lang="ts">
import {reactive, toRefs, onMounted, defineComponent, getCurrentInstance} from 'vue';
import {roleApi} from '@admin/api/system/role';
import {adminApi} from '@admin/api/system/admin';
import BaseSelect from '@admin/components/baseSelect/index.vue';
import Upload from '@admin/components/upload/index.vue';

// 定义接口来定义对象的类型
interface ruleFormState {
    name: string;
    email: string;
    phone: string;
    password: string;
    avatar: string|undefined;
    status: string;
    role_ids: Array
}

const ruleForm: ruleFormState = {
  name: '',
  phone: '',
  email: '',
  password: '',
  avatar: '',
  status: 'enabled',
  role_ids: []
}

export default defineComponent({
  name: 'systemAddUser',
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
        password: [{required: true, message: '账户密码不能为空', trigger: 'blur'}],
        role_ids: [{required: true, message: '角色不能为空', trigger: 'blur'}],
      },
      roleList: [],
      selectLoading: false
    });
    // 打开弹窗
    const openDialog = () => {
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
    // 新增
    const onSubmit = () => {
      proxy.$refs.ruleFormRef.validate((valid: boolean) => {
        if (valid) {
          state.loading = true
          adminApi().createAdmin(state.ruleForm).then((res?) => {
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

    // 页面加载时
    onMounted(() => {
      getRoleList();
    });

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
