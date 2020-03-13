<template>
    <section class="content container-fluid">
        <b-row class="justify-content-center p-3">

            <b-col md="4" class="mt-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mb-0">菜单列表</h3>
                    </div>

                    <div class="card-body">
                        <div class="card-tools mb-3">
                            <div class="btn-group mr-3">
                                <b-button v-has="'menu:add'" class="btn-sm" variant="primary" @click="addMenu">添加菜单</b-button>
                            </div>
                        </div>

                        <div v-if="loading" class="text-center text-danger my-2">
                            <b-spinner class="align-middle"/>
                            <strong>Loading...</strong>
                        </div>
                        <div v-else>
                            <b-tree-view v-if="items && items.length" :nodeLabelProp="'title'" :data="items"
                                         :contextMenuItems="menus" @nodeSelect="nodeSelect" :nodesDraggable="false"
                                         @contextMenuItemSelect="menuItemSelected" :renameNodeOnDblClick="false"/>
                        </div>

                        <div v-if="empty" class="text-center text-danger my-2">
                            <p>
                                <svg-vue icon="null" class="empty-data"/>
                            </p>
                            <h6>暂无数据</h6>
                        </div>
                    </div>
                </div>
            </b-col>

            <!--      资源列表      -->
            <b-col md="8" class="mt-3">
                <transition name="fade" mode="out-in">
                    <elements :menu-id="form.id"/>
                </transition>
            </b-col>
        </b-row>

        <create :title="'添加菜单'" :is-create="isCreate" :parent-nodes="parentNodes" v-has="'menu:add'"/>
        <edit :title="'编辑菜单'" :id="form.id" :isEdit="isEdit" v-has="'menu:edit'"/>
        <delete :title="'删除菜单'" :data="form" :is-delete="isDelete" v-has="'menu:delete'"/>
    </section>
</template>

<script>
    import {getAll, deleteData} from "../../api/menu";
    import {bTreeView} from 'bootstrap-vue-treeview';
    import Edit from './Edit';
    import Create from './Create';
    import Delete from '../../components/delete/Index';
    import Elements from '../elements/Index';

    const defaultForm = {
        id: null,
        name: null,
    };

    export default {
        name: "MenuList",
        components: {
            bTreeView,
            Create,
            Edit,
            Delete,
            Elements
        },
        data() {
            return {
                items: [],
                isEdit: false,
                isCreate: false,
                isDelete: false,
                parentNodes: {},
                menus: [{code: 'ADD_MENU', label: '添加子菜单'}, {code: 'RENAME_MENU',label: '编辑菜单'}, {code: 'DELETE_MENU', label: '删除菜单'}, ],
                form: Object.assign({}, defaultForm),
                node: null,
                loading: true,
                empty: false,
            }
        },
        created() {
            this.getAll()
        },
        methods: {
            addMenu() {
                this.parentNodes = {id: 0, title: '顶级菜单'};
                this.isCreate = true;
            },
            nodeSelect(node, isSelected) {
                if (isSelected) {
                    this.form = Object.assign({}, node.data);
                } else if (node.data.id === this.form.id) {
                    this.form = Object.assign({}, defaultForm)
                }
            },
            menuItemSelected(item, node) {
                this.node = node;
                switch (item.code) {
                    case 'ADD_MENU':
                        let id = this.form.id;
                        if (this.form.parent_id == 0) {
                            this.parentNodes = {id: id, title: this.form.title};
                            this.isCreate = true;
                        } else {
                            this.$toast.warning('暂只支持二级菜单', 'Warning');
                        }
                        break;
                    case 'DELETE_MENU':
                        this.form.name = this.form.title;
                        this.isDelete = true;
                        break;
                    case 'RENAME_MENU':
                        this.isEdit = true;
                        break;
                    default:
                        break;
                }
            },
            getAll() {
                this.loading = true;
                getAll().then(res => {
                    this.loading = false;
                    this.empty = false;
                    this.items = res.data;
                }).catch(error => {
                    this.loading = false;
                    this.empty = true;
                });
            },
            deleteData() {
                deleteData(this.form.id).then(res => {
                    this.$toast.success('删除成功。', 'Success');
                    this.node.delete();
                    this.isDelete = false;
                })
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
    .fade-enter-active, .fade-leave-active {
        transition-duration: 0.3s;
        transition-property: opacity;
        transition-timing-function: ease;
    }

    .fade-enter, .fade-leave-active {
        opacity: 0
    }
</style>
