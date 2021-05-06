<template>
  <div class="d-flex " :class="parentClass(type)">
    <b-button pill class="mx-1" :variant="deleteClass(type)" @click="onDelete">
      <i class="fa fa-trash"></i>
    </b-button>
    <b-button v-if="type == 'book'" pill class=" mx-1" :variant="linkClass(type)" @click="onLink">
      <i class="fa fa-link"></i>
    </b-button>
    <b-button v-if="type !== 'book'" pill class=" mx-1" :variant="detailClass(type)" @click="onDetail">
      <i class="fa fa-search"></i>
    </b-button>
    <b-button v-if="type == 'folder'|| type == 'book' || type =='file'" pill class="mx-1" :variant="editClass(type)" @click="onEdit">
      <i class="fa fa-pencil"></i>
    </b-button>
  </div>
</template>
<script>
  export default {
    name: 'ListItemButtons',
    props: {
      type: {
        type: String,
        default: null
      }
    },
    data: function() {
      return {}
    },
    created() {},
    methods: {
      parentClass(type) {
        return type == 'folder' ? 'folders-icons position-absolute' : ''
      },
      deleteClass(type) {
        return type == 'folder' ? 'danger' : 'outline-danger'
      },
      detailClass(type) {
        return type == 'folder' ? 'dark' : 'outline-light'
      },
      linkClass(type) {
        return type == 'book' ? 'outline-light' : ''
      },
      editClass(type) {
        return type == 'book' || type == 'file' ? 'outline-light' : 'dark'
      },
      onDetail() {
        this.$emit('onDetail')
      },
      onEdit() {
        this.$emit('onEdit')
      },
      onDelete() {
        this.$emit('onDelete')
      },
      onLink() {
        this.$emit('onLink')
      }
    },
  }
</script>
<style>
</style>
