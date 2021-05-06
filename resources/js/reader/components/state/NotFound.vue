<template>
  <b-col cols="12 ">
    <div class="p-5 mt-3 empty-state d-flex justify-content-center ">
      <div class="text-center my-5">
        <h2>404</h2>
        <h3 class="mt-3">NOT FOUND</h3>
        <div class="mb-3">The page you are looking for is not found</div>

      </div>

    </div>
  </b-col>
</template>
<script>
  export default {
    name: 'NotFound',
    data: function() {
      return {}
    },
    created() {},
    methods: {
      goToEbookList() {
        this.$router.push({
          name: 'ebooks',
        })
      },
    },
  }
</script>
<style>
</style>
