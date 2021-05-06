<template>
  <b-navbar-nav>
    <b-nav-form>
      <b-input-group>
        <b-input-group-prepend>
          <b-button size="sm" :disabled="input.length < 3" variant="outline-secondary" @click="handleSearchContent" aria-controls="sidebar-search"><i class="fa fa-search"></i></b-button>
        </b-input-group-prepend>

        <b-input size="sm" v-model="input" v-on:keydown.enter.prevent="handleInputEnter" placeholder="Search Title (min 3 letters)"></b-input>
        <b-input-group-append>
          <b-button size="sm" :disabled="input.length < 3" variant="dark" @click="handleCancelSearchContent" aria-controls="sidebar-search"><i class="fa fa-close"></i></b-button>
        </b-input-group-append>
      </b-input-group>
    </b-nav-form>
  </b-navbar-nav>
</template>
<script>
  import {
    EventBus
  } from "@/reader/event.js";
  export default {
    name: 'NavbarSearch',
    data: function() {
      return {
        input: ''
      }
    },
    created() {},
    methods: {
      handleSearchContent(e) {
        if (this.input.length < 3) {
          return
        }
        this.$root.$emit('bv::toggle::collapse', 'sidebar-search')

        EventBus.$emit('handleSearchContent', {
          state: true,
          input: this.input
        })
      },
      handleInputEnter() {
        if (this.input.length < 3) {
          return
        }
        this.$root.$emit('bv::toggle::collapse', 'sidebar-search')
        EventBus.$emit('handleSearchContent', {
          state: true,
          input: this.input
        })
      },
      handleCancelSearchContent() {
        if (this.input.length < 3) {
          return
        }
        this.input = ''
        EventBus.$emit('handleCancelSearchContent')
      },
    },
  }
</script>
<style>
</style>
