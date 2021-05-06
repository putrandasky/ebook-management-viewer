<template>
  <b-modal :title="chapter.type == 'folder'?'Update Folder':'Update Page'" id="ChapterModal" ok-title="Submit" @ok="updateChapter " @show="onShow" ok-variant="secondary" cancel-variant="outline-secondary">
    <b-form-group label="Chapter Name" v-if="chapter.type == 'folder'">
      <b-input placeholder="Input Chapter Name Here" class="mb-2" v-model="input.chapterName"></b-input>
    </b-form-group>
    <b-form-group label="Alias (Optional)" description="Alias will be used in link address and pagination, if not set will using order">
      <b-input v-model="input.chapterAlias" placeholder="e.g. 'C','i','ii','iii','iv' "></b-input>
    </b-form-group>
  </b-modal>
</template>
<script>
  export default {
    name: 'ChaptersModal',
    props: {
      chapter: Object,
    },
    data: function() {
      return {
        input: {

          chapterName: '',
          chapterAlias: null
        }
      }
    },
    created() {},
    methods: {
      onShow() {
        this.input.chapterName = this.chapter.name
        this.input.chapterAlias = this.chapter.alias
      },
      updateChapter() {
        axios.put(`/api/admin/chapter/${this.chapter.id}`, this.input)
          .then((response) => {

            this.toastSuccess(response.data.message)

            this.$emit('onUpdated', this.input)
            this.input.chapterName = ''
            this.input.chapterAlias = null
          })
          .catch((error) => {
            console.log(error);
            this.toastError("Ooops, There's Something Error")
          })
      }
    },
  }
</script>
<style>
</style>
