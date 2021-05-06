<template>
  <div class="app" v-if="loaded">
    <nav-bar />
    <sidebar-content />
    <sidebar-search />
    <b-container class="page-viewer pt-5">
      <single-reader v-if="readerMode == 'single' && !thumbnails" />
      <facing-reader v-if="readerMode == 'facing'  && !thumbnails" />
      <thumbnails v-if="thumbnails" />
    </b-container>
    <bookmarks />
    <crop-preview />
    <download-pdf />
    <print-layout-format />
    <help />
    <arrow direction="right"></arrow>
    <arrow direction="left"></arrow>
    <!-- <div class="position-absolute" style="left:10px;top:50%"> <i class="fa fa-chevron-left fa-3x"></i></div> -->
  </div>
</template>
<script>
  import SidebarContent from '@/reader/components/sidebars/ContentNavigation'
  import SidebarSearch from '@/reader/components/sidebars/SearchNavigation'
  import NavBar from '@/reader/components/navbar/Navbar'
  import SingleReader from '@/reader/components/readers/SingleReader'
  import FacingReader from '@/reader/components/readers/FacingReader'
  import Thumbnails from '@/reader/components/readers/Thumbnails'
  import Bookmarks from '../components/modals/Bookmarks'
  import CropPreview from '../components/modals/CropPreview.vue'
  import DownloadPdf from '../components/modals/DownloadPdf.vue'
  import PrintLayoutFormat from '../components/modals/PrintLayoutFormat.vue'
  import Help from '../components/modals/Help.vue'
  import Arrow from '@/reader/components/navigation/Arrow'
  import {
    mapState,
    mapActions
  } from 'vuex'

  export default {
    name: 'Reader',
    components: {
      SidebarContent,
      NavBar,
      SingleReader,
      FacingReader,
      Thumbnails,
      Bookmarks,
      CropPreview,
      DownloadPdf,
      SidebarSearch,
      PrintLayoutFormat,
      Help,
      Arrow,
    },
    data: function() {
      return {
        loaded: false,

        data: {
          ebook: {},
          chapters: [],
          pages: []
        }
      }
    },
    mounted() {
      this.getData()
    },
    computed: {
      ...mapState({
        readerMode: state => state.readerMode,
        thumbnails: state => state.thumbnails,
        pages: state => state.pages,
        currentPage: state => state.currentPage,
      })
    },
    watch: {
      '$route.params.slug': function() {
        this.getData()
      }
    },
    methods: {
      ...mapActions(['setAllFacingPageData']),

      getData() {
        let self = this
        axios.get(`/api/reader?slug=${this.$route.params.slug}`)
          .then((response) => {

            this.$store.commit('MAINDATA', response.data)
            this.setAllFacingPageData({
              startIndex: 0,
              lastIndex: this.pages.length - 1,
              blankPages: false,
              thumbnails: true
            })

            if (typeof this.$route.params.page !== 'undefined') {
              this.$store.dispatch('updateCurrentPageByPageLabel', this.$route.params.page)

            }

            this.loaded = true
            this.$store.dispatch('updateCurrentPageByIndex', 0)

          })
          .catch((error) => {
            console.log(error);
            this.goToNotFound()
          })
      },
      goToNotFound() {
        this.$router.push({
          name: 'notFound',
        })
      },
      handleButton() {
        this.$router.push({
          name: 'ebookname',
          params: {
            ebookName: 'test',
          }
        })
      }
    },
  }
</script>
<style>
</style>
