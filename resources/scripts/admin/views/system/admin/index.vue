<template>
  <div class="system-user-container">
    <el-card shadow="hover">
      <div class="system-user-search mb15">
        <el-row :gutter="10">
          <el-col :xs="24" :sm="4">
            <el-input size="default" v-model="tableData.param.name" placeholder="请输入名称" style="max-width: 180px"></el-input>
          </el-col>
          <el-col :xs="24" :sm="4">
            <el-input size="default" v-model="tableData.param.phone" placeholder="请输入手机号" style="max-width: 180px"></el-input>
          </el-col>
          <el-col :xs="24" :sm="4">
            <el-input size="default" v-model="tableData.param.email" placeholder="请输入邮箱" style="max-width: 180px"></el-input>
          </el-col>

          <el-button-group class="ml10">
            <el-button size="mini" v-auth="'admins.index'" type="primary" @click="getList">
              <el-icon>
                <ele-Search/>
              </el-icon>
              查询
            </el-button>
            <el-button size="mini" v-auth="'admins.index'" type="success" @click="resetFilter">
              <el-icon>
                <ele-Close/>
              </el-icon>
              重置
            </el-button>
            <el-button size="mini" v-auth="'admins.store'" type="primary" @click="onOpenAddUser">
              <el-icon>
                <ele-FolderAdd/>
              </el-icon>
              添加
            </el-button>
          </el-button-group>
        </el-row>

      </div>

      <el-table v-loading="tableData.loading" :data="tableData.data" style="width: 100%">
        <el-table-column prop="id" label="ID" width="60"/>
        <el-table-column prop="name" label="名称" show-overflow-tooltip width="120"></el-table-column>
        <el-table-column prop="phone" label="手机号" show-overflow-tooltip width="120"></el-table-column>
        <el-table-column prop="email" label="邮箱" show-overflow-tooltip width="200"></el-table-column>
        <el-table-column prop="roles" label="角色名称" show-overflow-tooltip>
          <template #default="scope">
            <el-tag type="primary" size="mini" v-for="item in scope.row.roles" :key="item.id">
              {{ item.name }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="status" label="状态" show-overflow-tooltip width="120">
          <template #default="scope">
            <el-tag type="success" size="mini" v-if="scope.row.status === 'enabled'">启用</el-tag>
            <el-tag type="info" size="mini" v-else>禁用</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间" show-overflow-tooltip width="200"></el-table-column>
        <el-table-column label="操作" width="160">
          <template #default="scope">
            <el-button v-auth="'admins.update'" size="small" text type="primary" @click="onOpenEditAdmin(scope.row)">修改</el-button>
            <el-button v-auth="'admins.update'" size="small" text type="primary" @click="resetPassword(scope.row)">重置密码</el-button>
            <el-button v-auth="'admins.destroy'" size="small" text type="danger" v-loading="deleteBtnLoading" @click="onRowDel(scope.row)">删除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
      <pagination v-show="tableData.total>0" :total="tableData.total" :page.sync="tableData.param.page" :limit.sync="tableData.param.limit" @pagination="getList"/>
    </el-card>
    <AddAdmin ref="addAdminRef" @getList="getList"/>
    <EditAdmin ref="editAdminRef" @getList="getList"/>
  </div>
</template>

<script lang="ts">
import {toRefs, reactive, onMounted, ref, defineComponent, getCurrentInstance} from 'vue';
import {ElMessageBox} from 'element-plus';
import AddAdmin from '@admin/views/system/admin/component/addAdmin.vue';
import EditAdmin from '@admin/views/system/admin/component/editAdmin.vue';
import {adminApi} from "@admin/api/system/admin";
import {passwordApi} from "@admin/api/system/password";
import Pagination from '@admin/components/pagination/index.vue';

// 定义接口来定义对象的类型
interface TableDataRow {
  "id": number,
  "name": string,
  "email": string,
  "phone": string,
  "avatar": string,
  "status": string,
  "is_super": number,
  "created_at": string,
  "updated_at": string,
  "roles": Array<RoleRow>
}

// 定义接口来定义对象的类型
interface RoleRow {
  "id": number,
  "name": string,
  "brief": string
}

interface defaultFilter {
  page: number;
  limit: number;
  name: string | undefined;
  email: string | undefined;
  phone: string | undefined;
}

interface TableDataState {
  tableData: {
    data: Array<TableDataRow>;
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
  email: undefined,
  phone: undefined
}

export default defineComponent({
  name: 'systemAdmin',
  components: {AddAdmin, EditAdmin, Pagination},
  setup() {
    const addAdminRef = ref();
    const editAdminRef = ref();
    const {proxy} = <any>getCurrentInstance();
    const state = reactive<TableDataState>({
      tableData: {
        data: [],
        total: 0,
        loading: false,
        param: Object.assign({}, defaultFilter),
      },
      deleteBtnLoading: false,
    });

    onMounted(() => {
      getList();
    });
    // 获取列表
    const getList = () => {
      state.tableData.loading = true
      adminApi().getAdmins(state.tableData.param).then((res?) => {
        state.tableData.data = res.data.data
        state.tableData.total = res.data.total
        state.tableData.loading = false
      }).catch((error?) => {
        state.tableData.data = []
        state.tableData.total = 0
        state.tableData.loading = false
      })
    }
    // 打开新增用户弹窗
    const onOpenAddUser = () => {
      addAdminRef.value.openDialog();
    };
    // 打开修改用户弹窗
    const onOpenEditAdmin = (row: TableDataRow) => {
      editAdminRef.value.openDialog(row);
    };
    // 删除用户
    const onRowDel = (row: TableDataRow) => {
      state.deleteBtnLoading = true
      ElMessageBox.confirm(`此操作将删除该管理员：“${row.name}”，是否继续?`, '提示', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        adminApi().deleteAdmin(row).then((res?) => {
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
    // 重置密码
    const resetPassword = (row: TableDataRow) => {
      ElMessageBox.confirm(`此操作将重置该管理员密码：“${row.name}”，是否继续?`, '提示', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        passwordApi().resetPassword(row).then(res => {
              proxy.$notify.success({
                title: '成功',
                message: '重置成功,新密码为 ' + res.data.password
              })
            }).catch(err => {
              proxy.$notify.error({
                title: '失败',
                message: err.msg
              })
            })
      }).catch(() => {
        proxy.$notify.info({
          message: '取消操作'
        })
      });
    };

    // 重置检索
    const resetFilter = () => {
      state.tableData.param = Object.assign({}, defaultFilter)
      getList()
    }

    return {
      addAdminRef,
      editAdminRef,
      onOpenAddUser,
      onOpenEditAdmin: onOpenEditAdmin,
      onRowDel,
      getList,
      resetFilter,
      resetPassword,
      ...toRefs(state),
    };
  },
});
</script>
