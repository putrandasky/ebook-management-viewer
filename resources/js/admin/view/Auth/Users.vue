<template>
  <b-container fluid class="pt-5">
    <b-row class="d-flex justify-content-center">

      <user-register @onRegistered="getData"></user-register>
      <confirmation-modal title="Delete User" @onSubmit="deleteUser()">
        Are you sure to delete this user?
      </confirmation-modal>

      <b-col sm="10" md="8" lg="6">

        <b-card class="shadow-sm mb-3">
          <b-card-title>
            Users List
            <b-btn size="sm" class="float-right" variant="secondary" @click="showUserRegisterModal">Add User</b-btn>

          </b-card-title>
          <b-table small thead-class="thead-light" :fields="field" :items="items">
            <template v-slot:cell(no)="data">
              {{data.index + 1}}
            </template>
            <template v-slot:cell(action)="data">
              <b-btn v-if="data.item.id != $store.state.auth.user.id" size="sm" variant="danger" @click="showDeleteUserModal(data.item.id,data.index)"><i class="fa fa-trash"></i></b-btn>
              <!-- <b-btn size="sm" variant="secondary" ><i class="fa fa-pencil"></i></b-btn> -->
            </template>
          </b-table>
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
    getError
  } from "@/admin/utils/helpers";
  import UserService from "@/admin/services/UserService";
  import AuthService from "@/admin/services/AuthService";
  import UserRegister from './UserRegister.vue'
  import ConfirmationModal from '@/admin/components/modals/ConfirmationModal.vue'

  export default {
    components: {
      UserRegister,
      ConfirmationModal
    },
    name: 'User',
    data: function() {
      return {
        current: {
          userId: null,
          index: null

        },
        items: [],
        field: [{
            key: 'no',
            label: 'No',
            class: 'text-center',
            thStyle: {
              minWidth: '30px',
              width: '30px'
            }
          },
          {
            key: 'name',
            label: 'Name',
            tdClass: 'truncate-cell',
            thStyle: {
              minWidth: '125px',
              minWidth: '125px'
            }
          },
          {
            key: 'email',
            label: 'Email',
            thStyle: {
              // minWidth: '150px',
              // width: '150px'
            },

          },
          {
            key: 'action',
            label: '',
            class: 'text-center',
            thStyle: {
              // minWidth: '150px',
              // width: '150px'
            },

          },
        ]
      }
    },
    created() {
      this.getData(1)

    },
    mounted() {},
    methods: {
      showUserRegisterModal() {
        this.$bvModal.show('user-register-modal')

      },
      goToEbookList() {
        this.$router.push({
          name: 'ebooks',
        })
      },
      getData() {
        UserService.getUsers(1)
          .then((response) => {
            console.log(response.data);
            this.items = response.data.data
          })
          .catch((error) => (console.log(getError(error))));
      },
      showDeleteUserModal(id, i) {
        this.current.id = id
        this.current.index = i
        this.$bvModal.show('ConfirmationModal')

      },
      deleteUser() {
        AuthService.deleteUser(this.current.id)
          .then((response) => {
            console.log(response.data);
            this.items.splice(this.current.index, 1)
            this.$bvModal.hide('ConfirmationModal')
            this.toastSuccess('User Deleted')
          })
          .catch((error) => {
              this.toastError("Ooops, There's Something Error")
            }

          );
      }
    },
  }
</script>
<style>
</style>
