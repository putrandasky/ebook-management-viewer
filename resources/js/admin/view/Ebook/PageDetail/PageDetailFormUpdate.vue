<template>
  <b-card>
    <b-form-group id="page-name-field" label="Page Name" label-for="name">

      <b-form-input required min="5" id="page-name" v-model="pageName"></b-form-input>

    </b-form-group>
    <b-form-checkbox id="page-mark" v-model="pageMarked" :value="1" :unchecked-value="0">
      Mark In Content Navigation
    </b-form-checkbox>
    <b-btn class="float-right" variant="secondary" size="sm" @click="updateData">Update</b-btn>
  </b-card>
</template>
<script>
  export default {
    name: 'PageDetailFormUpdate',
    props: {
      name: String,
      is_marked: Number
    },
    data: function() {
      return {
        pageName: this.name,
        pageMarked: this.is_marked,
      }
    },
    created() {},
    watch: {
      name(newVal, oldVal) {
        this.pageName = newVal
      },
      is_marked(newVal, oldVal) {
        this.pageMarked = newVal
      },
    },
    methods: {

      updateData() {
        axios.patch(`/api/admin/page/${this.$route.params.pageId}`, {
            name: this.pageName,
            is_marked: this.pageMarked
          })
          .then((response) => {
            this.toastSuccess(response.data.message)

          })
          .catch((error) => {
            if ([401, 419].includes(error.response.status)) {
              return
            }
            if (typeof error.response.data.errors.name !== 'undefined') {
              this.toastError(error.response.data.errors.name[0])
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
