<template>
    <div>
        <b-table class="text-nowrap" striped  responsive hover :items="items" :fields="fields" :sort-by.sync="sortBy"
                 :sort-desc.sync="sort" :busy.sync="isBusy" outlined ref="table" show-empty>

            <div slot="table-busy" class="text-center text-danger my-2">
                <b-spinner class="align-middle"/>
                <strong>Loading...</strong>
            </div>

            <template v-slot:empty="scope">
                <div class="text-center text-secondary">
                    <p>
                        <svg-vue icon="null" class="empty-data"/>
                    </p>
                    <h6>{{ notice }}</h6>
                </div>
            </template>

            <template v-for="slotName in Object.keys($scopedSlots)" v-slot:[slotName]="slotScope">
                <slot :name="slotName" v-bind="slotScope"/>
            </template>

        </b-table>

        <b-row>
            <b-col md="6" class="my-1">
                <b-pagination v-model="currentPage" :total-rows="total" :per-page="limit" class="my-0"/>
                <b-card-text class="mt-3 text-secondary">共 {{ total }} 条数据</b-card-text>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    export default {
        props: {
            items: {
                type: Array,
                default: []
            },
            fields: {
                type: Array,
                default: []
            },
            sortBy: {
                type: String,
                default: 'id'
            },
            sortDesc: {
                type: Boolean,
                default: false
            },
            isBusy: {
                type: Boolean,
                default: true
            },
            sortDirection: {
                type: String,
                default: 'asc'
            },
            notice: {
                type: String,
                default: '暂无数据'
            },
            page: {
                type: Number,
                default: 1
            },
            total: {
                type: Number,
                default: 0
            },
            limit: {
                type: Number,
                default: 20
            }
        },
        data() {
            return {
                currentPage: this.page,
                sort: this.sortDesc
            }
        },
        watch: {
            currentPage(value) {
                this.$parent.form.page = value;
            }
        },
    }
</script>

<style scoped>

</style>
