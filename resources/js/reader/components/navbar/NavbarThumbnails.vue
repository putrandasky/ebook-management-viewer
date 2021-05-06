<template>
  <b-nav-item @click="handleThumbnailsButton"><i class="fa fa-th-large"></i>
    <slot></slot>
  </b-nav-item>

</template>
<script>
  import {
    mapState
  } from 'vuex'
  export default {
    name: 'NavbarThumbnails',
    data: function() {
      return {}
    },
    created() {},
    computed: {
      ...mapState({
        thumbnails: state => state.thumbnails,
      })
    },
    methods: {
      handleThumbnailsButton() {
        let state = !this.thumbnails
        this.$store.commit('SETTHUMBNAILS', state)
      }
    },
  }
</script>
<style>
</style>
