<template>
  <el-upload
      class="avatar-uploader"
      ref="upload"
      :auto-upload="true"
      :show-file-list="multiple"
      :list-type="multiple ? 'picture-card' : ''"
      :limit="multiple ? limit : null"
      v-model:file-list="fileList"
      :headers="headers"
      v-loading="loading"
      :action="uploadPath"
      :on-error="handleOnError"
      :on-exceed="handleOnExceed"
      :on-success="handleOnSuccess"
      :before-upload="handleBeforeUpload"
  >
    <img v-if="!multiple && fileList && fileList.length > 0" :src="fileList[0].url" class="avatar" alt=""/>
    <el-icon v-else class="avatar-uploader-icon">
      <ele-Plus/>
    </el-icon>
  </el-upload>

  <el-dialog v-model="dialogVisible">
    <img w-full :src="dialogImageUrl" alt="Preview Image"/>
  </el-dialog>
</template>

<script lang="ts">
import {getCurrentInstance, reactive, toRefs, ref} from "vue";
import {genFileId} from "element-plus";
import type {UploadProps, UploadRawFile} from "element-plus";
import {Session} from "@admin/utils/storage";

export default {
  name: "upload",
  emits: ['handle-change'],
  props: {
    /**
     * 是否多文件，需要搭配limit，否则会被替换
     */
    multiple: {
      type: Boolean,
      default: false
    },
    /**
     * 可上传张数，需搭配multiple
     */
    limit: {
      type: Number,
      default: 1
    },
    file: {
      type: [Array, String],
      default: null
    },
  },
  computed: {
    headers() {
      return {
        'authorization': 'Bearer ' + Session.get('token'),
        'accept': 'application/json'
      }
    },
    fileList() {
      return this.changeFileList(this.file)
    }
  },
  setup(props: any, {emit}) {
    const {proxy} = <any>getCurrentInstance();
    const state = reactive({
      uploadPath: '/admin/file',
      loading: false,
      dialogImageUrl: '',
      fileList: [],
      upload: undefined,
      disabled: false
    })

    const handleBeforeUpload: UploadProps['beforeUpload'] = (rawFile) => {
      if (rawFile.size / 1024 / 1024 > 2) {
        proxy.$notify.error({
          title: '错误',
          message: '大小超出2M'
        })
        return false
      }
      state.loading = true
      return true
    }

    const handleOnSuccess: UploadProps['onSuccess'] = (res, uploadFile) => {
      if (res.status === 200) {
        state.loading = false
        emit('handle-change', res.data.path)
      } else {
        state.loading = false
        proxy.$notify.error({
          title: '失败',
          message: res.msg
        })
      }
    }

    const handleOnExceed: UploadProps['onExceed'] = (files) => {
      state.upload!.clearFiles()
      const file = files[0] as UploadRawFile
      file.uid = genFileId()
      state.upload!.handleStart(file)
    }

    const handleOnError: UploadProps['beforeUpload'] = (error: Error, uploadFile: UploadFile, uploadFiles: UploadFiles) => {
      state.loading = false
      emit('handle-change')
      proxy.$notify.error({
        title: '失败',
        message: error.msg
      })
    };

    const handleRemove: UploadProps['onRemove'] = (uploadFile, uploadFiles) => {
      console.log(uploadFile, uploadFiles)
    }

    const handlePictureCardPreview: UploadProps['onPreview'] = (uploadFile) => {
      state.dialogImageUrl = uploadFile.url!
      state.dialogVisible = true
    }

    const changeFileList = (file) => {
      if (file && typeof file === 'string') {
        return state.fileList = [{url: file}]
      } else if (file && typeof file === 'object') {
        return state.fileList = {url: file.url, id: index}
      }
    };


    return {
      handleOnSuccess,
      handleBeforeUpload,
      changeFileList,
      handleOnExceed,
      handleOnError,
      handleRemove,
      handlePictureCardPreview,
      ...toRefs(state),
    }
  }
}
</script>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed var(--el-border-color);
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: var(--el-transition-duration-fast);
}

.avatar-uploader .el-upload:hover {
  border-color: var(--el-color-primary);
}

.el-icon.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  text-align: center;
}

.avatar-uploader .avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>