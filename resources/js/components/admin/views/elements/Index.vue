<template>
    <div class="card card-primary card-outline" v-if="menuId">
        <div class="card-header">
            <h3 class="card-title mb-0">资源列表</h3>
        </div>
        <div class="card-body">
            <div class="card-tools">
                <div class="btn-group mr-3 mb-3">
                    <b-button v-has="'element:add'" class="btn-sm" variant="primary" @click="add">添加资源</b-button>
                </div>
            </div>

            <data-table :is-busy="isBusy" :items="items" :fields="fields" :notice="notice" :total="total" :limit="form.limit" :page="form.page">
                <template v-slot:cell(actions)="row">
                    <b-button v-has="'element:edit'" variant="link" @click="openEditModal(row.item)">编辑</b-button>
                    <b-button v-has="'element:delete'" variant="link" class="text-danger mr-1" @click="openDeleteModal(row.item)">删除</b-button>
                </template>
            </data-table>
        </div>

        <create :title="'添加资源'" :menu-id="menuId" :is-create="isCreate" v-has="'element:add'"/>
        <edit :title="'编辑资源'" :id="form.id" :is-edit="isEdit" v-has="'element:edit'"/>
        <delete :title="'删除资源'" :data="form" :is-delete="isDelete" v-has="'element:delete'"/>
    </div>
</template>

<script>
    import {getData, deleteData} from "../../api/element";
    import Create from './Create';
    import Edit from './Edit';
    import Delete from '../../components/delete/Index';
    import DataTable from '../../components/table/Index'

    const defaultForm = {
        menu_id: null,
        page: 1,
        limit: 20,
    };

    export default {
        name: "ElementList",
        components: {
            DataTable,
            Create,
            Edit,
            Delete
        },
        data(){
            return {
                items: [],
                total: 0,
                isBusy: true,
                isCreate: false,
                isEdit: false,
                isDelete: false,
                notice: '暂无数据',
                form: Object.assign({}, defaultForm),
                fields: [
                    {label: 'ID', key: 'id', sortable: true},
                    {label: '资源名称', key: 'name', sortable: false},
                    {label: '资源编号', key: 'code', sortable: false},
                    {label: '请求方法', key: 'method', sortable: false},
                    {label: '请求路径', key: 'path', sortable: false},
                    {label: '创建时间', key: 'created_at', sortable: true},
                    {label: '操作', key: 'actions', sortable: false}
                ],
            }
        },
        props:{
            'menu-id': {
                default: null
            }
        },
        watch:{
            menuId(value){
                this.form.menu_id = value;
                if(value){
                    this.isBusy = true;
                    this.getList()
                }
            }
        },
        methods: {
            getList() {
                this.isBusy = true;
                getData(this.form).then(res => {
                    this.isBusy = false;
                    this.items = res.data.data;
                    this.form.page = res.data.current_page;
                    this.total = res.data.total;
                }).catch(error => {
                    console.log(error)
                    this.isBusy = false;
                    this.notice = '系统出错';
                });
            },
            openDeleteModal(data){
                this.form = data;
                this.isDelete = true;
            },
            openEditModal(data){
                this.isEdit = true;
                this.form.id = data.id;
            },
            add(){
                this.isCreate = true
            },
            deleteData() {
                let id = this.form.id;
                deleteData(id).then(res => {
                    this.$toast.success('删除成功。', 'Success');
                    this.items = this.items.filter(item => item.id != id);
                    this.total = this.total - 1;
                    this.isDelete = false;
                })
            }
        }
    }
</script>

<style scoped>

</style>
