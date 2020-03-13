<template>
    <b-modal centered :title="title" v-model="show" @hidden="resetModal">
        <p class="my-4">
            {{ form.name ? '是否确认删除数据： ' + form.name + '？' : '是否删除该数据？'}}
        </p>
        <template slot="modal-footer" class="w-100 modal-footer">
            <b-button variant="primary" size="sm" @click="resetModal">取消</b-button>
            <b-button variant="danger" size="sm" @click="deleteData">确认</b-button>
        </template>
    </b-modal>
</template>

<script>

    const defaultForm = {
        id: null,
        name: null
    };
    export default {
        name: "Index",
        data() {
            return {
                show: false,
                form: Object.assign({}, defaultForm)
            }
        },
        props: {
            title: {
                type: String,
                default: '删除操作',
            },
            isDelete: {
                type: Boolean,
                default: false
            },
            data: {
                type: Object,
                default: {}
            }
        },
        watch: {
            isDelete(value) {
                this.show = value;
                this.form = Object.assign({}, this.data);
            }
        },
        methods: {
            resetModal() {
                this.form = Object.assign({}, defaultForm);
                this.show = false;
                this.$parent.isDelete = false;
            },
            deleteData() {
                this.$parent.deleteData()
            }
        }
    }
</script>

<style scoped>

</style>