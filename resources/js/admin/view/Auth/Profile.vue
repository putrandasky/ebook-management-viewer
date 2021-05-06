<template>
  <b-container fluid class="pt-5">
    <b-row class="d-flex justify-content-center">
      <b-col sm="10" md="8" lg="5">
        <b-card class="mb-3">
          <b-form @submit.prevent="state == 0? changeProfile():changePassword()">
            <div v-show="state == 0">
              <h4>My Profile</h4>
              <hr>
              <b-form-group label-cols-sm="4" label-cols-lg="4" label-align="right" content-cols-sm content-cols-lg="8" label="Email">
                <b-form-input disabled v-model="input.email"></b-form-input>
              </b-form-group>
              <b-form-group label-cols-sm="4" label-cols-lg="4" label-align="right" content-cols-sm content-cols-lg="8" label="Username" :invalid-feedback="errors.name" :state="stateName">
                <b-form-input v-model="input.name" :state="stateName"></b-form-input>
              </b-form-group>
            </div>
            <div v-show="state == 1">
              <h4>Change Password</h4>
              <hr>
              <b-form-group label-cols-sm="4" label-cols-lg="4" label-align="right" content-cols-sm content-cols-lg="8" label="Current Password" :invalid-feedback="errors.current_password" :state="stateCurrentPassword">
                <b-form-input type="password" v-model="input2.current_password" :state="stateCurrentPassword"></b-form-input>
              </b-form-group>
              <b-form-group label-cols-sm="4" label-cols-lg="4" label-align="right" content-cols-sm content-cols-lg="8" label="New Password" :invalid-feedback="errors.password" :state="statePassword">
                <b-form-input type="password" v-model="input2.password" :state="statePassword"></b-form-input>
              </b-form-group>
              <b-form-group label-cols-sm="4" label-cols-lg="4" label-align="right" content-cols-sm content-cols-lg="8" label="Confirm New Password">
                <b-form-input type="password" v-model="input2.password_confirmation"></b-form-input>
              </b-form-group>
            </div>
            <div class="w-100">
              <b-btn type="submit" variant="danger" size="sm" class=" ml-2 float-right">
                Update Data
              </b-btn>

              <b-btn variant="outline-secondary" size="sm" class="float-right" v-show="state == 0" @click="state = 1">
                Change Password
              </b-btn>
              <b-btn variant="outline-secondary" size="sm" class="float-right" v-show="state == 1" @click="state = 0">
                Change Profile
              </b-btn>
            </div>
          </b-form>
        </b-card>
        <b-btn size="sm" variant="outline-secondary" @click="goToEbookList()">
          <i class="fa fa-arrow-left"></i>
          Ebook List</b-btn>

      </b-col>
    </b-row>
  </b-container>
</template>
<script>
  import {
    mapState
  } from 'vuex'
  import {
    getError
  } from "@/admin/utils/helpers";
  import AuthService from "@/admin/services/AuthService";
  export default {
    name: 'Profile',
    data: function() {
      return {
        state: 0,
        input: {
          name: '',
          email: ''
        },
        input2: {
          current_password: '',
          password: '',
          password_confirmation: ''
        },
        errors: {
          name: '',
          current_password: '',
          password: '',
        }
      }
    },
    created() {},
    mounted() {
      //   console.log(this.$store.state.auth.user.name);
      this.input.name = this.user.name
      this.input.email = this.user.email
    },
    computed: {
      ...mapState('auth', {
        user: state => state.user,
      }),
      stateName() {
        return this.errors.name == 'no-error' ? true : this.errors.name ? false : null
      },
      stateCurrentPassword() {
        return this.errors.current_password == 'no-error' ? true : this.errors.current_password ? false : null
      },
      statePassword() {
        return this.errors.password == 'no-error' ? true : this.errors.password ? false : null
      },

    },

    methods: {
      goToEbookList() {
        this.$router.push({
          name: 'ebooks',
        })
      },
      changeProfile() {
        AuthService.updateUser(this.input)
          .then((response) => {
            this.toastSuccess('Profile Updated')
            this.errors.name = ''
          })
          .catch((error) => {
            console.log(error.response);
            this.errors.name = getError(error).name ? getError(error).name[0] : 'no-error';

          });
      },
      changePassword() {
        AuthService.updatePassword(this.input2)
          .then((response) => {
            this.toastSuccess('Password Changed')
            this.errors.current_password = ''
            this.errors.password = ''
          })
          .catch((error) => {
            console.log(error.response);
            // if (error.response.status == 422) {
            this.toastError(error.response.data.message)

            this.errors.name = getError(error).name ? getError(error).name[0] : 'no-error';
            this.errors.current_password = getError(error).current_password ? getError(error).current_password[0] : 'no-error';
            this.errors.password = getError(error).password ? getError(error).password[0] : 'no-error';

            return
            // }
            // this.toastError("Ooops, There's Something Error")

          });
      }
    },
  }
</script>
<style>
</style>
