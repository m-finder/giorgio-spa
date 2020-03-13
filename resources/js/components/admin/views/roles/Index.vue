<template>
    <section class="content container-fluid">
        <b-row class="justify-content-center p-3">
            <b-col cols="12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">角色列表</h3>
                    </div>

                    <div class="card-body">

                        <div class="card-tools">
                            <div class="btn-group  mr-3" v-has="'role:list'">
                                <form model="form">
                                    <b-row>
                                        <b-col md="6" class="mb-3">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">角色名称</span>
                                                </div>
                                                <b-form-input type="text" v-model="form.name" placeholder="输入角色名称"/>
                                            </div>
                                        </b-col>

                                        <b-col md="6" class="mb-3">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">角色别名</span>
                                                </div>
                                                <b-form-input type="text" v-model="form.alias" placeholder="输入角色别名"/>
                                                <div class="input-group-append">
                                                    <button type="button" @click="getList()" class="btn btn-primary">
                                                        <svg-vue icon="search"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </b-col>
                                    </b-row>
                                </form>
                            </div>

                            <div class="btn-group mr-3 mb-3">
                                <b-button v-has="'role:add'" class="btn-sm" variant="primary" @click="openAddModal">添加角色</b-button>
                            </div>

                            <div class="btn-group mb-3" v-has="'role:list'">
                                <b-button class="btn-sm" variant="primary" @click="refresh">刷新列表</b-button>
                            </div>
                        </div>

                        <data-table :is-busy="isBusy" :items="items" :fields="fields" :notice="notice" :total="total" :limit="form.limit" :page="form.page">
                            <template v-slot:cell(actions)="row">
                                <b-button v-has="'role:edit'" v-if="row.item.id != 1" variant="link" @click="openEditModal(row.item)">编辑</b-button>
                                <b-button v-has="'role:setAuth'" variant="link" class="text-danger" @click="assign(row.item)">权限分配</b-button>
                                <b-button v-has="'role:delete'" v-if="row.item.id != 1" variant="link" class="text-danger" @click="openDeleteModal(row.item)">删除</b-button>
                            </template>
                        </data-table>

                    </div>
                </div>
            </b-col>
        </b-row>

        <create :title="'添加角色'" :is-create="isCreate" v-has="'role:add'"/>
        <edit :title="'编辑角色'" :id="selectForm.id" :is-edit="isEdit" v-has="'role:edit'"/>
        <delete :title="'删除角色'" :data="selectForm" :is-delete="isDelete" v-has="'role:delete'"/>
        <assign :title="'权限分配'" :id="selectForm.id" :is-assign="isAssign" v-has="'role:setAuth'"/>
    </section>
</template>

<script>
    import {getData, deleteData} from "../../api/role";
    import Create from './Create';
    import Edit from './Edit';
    import Delete from '../../components/delete/Index';
    import Assign from '../roles-permissions/Index';
    import DataTable from '../../components/table/Index'

    const form = {
        name: null,
        alias: null,
        limit: 20,
        page: 1,
    };

    const defaultForm = {
        id: null,
        name: null,
    };

    export default {
        name: "RoleList",
        components: {
            Create,
            Edit,
            Delete,
            Assign,
            DataTable
        },
        data() {
            return {
                isBusy: true,
                isCreate: false,
                isEdit: false,
                isDelete: false,
                isAssign: false,
                notice: '暂无数据',
                total: 0,
                items: [],
                form: Object.assign({}, form),
                selectForm: Object.assign({}, defaultForm),
                fields: [
                    {label: 'ID', key: 'id', sortable: true},
                    {label: '角色名称', key: 'name', sortable: false},
                    {label: '角色别名', key: 'alias', sortable: false},
                    {label: '创建时间', key: 'created_at', sortable: true},
                    {label: '操作', key: 'actions', sortable: false}
                ]
            }
        },
        created() {
            this.getList()
        },
        watch: {
            'form.page'() {
                this.getList()
            },
        },
        methods: {
            assign(data) {
                this.selectForm = data;
                this.isAssign = true;
            },
            refresh() {
                this.form = Object.assign({}, form);
                this.getList()
            },
            getList() {
                this.isBusy = true;
                getData(this.form).then(res => {
                    this.isBusy = false;
                    this.form.page = res.data.current_page;
                    this.items = res.data.data;
                    this.total = res.data.total;
                }).catch(error => {
                    console.log(error)
                    this.isBusy = false;
                    this.notice = '系统出错';
                });
            },
            openEditModal(data) {
                this.selectForm.id = data.id;
                this.isEdit = true;
            },
            openAddModal() {
                this.isCreate = true
            },
            openDeleteModal(data) {
                this.selectForm = data;
                this.isDelete = true;
            },
            deleteData() {
                let id = this.selectForm.id;
                deleteData(id).then(res => {
                    this.$toast.success('删除成功。', 'Success');
                    this.items = this.items.filter(item => item.id != id);
                    this.total = this.total - 1;
                    this.isDelete = false;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>

    .row-user-avatar {
        width: 25px;
        height: 25px;
    }

    .btn, .btn-outline-info {
        svg {
            fill: #ffffff;
        }
    }

    .btn-outline-info {
        &:hover {
            color: #ffffff;
        }
    }
</style>
