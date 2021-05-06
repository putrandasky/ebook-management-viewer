<template>
  <li class="nav-item">
    <ul class="navbar-nav " v-if="tooltip">
      <b-nav-item v-b-tooltip.hover.bottom="'Single Reader'" v-if="readerMode == 'single'" @click="handleSetReaderButton('facing')"><i class="fa fa-file"></i>
        <slot name="single"></slot>
      </b-nav-item>
      <b-nav-item v-b-tooltip.hover.bottom="'Facing Reader'" v-if="readerMode == 'facing'" @click="handleSetReaderButton('single')"><i class="fa fa-file fa-flip-horizontal"></i> <i class="fa fa-file "></i>
        <slot name="facing"></slot>
      </b-nav-item>
    </ul>
    <ul class="navbar-nav" v-if="!tooltip">
      <b-nav-item v-if="readerMode == 'single'" @click="handleSetReaderButton('facing')"><i class="fa fa-file"></i>
        <slot name="single"></slot>
      </b-nav-item>
      <b-nav-item v-if="readerMode == 'facing'" @click="handleSetReaderButton('single')"><i class="fa fa-file fa-flip-horizontal"></i> <i class="fa fa-file "></i>
        <slot name="facing"></slot>
      </b-nav-item>
    </ul>
  </li>
</template>
<script>
  import {
    mapState,
    mapActions
  } from 'vuex'
  export default {
    name: 'NavbarToggleView',
    props: {
      tooltip: {
        type: Boolean,
        default: true
      }

    },
    data: function() {
      return {}
    },
    created() {},
    computed: {
      ...mapState({
        readerMode: state => state.readerMode,
      })
    },
    methods: {
      ...mapActions(['changeReaderMode']),
      handleSetReaderButton(mode) {
        this.$root.$emit('bv::hide::tooltip', 'Single Reader')
        this.$root.$emit('bv::hide::tooltip', 'Facing Reader')
        this.changeReaderMode(mode)
      },

    },
  }
</script>
<style>
</style>
