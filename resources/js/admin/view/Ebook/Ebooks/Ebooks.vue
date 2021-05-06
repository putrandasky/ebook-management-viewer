<template>
  <b-container class="pt-3">
    <b-row class="text-right">
      <b-col cols="12">
        <b-btn class="float-right" size="sm" variant="secondary" @click="onCreate">Add Ebook</b-btn>
      </b-col>

    </b-row>
    <b-row v-if="ebooks.length == 0 && dataLoaded" class=" pt-5 d-flex justify-content-center align-items-center">
      <empty-state type="book"></empty-state>

    </b-row>
    <b-row v-if="ebooks.length > 0 && dataLoaded">
      <b-col v-for="(v,i) in ebooks" :key="i" class="mt-3" lg="3" md="4" sm="6" cols="12">
        <b-card class="shadow-sm" no-body img-top>
          <b-overlay blur="" :show="showOverlay == i+1" variant="dark" rounded="top" v-on:mouseleave.native="showOverlay = 0">
            <template #overlay>
              <item-buttons type="book" @onDelete="onDelete(i,v.chapters_count)" @onEdit="onEdit(i)" @onLink="handleCliboard(v.slug)"></item-buttons>
            </template>
            <div v-on:mouseenter="showOverlay = i + 1">
              <b-card-img v-if="v.chapters_count > 0 && v.chapters[0].type =='file' " fluid :src="`/storage/book/${v.id}/${v.chapters[0].pages[0].original_name}`"></b-card-img>
              <item-no-cover v-if="v.chapters_count == 0 || v.chapters[0].type == 'folder'">
              </item-no-cover>
            </div>
          </b-overlay>
          <div class="mx-3 mt-3"><small> Uploaded: {{v.created_at}}</small>
          </div>
          <div class="mx-3">
            <h5>{{v.name}}</h5>
          </div>
          <div class="mb-3 px-3 w-100">
            <b-button-group class="btn-block">
              <b-btn variant="outline-secondary" size="sm" @click="handleOpen(v.slug)">Open</b-btn>
              <b-btn variant="secondary" size="sm" @click="openUrl(v.slug)" :disabled="v.chapters_count == 0 || v.chapters[0].type == 'folder'"><i class="fa fa-external-link"></i> View</b-btn>
            </b-button-group>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :title="state == 'create'?'New Book' :'Update Book' " id="EbookModal" ok-title="Submit" @ok.prevent="state == 'create'? createEbook() : updateEbook() " ok-variant="secondary" cancel-variant="outline-secondary">
      <b-form @submit.prevent="registerUser">
        <label for="book-name">Book Name</label>
        <b-input required min="5" class="mb-2" v-model="input.bookName"></b-input>
        <label for="slug-name">Slug</label>
        <b-input :disabled="!input.bookName" v-model="input.slug" :placeholder="slugPlaceholder !== ''?`Slug would be '${slugPlaceholder}', type here to change`:'type book name first'"></b-input>
        <small>Slug will be used in link address</small>
      </b-form>
    </b-modal>
    <confirmation-modal title="Delete Book Confirmation" @onSubmit="deleteBook()">
      Are you sure to delete this book?

    </confirmation-modal>
  </b-container>
