<template>
  <b-nav-item @click="handlePrintButton"><i class="fa fa-print"></i>
    <slot></slot>
  </b-nav-item>
</template>
<script>
  import {
    EventBus
  } from "@/reader/event.js";
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  export default {
    name: 'NavbarPrint',
    data: function() {
      return {
        printMode: true
      }
    },
    created() {},
    computed: {

    },
    methods: {
      handlePrintButton() {
        EventBus.$emit('handlePrintButton', true)
        this.printMode = true

      }
    },
  }
</script>
<style>
</style>
