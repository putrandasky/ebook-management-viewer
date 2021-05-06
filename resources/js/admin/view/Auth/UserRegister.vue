<template>
  <b-modal title="Add New User" id="user-register-modal" hide-footer>
    <b-form @submit.prevent="registerUser">
      <b-form-group label="Username" :invalid-feedback="errors.name" :state="stateName">
        <b-input-group>
          <b-input-group-prepend>
            <b-input-group-text>
              <i class="fa fa-user"></i>
            </b-input-group-text>
          </b-input-group-prepend>
          <b-input type="text" v-model="input.name" :state="stateName">
          </b-input>
        </b-input-group>
      </b-form-group>
      <b-form-group label="Email" :invalid-feedback="errors.email" :state="stateEmail">
        <b-input-group>
          <b-input-group-prepend>
            <b-input-group-text>
              <i class="fa fa-envelope"></i>
            </b-input-group-text>
          </b-input-group-prepend>
          <b-input type="text" v-model="input.email" :state="stateEmail">
          </b-input>
        </b-input-group>
      </b-form-group>
      <b-form-group label="Password" :invalid-feedback="errors.password" :state="statePassword">
        <b-input-group>
          <b-input-group-prepend>
            <b-input-group-text>
              <i class="fa fa-lock"></i>
            </b-input-group-text>
          </b-input-group-prepend>
          <b-input type="password" v-model="input.password" :state="statePassword"></b-input>
        </b-input-group>
      </b-form-group>
      <b-form-group label="Confirm Password">
        <b-input-group>
          <b-input-group-prepend>
            <b-input-group-text>
              <i class="fa fa-lock"></i>
            </b-input-group-text>
          </b-input-group-prepend>
          <b-input type="password" v-model="input.password_confirmation"></b-input>
        </b-input-group>
      </b-form-group>

      <b-btn type="submit" variant="danger" class="float-right">Submit</b-btn>
    </b-form>
  </b-modal>
</template>
<script>
  import {
    getError
  } from "@/admin/utils/helpers";
  import AuthService from "@/admin/services/AuthService";
  export default {
    name: 'UserRegister',
    data: function() {
      return {
        me: {},
        input: {
          name: null,
          email: null,
          password: null,
          password_confirmation: null,
          isAdmin: 1
        },
        errors: {
          name: null,
          email: null,
          password: null,
          password_confirmation: null,
          isAdmin: 1
        }
      }
    },
    created() {},
    mounted() {
      this.me = this.$store.state.auth.user

    },
    computed: {
      stateName() {
        return this.errors.name == 'no-error' ? true : this.errors.name ? false : null
      },
      stateEmail() {
        return this.errors.email == 'no-error' ? true : this.errors.email ? false : null
      },
      statePassword() {
        return this.errors.password == 'no-error' ? true : this.errors.password ? false : null
      },
    },
    methods: {
      registerUser(e) {
        e.preventDefault()
        AuthService.registerUser(this.input)
          .then((response) => {
            console.log(response.data)
            this.$emit('onRegistered')
            let input = {
              name: null,
              email: null,
              password: null,
              password_confirmation: null,
              isAdmin: 1
            }
            this.input = input
            this.$bvModal.hide('user-register-modal')
            this.errors.name = ''
            this.errors.email = ''
            this.errors.password = ''
            this.toastSuccess('New User Added')
            this.$store.commit('auth/SET_USER', this.me)
          })
          .catch((error) => {
            this.toastError(error.response.data.message)

            this.errors.name = getError(error).name ? getError(error).name[0] : 'no-error';
            this.errors.email = getError(error).email ? getError(error).email[0] : 'no-error';
            this.errors.password = getError(error).password ? getError(error).password[0] : 'no-error';
          });
      },
    },
  }
</script>
<style>
</style>
