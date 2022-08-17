<template>
  <div class="system-edit-role-container">
    <el-dialog title="修改角色" v-model="isShowDialog" width="769px" @close="closeDialog">
      <el-form :model="ruleForm" ref="ruleFormRef" size="default" label-width="90px">
        <el-row :gutter="35">
          <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="12" class="mb20">
            <el-tree class="menu-data-tree" v-loading="dialogLoading" ref="treeRef" :data="permissions" :default-checked-keys="assignedPermissions" show-checkbox node-key="id" highlight-current>
              <template #default="{ node, data }">
                  <span class="custom-tree-node">
                    <span>{{ node.label }}</span>
                    <el-tag v-if="node.api" type="success" size="mini" style="margin: 0 10px;">
                      {{ node.api || '初始' }}
                    </el-tag>
                  </span>
              </template>
            </el-tree>
          </el-col>
        </el-row>
      </el-form>
      <template #footer>
				<span class="dialog-footer">
					<el-button @click="onCancel" size="default">取 消</el-button>
					<el-button type="primary" @click="onSubmit" size="default">提 交</el-button>
				</span>
      </template>
    </el-dialog>
  </div>
</template>

<script lang="ts">
import {reactive, toRefs, defineComponent, getCurrentInstance} from 'vue';
import {roleApi} from '@admin/api/system/role';
import {permissionApi} from '@admin/api/permission';
import {dynamicRoutes as routeMap} from "@admin/router/route";

// 定义接口来定义对象的类型
interface ruleFormState {
  id: number | undefined;
  permissions: object | undefined;
}

const ruleForm: ruleFormState = {
  id: undefined,
  permissions: [],
}

export default defineComponent({
  name: 'systemAuthRole',
  emits: ['getList'],
  setup(props: any, { emit }) {
    const {proxy} = <any>getCurrentInstance();
    const state = reactive({
      isShowDialog: false,
      ruleForm,
      dialogLoading: false,
      permissions: [],
      assignedPermissions: [],
    });
    // 打开弹窗
    const openDialog = (row: ruleFormState) => {
      state.ruleForm = row;
      getPermissions(row);
      state.isShowDialog = true;
    };

    // 获取权限列表
    const getPermissions = (row) => {
      state.dialogLoading = true
      permissionApi().getPermissions({type: 'admin'}).then(res => {
        adminPermissions(row, res)
      }).catch(err => {
        state.dialogLoading = false
        proxy.$notify.error({
          title: '失败',
          message: err.msg
        })
      })
    };
    // 遍历权限
    const adminPermissions = (row, res) => {
      let globalRouter = []
      let selectRouter = []
      row.permissions.map(e => {
        selectRouter.push(e.name)
      })
      if(routeMap[0]){
        routeMap[0].children.map(route => {
          let snap = {
            id: route.path,
            label: route.meta.title
          }
          if (route.children && route.meta.title) {
            snap.children = []
            route.children.map(e => {
              let childrenList = []
              res.data.map(item => {
                if (e.meta.auth && e.meta.auth.includes(item.name)) {
                  childrenList.push({
                    id: item.name,
                    api: item.method + ' ' + item.uri,
                    label: item.name_zh_cn
                  })
                }
              })
              if (childrenList.length > 0) {
                snap.children.push({
                  id: e.path,
                  label: e.meta.title,
                  perms: e.meta.auth,
                  children: childrenList
                })
              }
            })
            globalRouter.push(snap)
          }
        })
      }
      state.permissions = globalRouter
      state.assignedPermissions = selectRouter
      state.dialogLoading = false
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
    // 重置表单
    const resetField = () => {
      proxy.$refs.ruleFormRef.resetFields();
    }
    // 授权
    const onSubmit = () => {
      state.loading = true
      state.ruleForm.permissions = proxy.$refs.treeRef.getCheckedKeys(true).map(e => {
        if (e.includes('/')) {
          return undefined
        } else {
          return e
        }
      })
      roleApi().authRole(state.ruleForm).then((res?) => {
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
          title: '失败',
          message: err.msg
        })
      })
    };
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
