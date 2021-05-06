<template>
  <b-card style="max-width:246px" class="shadow-sm" no-body img-top>
    <b-card-img fluid :src="`/storage/book/${ebookId}/tmb256-${originalName}`"></b-card-img>
  </b-card>
</template>
<script>
  export default {
    name: 'ListItemFile',
    props: {

      ebookId: Number,
      originalName: String
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
