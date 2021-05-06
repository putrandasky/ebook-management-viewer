<template>
  <b-modal :title="title" id="ConfirmationModal" ok-title="Submit" @ok="$emit('onSubmit') " ok-variant="secondary" cancel-variant="outline-secondary">
    <slot></slot>
  </b-modal>
</template>
<script>
  export default {
    name: 'ConfirmationModal',
    props: {
      title: String
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
