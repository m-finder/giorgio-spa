<template>
    <b-modal centered size="xl" :title="title" v-model="show" @hidden="resetModal">

        <div v-if="loading" class="text-center text-danger my-2">
            <b-spinner class="align-middle"/>
            <strong>Loading...</strong>
        </div>

        <div v-if="empty" class="text-center my-2">
            <p>
                <svg-vue icon="null" class="empty-data"/>
            </p>
            <h6>暂无数据</h6>
        </div>

        <div v-if="menus">
            <b-row class="p-3">
                <template v-for="(item,i) in menus">
                    <b-col cols="6">
                        <div class="card mb-3">
                            <h5 class="card-title border-bottom pl-2 pb-1 mt-2 mb-2 ">
                                <b-form-checkbox :value="item.id" switch @change="toggleMenu($event, item.id)"
                                                 v-model="menuArray">
                                    {{ item.title }}
                                </b-form-checkbox>
                            </h5>
                            <div class="card-body">
                                <div v-if="item.elements && item.elements.length > 0">
                                    <b-form-checkbox-group :id="'element-' + item.id" v-model="elementArray">
                                        <template v-for="(e,ei) in item.elements">
                                            <b-form-checkbox :value="e.id" @change="toggleElement($event, e.id)">
                                                {{ e.name }}
                                            </b-form-checkbox>
                                        </template>
                                    </b-form-checkbox-group>
                                </div>
                                <div v-else class="text-center">
                                    <p>
                                        <svg-vue icon="null" class="empty-data"/>
                                    </p>
                                    <h6>暂无数据</h6>
                                </div>
                            </div>
                        </div>
                    </b-col>
                </template>
            </b-row>
        </div>

        <template slot="modal-footer" class="w-100 modal-footer">
            <b-button variant="primary" :disabled="disabled" size="sm" @click="resetModal">取消</b-button>
            <b-button v-has="'role:setAuth'" :disabled="disabled" variant="danger" size="sm" @click="submitAssign">
                <span v-if="disabled" class="spinner-border spinner-border-sm"/>
                确认
            </b-button>
        </template>
    </b-modal>
</template>

<script>
    import {getAllWithElements} from '../../api/menu'
    import {getAuth, setAuth} from '../../api/role'

    export default {
        name: "RolePermission",
        props: {
            'is-assign': {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: '添加操作'
            },
            id: {
                default: null
            }
        },
        data() {
            return {
                show: this.isCreate,
                disabled: true,
                loading: true,
                empty: false,
                menus: [],
                menuArray: [],
                elementArray: [],
            }
        },
        watch: {
            isAssign(value) {
                this.show = value;
                if (value) {
                    this.menus = [];
                    this.getMenus();
                    this.getAuth();
                }
            }
        },
        methods: {
            getAuth() {
                getAuth(this.id).then(res => {
                    this.menuArray = res.data.menus;
                    this.elementArray = res.data.elements;
                })
            },
            toggleMenu(checked, id) {
                let elements = (this.menus.filter(item => item.id == id)[0].elements).map(item => item.id);
                checked ? (this.menuArray.push(id), this.elementArray.push.apply(this.elementArray, elements))
                    : (this.menuArray = this.menuArray.filter(item => item != id), this.elementArray = this.elementArray.filter(item => !elements.includes(item)));
            },
            toggleElement(checked, id) {
                checked ? this.elementArray.push(id) : this.elementArray = this.elementArray.filter(item => item != id);
            },
            getMenus() {
                this.loading = this.disabled = true;
                this.empty = false;
                getAllWithElements().then(res => {
                    this.loading = this.disabled = false;
                    this.menus = res.data;
                }).catch(error => {
                    this.loading = false;
                    this.empty = true;
                });
            },
            submitAssign() {
                this.disabled = true;
                let data = {menus: this.menuArray, elements: this.elementArray};
                setAuth(this.id, data).then(res => {
                    this.$toast.success('权限分配成。', 'Success');
                    this.resetModal();
                })
            },
            resetModal() {
                this.show = false;
                this.$parent.isAssign = false;
            }
        }
    }
</script>

<style scoped>

</style>
