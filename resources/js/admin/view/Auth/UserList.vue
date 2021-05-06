<template>
  <b-card class="shadow-sm">
    <b-card-title>
      Users List
      <b-btn size="sm" class="float-right" variant="outline-secondary" @click="showUserRegisterModal">Add User</b-btn>

    </b-card-title>
    <b-table small thead-class="thead-light" :fields="field" :items="items">
      <template v-slot:cell(no)="data">
        {{data.index + 1}}
      </template>
      <template v-slot:cell(action)="data">
        <b-btn size="sm" variant="danger" @click="editUser(data.index)"><i class="fa fa-trash"></i></b-btn>
        <b-btn size="sm" variant="secondary" @click="deleteUser(data.index)"><i class="fa fa-pencil"></i></b-btn>
      </template>
    </b-table>
  </b-card>
</template>
<script>
  import {
    getError
  } from "@/admin/utils/helpers";
  import UserService from "@/admin/services/UserService";
  export default {
    name: 'UserList',
    data: function() {
      return {
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
              //   minWidth: '150px',
              //   width: '150px'
            },

          },
          {
            key: 'action',
            label: '',
            class: 'text-center',
            thStyle: {
              //   minWidth: '150px',
              //   width: '150px'
            },

          },
        ]
      }
    },
    created() {
      this.getData(1)
    },
    methods: {
      editUser() {

      },
      deleteUser() {

      },
      showUserRegisterModal() {
        this.$bvModal.show('user-register-modal')

      },
      getData() {
        UserService.getUsers(1)
          .then((response) => {
            console.log(response.data);
            this.items = response.data.data
          })
          .catch((error) => (console.log(getError(error))));
      }
    },
  }
</script>
<style>
</style>
