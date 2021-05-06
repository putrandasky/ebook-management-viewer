<template>
  <b-col cols="12 ">
    <div class="p-5 empty-state d-flex justify-content-center ">
      <div v-if="type == 'book'" class="text-center">
        <i class="fa fa-book fa-3x"></i>
        <h3 class="mt-3">NO BOOK FOUND</h3>
        <div>Please add new book first to see something in here</div>
      </div>
      <div v-if="type == 'chapter'" class="text-center">
        <i class="fa fa-folder fa-3x"></i>
        <h3 class="mt-3">NO CHAPTER/PAGE FOUND</h3>
        <div>Please add new folder or page first to see something in here</div>
      </div>
      <div v-if="type == 'page'" class="text-center">
        <i class="fa fa-file-image-o fa-3x"></i>
        <h3 class="mt-3">NO PAGE FOUND</h3>
        <div>Please add new page first to see something in here</div>
      </div>


    </div>
  </b-col>
</template>
<script>
  export default {
    props: {
      type: String
    },
    name: 'EmptyState',
    data: function() {
      return {}
    },
    created() {},
    methods: {},
  }
</script>
<style>
  .empty-state {
    border: 2px dashed grey;
    border-radius: 4px;
    clear: both;

  }
</style>
