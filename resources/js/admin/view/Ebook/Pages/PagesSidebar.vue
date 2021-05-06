<template>
  <b-sidebar title="Add New Pages" right backdrop id="sidebar-page" class="px-2" :z-index="1001" :shadow="true">
    <b-container>

      <div class="mt-2">
        <file-upload :url="`/api/admin/page/create-file/${this.$route.params.chapterId}`" @uploaded="newFileUploaded($event)"></file-upload>
      </div>
    </b-container>
  </b-sidebar>
</template>
<script>
  import FileUpload from '@/admin/components/FileUpload'
  export default {
    name: 'ChaptersSidebar',
    components: {
      FileUpload,
    },
    data: function() {
      return {}
    },
    created() {},
    methods: {

      newFileUploaded(e) {
        this.$emit('newFileUploaded', e)
      }
    },
  }
</script>
<style>
</style>
