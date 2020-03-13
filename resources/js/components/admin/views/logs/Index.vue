<template>
    <section class="content container-fluid">

        <b-row class="justify-content-center p-3">
            <b-col cols="12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">日志列表</h3>
                    </div>

                    <div class="card-body">
                        <div class="card-tools">

                            <div class="btn-group mr-3" v-has="'admin:list'">
                                <form model="form">
                                    <b-row>
                                        <b-col md="12" class="mb-3">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">操作邮箱</span>
                                                </div>
                                                <b-form-input type="email" v-model="form.user" placeholder="输入操作邮箱"/>
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

                            <div class="btn-group mr-3 mb-3" v-has="'admin:list'">
                                <b-button class="btn-sm" variant="primary" @click="refresh">刷新列表</b-button>
                            </div>
                        </div>

                        <data-table :is-busy="isBusy" :items="items" :fields="fields" :notice="notice" :total="total"
                                    :limit="form.limit" :page="form.page" :sort-desc="true"/>

                    </div>
                </div>
            </b-col>
        </b-row>
    </section>
</template>

<script>
    import {getData} from "../../api/log";
    import DataTable from '../../components/table/Index'


    const form = {
        user: null,
        limit: 20,
        page: 1
    };

    export default {
        name: "Logs",
        components:{
            DataTable
        },
        data() {
            return {
                isBusy: true,
                total: 0,
                items: [],
                notice: '暂无数据',
                form: Object.assign({}, form),
                fields: [
                    {label: 'ID', key: 'id', sortable: true, sortDirection: 'last'},
                    {label: '操作邮箱', key: 'user', sortable: false},
                    {label: '请求地址', key: 'request_url', sortable: false},
                    {label: '请求内容', key: 'request_content', sortable: false},
                    {label: '创建时间', key: 'created_at', sortable: true},
                ]
            }
        },
        created() {
            this.getList()
        },
        watch: {
            'form.page'(value) {
                this.getList()
            },
        },
        methods: {
            refresh(){
                this.form = Object.assign({}, form);
                this.getList()
            },
            getList() {
                this.isBusy = true;
                getData(this.form).then(res => {
                    this.isBusy = false;
                    this.currentPage = res.data.current_page;
                    this.items = res.data.data;
                    this.total = res.data.total;
                }).catch(error=>{
                    console.log(error);
                    this.isBusy = false;
                    this.notice = '系统出错';
                });
            },
        }
    }
</script>

<style lang="scss" scoped>

    .btn, .btn-outline-info {
        svg{
            fill: #ffffff;
        }
    }
    .btn-outline-info{
        &:hover{
            color: #ffffff;
        }
    }
</style>
