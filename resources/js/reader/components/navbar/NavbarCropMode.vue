<template>
  <b-navbar-nav>
    <b-btn variant="outline-secondary" size="sm" @click="SETCROPMODE(false)">Cancel</b-btn>
    <b-btn class="ml-3" variant="secondary" size="sm" @click="handleCropButton">Crop</b-btn>
  </b-navbar-nav>
</template>
<script>
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  import {
    EventBus
  } from "@/reader/event.js";
  export default {
    name: 'NavbarCropMode',
    data: function() {
      return {}
    },
    created() {},

    methods: {
      ...mapMutations(['SETCROPMODE']),
      handleCropButton() {
        EventBus.$emit('showCropPreview')
      }
    },
  }
</script>
<style>
</style>
