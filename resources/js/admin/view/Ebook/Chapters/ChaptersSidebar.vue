<template>
  <b-sidebar title="Add New Chapter" right backdrop id="sidebar-chapter" class="px-2" :z-index="1001" :shadow="true">
    <b-container>
      <b-button-group class="btn-block">
        <b-btn @click="state = 'chapter'" :variant="state == 'chapter' ?'secondary' : 'outline-secondary' " size="sm">Chapter</b-btn>
        <b-btn @click="state = 'file'" :variant="state == 'file' ?'secondary' : 'outline-secondary' " size="sm">File</b-btn>
      </b-button-group>

      <add-chapter-input v-if="state =='chapter'" @submitted="newChapterSubmitted($event)"></add-chapter-input>
      <div class="mt-2" v-if="state =='file'">
        <file-upload :url="`/api/admin/chapter/create-file/${this.$route.params.slug}`" @uploaded="newFileUploaded($event)"></file-upload>
      </div>
    </b-container>
  </b-sidebar>
</template>
<script>
  import FileUpload from '@/admin/components/FileUpload'
  import AddChapterInput from './ChaptersSidebarAddChapterInput.vue'
  export default {
    name: 'ChaptersSidebar',
    components: {
      FileUpload,
      AddChapterInput
    },
    data: function() {
      return {
        state: 'chapter'
      }
    },
    created() {},
    methods: {
      newChapterSubmitted(e) {
        this.$emit('newChapterSubmitted', e)
      },
      newFileUploaded(e) {
        this.$emit('newFileUploaded', e)
      }
    },
  }
</script>
<style>
</style>
