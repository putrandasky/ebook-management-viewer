<template>
  <b-navbar sticky type="light" variant="light" class="shadow-sm " style="z-index:1000">
    <b-navbar-brand href="#">Ebook Reader</b-navbar-brand>
    <b-navbar-nav class="ml-auto ">
      <b-nav-item-dropdown no-caret right>

        <b-dropdown-item href="#" @click="goToProfile()">Profile</b-dropdown-item>
        <b-dropdown-item href="#" @click="signOut()">Sign Out</b-dropdown-item>
        <template #button-content>
          <b-btn size="sm" variant="outline-secondary">
            <i class="fa fa-user"></i> <b>{{user.name}}</b>
          </b-btn>
        </template>
      </b-nav-item-dropdown>
    </b-navbar-nav>
  </b-navbar>
</template>
<script>
  import {
    mapGetters
  } from 'vuex'
  import {
    getError
  } from "@/admin/utils/helpers";
  import AuthService from "@/admin/services/AuthService";
  export default {
    name: 'Navbar',
    components: {

    },
    data: function() {
      return {}
    },
    created() {},

    computed: {
      ...mapGetters({
        user: "auth/authUser"
      })
    },
    methods: {
      goToProfile() {
        this.$router.push({
          name: 'profile',

        })
      },
      signOut() {
        AuthService.logout()
          .then((response) => {
            this.$store.commit('auth/SET_TOAST', [true, 'GOOD BYE', 'You are log out'])
            this.$router.push({
              name: 'login',
            })
          })
          .catch((error) => (console.log(getError(error))));
      }
    },
  }
</script>
<style>
</style>