</template>
<script>
  import ItemButtons from '@/admin/components/List/ListItemButtons'
  import ItemNoCover from '@/admin/components/List/ListItemNoCover'
  import ConfirmationModal from '@/admin/components/modals/ConfirmationModal.vue'
  import EmptyState from '@/admin/components/EmptyState.vue'

  import {
    mapState
  } from 'vuex'
  export default {
    name: 'Ebooks',
    components: {
      ItemButtons,
      ItemNoCover,
      ConfirmationModal,
      EmptyState

    },
    data: function() {
      return {
        dataLoaded: false,
        state: '',
        showOverlay: 0,
        ebooks: [],
        selectedEbookId: null,
        selectedEbookIndex: null,
        input: {
          bookName: '',
          slug: '',
        },
        slugPlaceholder: ''
      }
    },
    created() {
      this.getData()
    },
    watch: {
      'input.slug': function(newVal, oldVal) {
        this.input.slug = newVal.replace(/\s/g, '-')
      },
      'input.bookName': function(newVal, oldVal) {

        if (newVal == '') {
          this.slugPlaceholder = ''
          this.input.slug = ''
          return
        }
        this.slugPlaceholder = newVal.toLowerCase().replace(/\s/g, '-')
      }

    },
    methods: {


      handleOpen(slug) {
        this.$router.push({
          name: 'chapters',
          params: {
            slug: slug
          }
        })
      },
      onEdit(i) {
        this.input.bookName = this.ebooks[i].name
        this.input.slug = this.ebooks[i].slug
        this.selectedEbookId = this.ebooks[i].id
        this.state = 'edit'
        this.selectedEbookIndex = i
        this.$bvModal.show('EbookModal')

      },
      onCreate() {
        this.state = 'create'
        this.$bvModal.show('EbookModal')
      },
      openUrl(slug) {
        let protocol = window.location.protocol + '//'
        let host = window.location.host
        let url = protocol + host + '/reader/' + slug
        window.open(url, "_blank")
      },
      createEbook() {
        if (this.input.slug == '') {
          this.input.slug = this.slugPlaceholder
        }
        axios.post(`/api/admin/ebook`, this.input)
          .then((response) => {
            this.ebooks.push(response.data)
            this.input.bookName = ''
            this.input.slug = ''
            this.state = ''
            this.$bvModal.hide('EbookModal')
            this.toastSuccess('New Book Created')

          })
          .catch((error) => {
            console.log(error);
            this.toastError(error.response.data.errors.bookName[0])
          })
      },
      updateEbook() {
        if (this.input.slug == '') {
          this.input.slug = this.slugPlaceholder
        }
        axios.put(`/api/admin/ebook/${this.selectedEbookId}`, this.input)
          .then((response) => {
            this.toastSuccess(response.data.message)
            console.log(response.data)
            this.ebooks[this.selectedEbookIndex].name = this.input.bookName
            this.ebooks[this.selectedEbookIndex].slug = this.input.slug
            this.input.bookName = ''
            this.input.slug = ''
            this.state = ''
            this.$bvModal.hide('EbookModal')
          })
          .catch((error) => {
            console.log(error);
            if (typeof error.response.data.errors.bookName !== 'undefined') {
              this.toastError(error.response.data.errors.bookName[0])
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      },
      getData() {
        axios.get(`/api/admin/ebooks`)
          .then((response) => {
            this.ebooks = response.data
            this.dataLoaded = true

          })
          .catch((error) => {
            console.log(error);
          })
      },
      onDelete(i, hasChapter) {
        this.selectedEbookId = this.ebooks[i].id
        this.selectedEbookIndex = i
        if (hasChapter > 0) {
          this.$bvToast.toast('Delete all chapters and pages in this book first', {
            title: `ALERT!`,
            variant: 'warning',
            solid: true
          })
          return
        }
        this.$bvModal.show('ConfirmationModal')

      },
      deleteBook() {
        axios.delete(`/api/admin/ebook/${this.selectedEbookId}`)
          .then((response) => {
            this.ebooks.splice(this.selectedEbookIndex, 1)
            this.toastSuccess(response.data.message)

          })
          .catch((error) => {
            console.log(error);
            this.toastError("Ooops, There's Something Error, Try Again Later")
          })
      },
      handleCliboard(slug) {
        let protocol = window.location.protocol + '//'
        let host = window.location.host
        let url = protocol + host + '/reader/' + slug
        this.copyTextToClipboard(url)

        this.buttonCopyText = '<i class="fa fa-check"></i> Copied!'
        setTimeout(() => {
          this.buttonCopyText = "Click to Copy"
        }, 500);
      },
      fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
          console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
      },

      copyTextToClipboard(text) {
        if (!navigator.clipboard) {
          this.fallbackCopyTextToClipboard(text);
          return;
        }
        let self = this
        navigator.clipboard.writeText(text).then(function() {
          console.log('Async: Copying to clipboard was successful!');
          self.toastSuccess('Book Reader Link Copied')
        }, function(err) {
          console.error('Async: Could not copy text: ', err);
        });
      }
    },
  }
</script>
<style>
</style>
