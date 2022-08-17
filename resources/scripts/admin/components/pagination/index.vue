<template>
  <el-pagination
      @size-change="onHandleSizeChange"
      @current-change="onHandleCurrentChange"
      class="mt15"
      :pager-count="5"
      :page-sizes="pageSizes"
      v-model:current-page="currentPage"
      background
      v-model:page-size="pageSize"
      :layout="layout"
      :total="total"
  />
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "pagination",
  emits: ['pagination', 'update:page', 'update:limit'],
  props: {
    total: {
      required: true,
      type: Number
    },
    page: {
      type: Number,
      default: 1
    },
    limit: {
      type: Number,
      default: 15
    },
    pageSizes: {
      type: Array,
      default() {
        return [10, 15, 20, 25, 30, 35, 40, 45, 50]
      }
    },
    layout: {
      type: String,
      default: 'total, sizes, prev, pager, next, jumper'
    },
    background: {
      type: Boolean,
      default: true
    },
    autoScroll: {
      type: Boolean,
      default: true
    },
    hidden: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    currentPage: {
      get() {
        return this.page
      },
      set(val) {
        emit('update:page', val)
      }
    },
    pageSize: {
      get() {
        return this.limit
      },
      set(val) {
        emit('update:limit', val)
      }
    }
  },
  setup(props) {

    // 分页改变
    const onHandleSizeChange = (val: number) => {
      emit('pagination', {page: this.currentPage, limit: val});
      if (this.autoScroll) {
        scrollTo(0, 800)
      }
    };

    // 分页改变
    const onHandleCurrentChange = (val: number) => {
      emit('pagination', {page: val, limit: this.pageSize})
      if (this.autoScroll) {
        scrollTo(0, 800)
      }
    };

    return {
      onHandleSizeChange,
      onHandleCurrentChange
    }
  },
});
</script>

<style scoped>

</style>