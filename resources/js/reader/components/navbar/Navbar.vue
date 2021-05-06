<template>
  <b-navbar sticky type="light" variant="light" class="shadow-sm " style="z-index:800">
    <b-navbar-nav class="d-flex d-md-none mr-auto">
      <b-btn variant="outline-secondary" v-b-toggle="'sidebar-1'">
        <i class="fa fa-list"></i>
      </b-btn>
    </b-navbar-nav>
    <b-navbar-brand class="mr-auto  d-none d-md-flex" href="#">Ebook Reader</b-navbar-brand>
    <b-navbar-brand class="center-h   d-flex d-md-none" href="#">Ebook Reader</b-navbar-brand>

    <b-navbar-nav class="center-h d-none d-md-flex" v-if="!cropMode">
      <navbar-toggle-view />
      <navbar-thumbnails v-b-tooltip.hover.bottom="'Thumbnails'"></navbar-thumbnails>
      <b-nav-item v-b-tooltip.hover.bottom="'Bookmarks'" @click="SETSHOWBOOKMARKSMODAL(true)"><i class="fa fa-bookmark"></i></b-nav-item>
      <navbar-print v-b-tooltip.hover.bottom="'Print'"></navbar-print>
      <navbar-arrow v-b-tooltip.hover.bottom="'Previous Page'" direction="left"></navbar-arrow>
      <navbar-input />
      <navbar-arrow v-b-tooltip.hover.bottom="'Next Page'" direction="right"></navbar-arrow>
      <b-nav-item v-b-tooltip.hover.bottom="'Crop Page'" @click="SETCROPMODE(true)"><i class="fa fa-crop"></i></b-nav-item>
      <navbar-download-pdf v-b-tooltip.hover.bottom="'Export To PDF'"></navbar-download-pdf>
      <navbar-clipboard v-b-tooltip.hover.bottom="'Copy Link'"></navbar-clipboard>
      <b-nav-item v-b-modal.helpModal v-b-tooltip.hover.bottom="'Help'"><i class="fa fa-question-circle"></i></b-nav-item>
    </b-navbar-nav>
    <!-- <b-navbar-nav class="center-h  d-flex d-md-none" v-if="!cropMode">
      <navbar-arrow v-b-tooltip.hover.bottom="'Previous Page'" direction="left"></navbar-arrow>
      <navbar-input />
      <navbar-arrow v-b-tooltip.hover.bottom="'Next Page'" direction="right"></navbar-arrow>
    </b-navbar-nav> -->

    <b-navbar-nav class="ml-auto d-flex d-md-none" v-if="!cropMode">
      <b-nav-item-dropdown right no-caret>
        <template #button-content>
          <b-btn variant="outline-secondary">
            <i class="fa fa-bars"></i>
          </b-btn>
        </template>
        <navbar-search class="px-2" />

        <navbar-toggle-view :tooltip="false">
          <template #single>
            Single Reader
          </template>
          <template #facing>
            Facing Reader
          </template>

        </navbar-toggle-view>
        <navbar-thumbnails>Thumbnails</navbar-thumbnails>
        <b-nav-item @click="SETSHOWBOOKMARKSMODAL(true)"><i class="fa fa-bookmark"></i> Bookmarks</b-nav-item>
        <navbar-print> Print</navbar-print>

        <b-nav-item @click="SETCROPMODE(true)"><i class="fa fa-crop"></i> Crop Page</b-nav-item>
        <navbar-download-pdf> Export To PDF</navbar-download-pdf>
        <navbar-clipboard>Copy Link</navbar-clipboard>
        <b-nav-item v-b-modal.helpModal><i class="fa fa-question-circle"></i> Help</b-nav-item>
      </b-nav-item-dropdown>
    </b-navbar-nav>

    <navbar-search class="ml-auto  d-none d-md-flex" v-if="!cropMode" />
    <navbar-crop-mode class="ml-auto d-none d-md-flex" v-if="cropMode" />
    <navbar-crop-mode class="position-absolute d-flex d-md-none" style="right:5px;top:65px" v-if="cropMode" />

  </b-navbar>
</template>
<script>
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'

  import NavbarInput from './NavbarInput.vue'
  import NavbarArrow from './NavbarArrow.vue'
  import NavbarToggleView from './NavbarToggleView.vue'
  import NavbarCropMode from './NavbarCropMode.vue'
  import NavbarDownloadPdf from './NavbarDownloadPdf.vue'
  import NavbarSearch from './NavbarSearch.vue'
  import NavbarPrint from './NavbarPrint.vue'
  import NavbarClipboard from './NavbarClipboard.vue'
  import NavbarThumbnails from './NavbarThumbnails.vue'

  export default {
    name: 'Navbar',
    components: {
      NavbarInput,
      NavbarArrow,
      NavbarToggleView,
      NavbarCropMode,
      NavbarDownloadPdf,
      NavbarSearch,
      NavbarPrint,
      NavbarClipboard,
      NavbarThumbnails
    },
    data: function() {
      return {}
    },
    created() {},

    computed: {
      ...mapState({
        cropMode: state => state.cropMode,
      }),
    },
    methods: {
      ...mapMutations(['SETSHOWBOOKMARKSMODAL', 'SETCROPMODE'])
    },
  }
</script>
<style>
</style>
