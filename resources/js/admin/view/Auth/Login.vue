<template>
  <b-container class="h-100 d-flex justify-content-center align-items-center" style="min-height:100vh">
    <b-card class="shadow-sm" style="width:300px">
      <b-card-title>
        Login Area
      </b-card-title>
      <hr>
      <b-form @submit.prevent="login">
        <b-form-group label="Email" :invalid-feedback="errors.email" :state="stateEmail">
          <b-input-group>
            <b-input-group-prepend>
              <b-input-group-text>
                <i class="fa fa-envelope"></i>
              </b-input-group-text>
            </b-input-group-prepend>
            <b-input type="email" v-model="input.email" :state="stateEmail">
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

        <b-btn type="submit" variant="danger" block>login</b-btn>
      </b-form>
    </b-card>
  </b-container>
</template>
<script>
  import {
    getError
  } from "@/admin/utils/helpers";
  import AuthService from "@/admin/services/AuthService";
  export default {
    name: 'Login',
    data: function() {
      return {
        input: {
          email: null,
          password: null,
        },
        errors: {
          email: null,
          password: null,
        }
      }
    },
    created() {},
    computed: {
      stateEmail() {
        return this.errors.email == 'no-error' ? true : this.errors.email ? false : null
      },
      statePassword() {
        return this.errors.password == 'no-error' ? true : this.errors.password ? false : null
      },
    },
    methods: {
      async login() {

        try {
          await AuthService.login(this.input);
          const authUser = await this.$store.dispatch("auth/getAuthUser");
          if (authUser) {
            this.$store.dispatch("auth/setGuest", {
              value: "isNotGuest"
            });
            this.$store.commit('auth/SET_TOAST', [true, 'WELCOME', 'You are logged in'])

            this.$router.push("/ebooks");

          } else {
            const error = Error(
              "Unable to fetch user after login, check your API settings."
            );
            error.name = "Fetch User";
            throw error;
          }
        } catch (error) {
          console.log(error.response);
          //   console.log(getError(error).email);
          if (error.response.status == 422) {
            this.toastError(error.response.data.message)

            this.errors.email = getError(error).email ? getError(error).email[0] : 'no-error';
            this.errors.password = getError(error).password ? getError(error).password[0] : 'no-error';
            return
          }
          this.toastError("Ooops, There's Something Error, Try Again Later")


        }
      },
    },
  }
</script>
<style>
</style>
