<template>
  <b-container class="pt-3">
    <div class="w-100 ">
      <b-btn size="sm" variant="outline-secondary" class="shadow-sm" @click="previousPage()">Back</b-btn>
      <b-btn size="sm" variant="danger" class="shadow-sm float-right" v-b-toggle.sidebar-page>Add Pages</b-btn>
    </div>

    <b-row v-if="pages.length == 0 && dataLoaded" class="pt-5 d-flex justify-content-center align-items-center">
      <empty-state type="page"></empty-state>

    </b-row>
    <list-item v-if="pages.length > 0 && dataLoaded" :items="pages" @endDragging="endDragging">
      <template v-slot:buttons="data">
        <item-buttons @onDetail="openDetail(data.item.id)" @onDelete="onDelete(data.index)"></item-buttons>
      </template>
      <template v-slot:items="data">
        <item-file :ebookId="chapter.ebook_id" :originalName="data.item.original_name"></item-file>
      </template>

    </list-item>
    <pages-sidebar @newFileUploaded="insertNewFile($event)"></pages-sidebar>
    <confirmation-modal title="Delete Page Confirmation" @onSubmit="deletePage()">
      Are you sure to delete this page in this chapter?
    </confirmation-modal>
  </b-container>

</template>

<script>
  import PagesSidebar from './PagesSidebar.vue'
  import ListItem from '@/admin/components/List/ListItem'
  import ItemFile from '@/admin/components/List/ListItemFile'
  import ItemButtons from '@/admin/components/List/ListItemButtons'
  import ConfirmationModal from '@/admin/components/modals/ConfirmationModal.vue'
  import EmptyState from '@/admin/components/EmptyState.vue'

  export default {
    components: {
      PagesSidebar,
      ListItem,
      ItemFile,
      ConfirmationModal,
      ItemButtons,
      EmptyState

    },
    name: 'Pages',
    ConfirmationModal,
    data: function() {
      return {
        dataLoaded: false,
        showOverlay: 0,
        pages: [],
        chapter: [{
          pages: []
        }],
        selected: {
          page: {},
          index: null
        }
      }
    },
    created() {
      this.getData()
    },
    watch: {


    },
    methods: {

      previousPage() {
        this.$router.push({
          name: 'chapters',
          params: {
            slug: this.$route.params.slug,
          }
        })
      },
      openDetail(pagesId) {

        this.$router.push({
          name: 'pageDetail',
          params: {
            pageId: pagesId,
          }
        })
      },
      endDragging(items) {
        this.reorder(items)
      },
      onDelete(i) {
        this.selected.index = i
        this.selected.page = this.pages[i]
        this.$nextTick(function() {
          this.$bvModal.show('ConfirmationModal')
        })
      },
      deletePage() {
        axios.delete(`/api/admin/page/${this.selected.page.id}`)
          .then((response) => {
            console.log(response.data)
            this.pages.splice(this.selected.index, 1)
            this.$nextTick(function() {
              this.reorder(this.pages)
            })
            this.toastSuccess(response.data.message)
          })
          .catch((error) => {
            console.log(error);
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      },
      reorder(items) {
        axios.post(`/api/admin/page/reorder`, {
            pages: items
          })
          .then((response) => {
            console.log(response.data)

          })
          .catch((error) => {
            console.log(error);
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      },
      insertNewFile(data) {
        this.pages.push(data.response)
      },
      getData() {
        axios.get(`/api/admin/ebook/${this.$route.params.slug}/${this.$route.params.chapterId}`)
          .then((response) => {
            this.chapter = response.data
            this.pages = response.data.pages
            console.log(response.data)
            this.dataLoaded = true
          })
          .catch((error) => {
            console.log(error);
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      }
    },
  }
</script>
<style scoped>
  .folders-icons {
    top: 80%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);

  }
</style>
