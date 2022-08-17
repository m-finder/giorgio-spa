<template>
  <el-select
      remote
      filterable
      clearable
      reserve-keyword
      value-key="id"
      loading-text="加载中..."
      :remote-method="methodAntiShake(remoteMethod)"
      @change="handleChange"
      @clear="handleClear"
      @focus="handleFocus"
      :size="size"
      v-model="data"
      :loading="loading"
      :disabled="disabled"
      :multiple="multiple"
      :placeholder="placeholder"
  >
    <slot>
      <el-option v-for="item in options" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </slot>
  </el-select>
</template>

<script lang="ts">
export default {
  name: "baseSelect",
  emits: ['remote-method', 'handle-change'],
  props: {
    // 绑定值
    data: {
      type: [String, Number, Object],
      default: ''
    },
    // 占位符
    placeholder: {
      type: String,
      default: '请选择'
    },
    // 加载中
    loading: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'small'
    },
    // 选项列表数据源
    options: {
      type: Array,
      default: () => {
        return []
      }
    },
    // 是否多选
    multiple: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    valueKey: {
      type: String,
      default: ''
    }
  },
  setup(props: any, { emit }) {
    // 搜索方法
    const remoteMethod = (query: any) => {
      if (query) {
        emit('remote-method', query)
      }
    };
    // 初始搜索第一页
    const handleFocus = () => {
      emit('remote-method')
    };
    // 选中赋值方法
    const handleChange = (query: any) => {
      if (query) {
        emit('handle-change', query)
      }
    };
    // 清空
    const handleClear = () => {
      emit('handle-change')
    };
    // 查询方法防抖
    const methodAntiShake = (fun) => {
      let timeout = null
      return function () {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
          fun.apply(this, arguments)
        }, 1000)
      }
    }
    return {
      remoteMethod,
      handleFocus,
      handleChange,
      handleClear,
      methodAntiShake
    }
  },
}
</script>

<style scoped>

</style>