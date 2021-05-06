<template>
  <b-overlay blur="" :show="isLoading" rounded="sm" spinner-variant="light" variant="dark" z-index="2000" style="height:100vh">
    <b-toast id="main-toast" @hidden="resetToast" toast-class="main" variant="secondary" solid static :visible="toast.show" :auto-hide-delay="3000">
      <template #toast-title>
        <b>{{toast.title}}</b>
      </template>
      {{toast.content}}
    </b-toast>
    <router-view></router-view>
  </b-overlay>
</template>
<script>
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  export default {
    name: 'App',
    data: function() {
      return {};
    },
    created() {},
    computed: {
      ...mapState('auth', {
        isLoading: state => state.loading,
        toast: state => state.toast,
      }),
    },
    methods: {
      resetToast() {
        this.$store.commit('auth/SET_TOAST', [false, '', ''])
      }
    }
  };
</script>



<style lang="scss">
  @import '../../../sass/app.scss';
</style>
