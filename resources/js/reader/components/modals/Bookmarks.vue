<template>
  <div>
    <b-modal title="Bookmark" v-model="isShowBookmarkModal" @close="SETSHOWBOOKMARKSMODAL(false)" @show="getData" modal-class="bookmark-modal" :hide-footer="true" :no-close-on-backdrop="true" centered size="lg">
      <div class="mb-3">
        <b-btn variant="secondary" class="float-right " size="sm" @click="addCurrentPage"> Add Current Page</b-btn>
        <b-btn variant="outline-secondary " size="sm" :disabled="selectedCheckbox.length == 0" @click="handleDeleteButton"><i class="fa fa-trash"></i></b-btn>
      </div>
      <div class="w-100 text-center" v-if="data.length == 0">
        There is no bookmark list
      </div>
      <div>
        <b-table v-if="data.length > 0" :items="data" :fields="fields">
          <template #head(selectAble)>
            <b-btn variant="light" class="text-dark border" size="sm" @click="handleSelectAll" v-if="data.length !== selectedCheckbox.length">Select All</b-btn>
            <b-btn variant="light" class="text-dark border" size="sm" @click="handleDeselectAll" v-if="data.length == selectedCheckbox.length">Deselect All</b-btn>
          </template>
          <template #cell(selectAble)="data">
            <b-form-checkbox v-model="selectedCheckbox" :value="data.item.index"></b-form-checkbox>
          </template>
          <template #cell(notes)="data">
            <b-btn variant="outline-secondary" size="sm" @click="openNotes(data)">
              <i v-if="!data.item.notes" class="fa fa-plus"></i>
              <i v-if="data.item.notes" class="fa fa-edit"></i>
            </b-btn>
          </template>
          <template #cell(pageLabel)="data">
            <b-link @click.prevent="goTo(data.item)">{{data.item.pageLabel}}</b-link>
          </template>
        </b-table>
      </div>
    </b-modal>
    <b-modal v-model="showNoteModal" @ok="saveNote" :no-close-on-backdrop="true" centered ok-variant="secondary" cancel-variant="outline-secondary" ok-title="Save">
      <template v-slot:modal-title>
        <b>
          Page : {{notesModal.title}}
        </b>
      </template>
      <b-form-textarea id="textarea" v-model="notesModal.content" placeholder="Enter something..." rows="3" max-rows="6"></b-form-textarea>
    </b-modal>
  </div>
</template>
<script>
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  export default {
    name: 'Bookmarks',
    data: function() {
      return {
        selectedCheckbox: [],
        showNoteModal: false,
        notesModal: {
          title: '',
          content: '',
          index: null
        },
        data: [],
        fields: [{
            key: 'selectAble',
            label: 'Select',
            class: 'text-center',
            thStyle: {
              minWidth: '100px',
              width: '100px',
            }
          },
          {
            key: 'notes',
            label: 'Notes',
            class: 'text-center',
            thStyle: {
              minWidth: '75px',
              width: '75px',

            }
          },
          {
            key: 'bookTitle',
            label: 'Book',
          },
          {
            key: 'pageLabel',
            label: 'Page',
            thStyle: {
              minWidth: '100px'
            }
          },
        ]
      }
    },
    created() {},
    computed: {

      ...mapState({
        isShowBookmarkModal: state => state.showBookmarksModal,
        ebook: state => state.ebook,
        currentIndex: state => state.currentIndex,
      })
    },
    methods: {
      ...mapMutations(['SETSHOWBOOKMARKSMODAL']),
      ...mapActions(['updateCurrentPageByPageLabel', 'changeReaderMode']),
      goTo(data) {
        this.SETSHOWBOOKMARKSMODAL(false)
        this.changeReaderMode('single')
        if (data.slug == this.$route.params.slug) {
          this.updateCurrentPageByPageLabel(data.pageLabel)
          return
        }
        this.$router.push({
          name: 'page',
          params: {
            slug: data.slug,
            page: data.pageLabel,
          }
        })
      },
      handleSelectAll() {
        for (let i = 0; i < this.data.length; i++) {
          this.selectedCheckbox.push(this.data[i].index)
        }
      },
      handleDeselectAll() {
        this.selectedCheckbox = []
      },
      handleDeleteButton() {
        let tempData = this.data
        for (let i = 0; i < this.selectedCheckbox.length; i++) {
          let findIndex = this.data.map(e => e.index).indexOf(this.selectedCheckbox[i])
          this.data.splice(findIndex, 1)
        }
        this.selectedCheckbox = []
        localStorage[`Preferences/Bookmarks`] = JSON.stringify(this.data)
      },
      openNotes(data) {
        console.log(data);
        this.notesModal.index = data.index
        this.notesModal.title = data.item.pageLabel
        this.notesModal.content = data.item.notes
        this.showNoteModal = true
      },
      saveNote() {
        this.data[this.notesModal.index].notes = this.notesModal.content
        localStorage[`Preferences/Bookmarks`] = JSON.stringify(this.data)
      },
      addCurrentPage() {
        let newData = {
          bookTitle: this.ebook.name,
          slug: this.ebook.slug,
          index: window.btoa(this.ebook.slug + this.$route.params.page),
          pageLabel: this.$route.params.page,
          notes: ''
        }
        this.data.push(newData)
        localStorage[`Preferences/Bookmarks`] = JSON.stringify(this.data)
      },
      getData() {
        let data = localStorage.getItem(`Preferences/Bookmarks`)
        if (!data) {
          return
        }
        let parsedData = JSON.parse(data)
        this.data = parsedData

      }
    },
  }
</script>
<style>
</style>
