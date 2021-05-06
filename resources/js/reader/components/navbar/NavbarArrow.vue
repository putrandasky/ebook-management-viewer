<template>
  <b-nav-item @click="handleClickArrowButton">
    <i v-if="direction == 'right'" class="fa fa-chevron-right"></i>
    <i v-if="direction == 'left'" class="fa fa-chevron-left"></i>
  </b-nav-item>
</template>
<script>
  import {
    mapState,
    mapActions
  } from 'vuex'
  export default {
    name: 'NavbarArrow',
    props: ['direction'],
    data: function() {
      return {}
    },
    created() {},
    computed: {
      ...mapState({
        thumbnails: state => state.thumbnails,
        readerMode: state => state.readerMode,

      })
    },
    methods: {
      ...mapActions(['updateCurrentPageByArrowButton', 'changeReaderMode']),
      async handleClickArrowButton() {

        await this.updateCurrentPageByArrowButton(this.direction)
        if (this.thumbnails) {
          this.changeReaderMode('single')

        }
      }
    },
  }
</script>
<style>
</style>
