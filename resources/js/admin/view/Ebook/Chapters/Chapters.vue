<template>
  <b-container class="pt-3">
    <div class="w-100 ">
      <b-btn size="sm" variant="outline-secondary" class="shadow-sm" @click="previousPage()">Back</b-btn>
      <b-btn size="sm" variant="danger" class="shadow-sm float-right" v-b-toggle.sidebar-chapter>Add Chapter</b-btn>
    </div>
    <!-- <chapters-list :chapters="chapters" :ebook="ebook"></chapters-list> -->
    <b-row v-if="chapters.length == 0  && dataLoaded" class="pt-5 d-flex justify-content-center align-items-center">
      <empty-state type="chapter"></empty-state>

    </b-row>
    <list-item v-if="chapters.length > 0  && dataLoaded" :items="chapters" @endDragging="endDragging">
      <template v-slot:buttons="data">
        <item-buttons :type="data.item.type" @onDelete="onDelete(data.index)" @onDetail="openDetail(data.item)" @onEdit="onEdit(data.index)"></item-buttons>
      </template>
      <template v-slot:items="data">
        <item-folder v-if="data.item.type == 'folder'" :name="data.item.name" :pagesCount="data.item.pages_count"></item-folder>
        <item-file v-if="data.item.type == 'file'" :ebookId="ebook.id" :originalName="data.item.pages.original_name"></item-file>
      </template>
    </list-item>
    <chapters-modal :chapter="selected.chapter" @onUpdated="onFolderUpdated"></chapters-modal>
    <chapters-sidebar @newChapterSubmitted="insertNewFolder($event)" @newFileUploaded="insertNewFile($event)"></chapters-sidebar>
    <confirmation-modal :title="selected.chapter.type == 'folder'? 'Delete Folder Confirmation':'Delete Page Confirmation'" @onSubmit="deleteChapter()">
      <span v-if="selected.chapter.type == 'folder'">
        Are you sure to delete this folder? All page in this folder will be deleted!
      </span>
      <span v-if="selected.chapter.type == 'file'">
        Are you sure to delete this page?
      </span>
    </confirmation-modal>
  </b-container>
</template>

<script>
  import ChaptersSidebar from './ChaptersSidebar.vue'
  import ChaptersModal from './ChaptersModal.vue'
  import ListItem from '@/admin/components/List/ListItem'
  import ItemFolder from '@/admin/components/List/ListItemFolder'
  import ItemFile from '@/admin/components/List/ListItemFile'
  import ItemButtons from '@/admin/components/List/ListItemButtons'
  import ConfirmationModal from '@/admin/components/modals/ConfirmationModal.vue'
  import EmptyState from '@/admin/components/EmptyState.vue'

  export default {
    components: {
      ChaptersSidebar,
      ListItem,
      ItemFolder,
      ItemFile,
      ItemButtons,
      ChaptersModal,
      ConfirmationModal,
      EmptyState


    },
    name: 'Chapters',
    data: function() {
      return {
        dataLoaded: false,
        chapters: [],
        ebook: {},
        selected: {
          chapter: {},
          index: null
        }

      }
    },
    created() {
      this.getData()
    },


    methods: {
      previousPage() {
        this.$router.push({
          name: 'ebooks',

        })
      },
      onEdit(i) {
        console.log(this.chapters[i]);
        this.selected.chapter = this.chapters[i]
        this.selected.index = i
        this.$nextTick(function() {
          this.$bvModal.show('ChapterModal')
        })
      },
      onDelete(i) {
        this.selected.index = i
        this.selected.chapter = this.chapters[i]
        this.$nextTick(function() {
          this.$bvModal.show('ConfirmationModal')
        })
      },
      onFolderUpdated(data) {
        let index = this.selected.index
        this.chapters[index].name = data.chapterName
        this.chapters[index].alias = data.chapterAlias
      },
      openDetail(data) {
        if (data.type == 'folder') {
          this.$router.push({
            name: 'pages',
            params: {
              chapterId: data.id
            }
          })
        }
        if (data.type == 'file') {
          this.$router.push({
            name: 'pageDetail',
            params: {
              pageId: data.pages.id,
            }
          })
        }

      },
      insertNewFolder(data) {
        let newChaptersData = {
          id: data.id,
          name: data.name,
          order: data.order,
          type: 'folder',
          pages_count: 0

        }
        this.chapters.push(newChaptersData)

      },

      insertNewFile(data) {
        let res = data.response
        let newChaptersData = {
          id: res.chapter_id,
          name: res.name,
          order: res.order,
          type: 'file',
          pages: {
            id: res.id,
            original_name: res.original_name
          }
        }
        this.chapters.push(newChaptersData)
      },
      endDragging(items) {
        this.reorder(items)
      },
      reorder(items) {
        axios.post(`/api/admin/chapter/reorder`, {
            chapters: items
          })
          .then((response) => {
            console.log(response.data)

          })
          .catch((error) => {
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      },
      deleteChapter() {
        axios.delete(`/api/admin/chapter/${this.selected.chapter.id}`)
          .then((response) => {
            this.toastSuccess(response.data.message)
            this.chapters.splice(this.selected.index, 1)
            this.$nextTick(function() {
              this.reorder(this.chapters)
            })
          })
          .catch((error) => {
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      },
      getData() {
        axios.get(`/api/admin/ebook/${this.$route.params.slug}`)
          .then((response) => {
            this.chapters = response.data.chapters || []
            this.ebook = response.data.ebook
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

</style>
