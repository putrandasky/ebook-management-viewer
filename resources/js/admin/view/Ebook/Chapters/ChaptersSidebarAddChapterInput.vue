<template>
  <div class="mt-2">
    <b-form-group label="Chapter Name">
      <b-input required min="5" placeholder="Input Chapter Name Here" v-model="input.chapterName"></b-input>
    </b-form-group>
    <b-form-group label="Alias (Optional)" description="Alias will be used in link address and pagination, if not set will using order">
      <b-input placeholder="e.g. 'C','i','ii','iii','iv' " v-model="input.alias"></b-input>
    </b-form-group>
    <b-btn variant="secondary" size="sm" class="float-right" @click="createChapter">Submit</b-btn>
  </div>
</template>
<script>
  export default {
    name: 'ChaptersSidebarAddChapterInput',
    data: function() {
      return {
        input: {
          chapterName: '',
          alias: '',
          slug: this.$route.params.slug
        }
      }
    },
    created() {},
    methods: {
      createChapter() {
        axios.post(`/api/admin/chapter/create-folder`, this.input)
          .then((response) => {
            this.input.chapterName = ''
            this.input.alias = ''
            this.toastSuccess(response.data.message)
            this.$emit('submitted', response.data.data)
            this.$root.$emit('bv::toggle::collapse', 'sidebar-chapter')

          })
          .catch((error) => {
            console.log(error);
            if (typeof error.response.data.errors.chapterName !== 'undefined') {
              this.toastError(error.response.data.errors.chapterName[0])
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      }
    },
  }
</script>
<style>
</style>
