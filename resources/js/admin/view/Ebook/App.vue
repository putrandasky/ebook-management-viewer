<template>
  <div id="admin-area" class="pb-3">
    <navbar></navbar>
    <router-view></router-view>
  </div>
</template>
<script>
  import Navbar from '@/admin/components/navbar/Navbar'
  export default {
    name: 'App',
    components: {
      Navbar
    },
    data: function() {
      return {};
    },
    created() {},
    methods: {}
  };
</script>
