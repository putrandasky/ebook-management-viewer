<template>
  <div class="ffolder big gray mx-sm-3 my-lg-0 my-md-3  my-0">
    <span>{{pagesCount}} Pages</span>
    <br>
    <div class="text-light">{{name}}</div>
  </div>
</template>
<script>
  export default {
    name: 'ListItemFolder',
    props: {
      pagesCount: Number,
      name: String
    },
    data: function() {
      return {}
    },
    created() {},
    methods: {},
  }
</script>
<style>
</style>
