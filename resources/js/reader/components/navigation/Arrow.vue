<template>
  <div v-if="!thumbnails" @click="handleClickArrowButton(direction)" class="position-absolute" :style="defineStyle(direction)"> <i :class="defineIcon(direction)"></i></div>
</template>
<script>
  import {
    mapState,
    mapActions
  } from 'vuex'
  export default {
    name: 'Arrow',
    props: {
      direction: String
    },
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

      defineStyle(direction) {
        return direction == 'right' ? "right:10px;top:50%;z-index:100;cursor:pointer" : "left:10px;top:50%;z-index:100;cursor:pointer"
      },
      defineIcon(direction) {
        return direction == 'right' ? "fa fa-chevron-right fa-3x" : "fa fa-chevron-left fa-3x"
      },
      handleClickArrowButton(direction) {

        this.updateCurrentPageByArrowButton(direction)

      }
    }
  }
</script>
<style>
</style>
