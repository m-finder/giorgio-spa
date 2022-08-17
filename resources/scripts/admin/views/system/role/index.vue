<template>
  <div class="system-role-container">
    <el-card shadow="hover">
      <div class="system-user-search mb15">
        <el-input size="default" v-model="tableData.param.name" placeholder="请输入角色名称" clearable style="max-width: 180px"/>
        <el-button-group class="ml10">
          <el-button size="mini" v-auth="'roles.index'" type="primary" @click="getList">
            <el-icon>
              <ele-Search/>
            </el-icon>
            查询
          </el-button>
          <el-button size="mini" v-auth="'roles.index'" type="success" @click="resetFilter">
            <el-icon>
              <ele-Close/>
            </el-icon>
            重置
          </el-button>
          <el-button size="mini" v-auth="'roles.store'" type="primary" @click="onOpenAddRole">
            <el-icon>
              <ele-FolderAdd/>
            </el-icon>
            添加
          </el-button>
        </el-button-group>
      </div>

      <el-table v-loading="tableData.loading" :data="tableData.data" style="width: 100%">
        <el-table-column prop="id" label="ID" width="60"/>
        <el-table-column prop="name" label="角色名称" show-overflow-tooltip></el-table-column>
        <el-table-column prop="brief" label="角色描述" show-overflow-tooltip></el-table-column>
        <el-table-column prop="is_super" label="角色类型" show-overflow-tooltip>
          <template #default="scope">
            <el-tag size="mini" type="success" v-if="scope.row.is_super">超管</el-tag>
            <el-tag size="mini" type="info" v-else>普通</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间" show-overflow-tooltip></el-table-column>
        <el-table-column label="操作" width="150">
          <template #default="scope">
            <el-button v-auth="'roles.update'" size="small" text type="primary" @click="onOpenEditRole(scope.row)">修改</el-button>
            <el-button v-auth="'roles.auth'" size="small" text type="primary" @click="onOpenAuthRole(scope.row)">授权</el-button>
            <el-button v-auth="'roles.destroy'" size="small" text type="danger" v-loading="deleteBtnLoading" @click="onRowDel(scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <pagination v-show="tableData.total>0" :total="tableData.total" :page.sync="tableData.param.page" :limit.sync="tableData.param.limit" @pagination="getList"/>
    </el-card>
    <AddRole ref="addRoleRef" @getList="getList"/>
    <EditRole ref="editRoleRef" @getList="getList"/>
    <AuthRole ref="authRoleRef" @getList="getList"/>
  </div>
</template>

<script lang="ts">
import {toRefs, reactive, onMounted, ref, defineComponent, getCurrentInstance} from 'vue';
import {ElMessageBox} from 'element-plus';
import AddRole from '@admin/views/system/role/component/addRole.vue';
import EditRole from '@admin/views/system/role/component/editRole.vue';
import AuthRole from '@admin/views/system/role/component/authRole.vue';
import {roleApi} from "@admin/api/system/role";
import Pagination from '@admin/components/pagination/index.vue';

// 定义接口来定义对象的类型
interface TableData {
  id: number;
  name: string;
  brief: string;
  is_super: boolean;
  created_at: string;
}

interface defaultFilter {
  page: number;
  limit: number;
  name: string | undefined,
}

interface TableDataState {
  tableData: {
    data: Array<TableData>;
    total: number;
    loading: boolean;
    param: defaultFilter;
  };
  deleteBtnLoading: boolean;
}

const defaultFilter: defaultFilter = {
  page: 1,
  limit: 15,
  name: undefined,
}

export default defineComponent({
  name: 'systemRole',
  components: {AddRole, EditRole, AuthRole, Pagination},
  setup() {
    const addRoleRef = ref();
    const editRoleRef = ref();
    const authRoleRef = ref();
    const {proxy} = <any>getCurrentInstance();
    const state = reactive<TableDataState>({
      tableData: {
        data: [],
        total: 0,
        loading: true,
        param: Object.assign({}, defaultFilter),
      },
      deleteBtnLoading: false
    });

    onMounted(() => {
      getList();
    });

    // 获取列表
    const getList = () => {
      state.tableData.loading = true
      roleApi().getRoles(state.tableData.param).then((res?) => {
        state.tableData.data = res.data.data
        state.tableData.total = res.data.total
        state.tableData.loading = false
      }).catch((error?) => {
        state.tableData.data = []
        state.tableData.total = 0
        state.tableData.loading = false
      })
    }
    // 打开新增角色弹窗
    const onOpenAddRole = () => {
      addRoleRef.value.openDialog();
    };
    // 打开修改角色弹窗
    const onOpenEditRole = (row: Object) => {
      editRoleRef.value.openDialog(row);
    };

    const onOpenAuthRole = (row: Object) => {
      authRoleRef.value.openDialog(row);
    }
    // 删除角色
    const onRowDel = (row: any) => {
      state.deleteBtnLoading = true
      ElMessageBox.confirm(`此操作将永久删除角色名称：“${row.name}”，是否继续?`, '提示', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        roleApi().deleteRole(row).then((res?) => {
          state.deleteBtnLoading = false
          proxy.$notify.success({
            title: '成功',
            message: '操作成功'
          })
          getList();
        }).catch((err?) => {
          state.deleteBtnLoading = false
          proxy.$notify.error({
            title: '错误',
            message: err.msg
          })
        })
      }).catch(() => {
        proxy.$notify.info({
          message: '取消操作'
        })
        state.deleteBtnLoading = false
      });
    };

    // 重置检索
    const resetFilter = () => {
      state.tableData.param = Object.assign({}, defaultFilter)
      getList()
    }

    return {
      addRoleRef,
      editRoleRef,
      authRoleRef,
      onOpenAddRole,
      onOpenEditRole,
      onOpenAuthRole,
      onRowDel,
      getList,
      resetFilter,
      ...toRefs(state),
    };
  },
});
</script>
