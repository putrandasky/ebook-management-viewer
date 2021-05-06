<template>
  <b-nav-item @click="handleDownloadPdfButton"><i class="fa fa-file-pdf-o"></i>
    <slot></slot>
  </b-nav-item>
</template>
<script>
  import {
    EventBus
  } from "@/reader/event.js";
  export default {
    name: 'NavbarDownloadPdf',
    data: function() {
      return {}
    },
    created() {},
    methods: {
      handleDownloadPdfButton() {
        EventBus.$emit('setShowPdfOptionModal', true)
      }
    },
  }
</script>
<style>
</style>
