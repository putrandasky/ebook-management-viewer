<template>
  <b-row class="pt-5 d-flex  justify-content-center h-100">
    <b-col class=" text-center  " lg="3" md="4" sm="6" v-for="(v,i) in thumbnailsData" :key="i">

      <b-row class=" shadow mx-1 thumb-view">
        <b-col col class="px-0 ">
          <b-img class="float-right" @click="pageClick(v.odd.id)" fluid :src="`/storage/book/${ebook.id}/tmb256-${v.odd.original_name}`"></b-img>
        </b-col>
        <b-col col class="px-0 ">
          <div v-if="!v.even" class="w-100 h-100 bg-white">
          </div>
          <b-img class="float-left" v-if="v.even" @click="pageClick(v.even.id)" fluid :src="`/storage/book/${ebook.id}/tmb256-${v.even.original_name}`"></b-img>
        </b-col>
      </b-row>
      <b-row class="w-100 mx-1">
        <b-col cols="12" class="text-secondary px-0">
          <em>{{getPagination(v.odd)}}</em>
          <em v-if="v.even"> - {{getPagination(v.even)}}</em>
        </b-col>
      </b-row>
    </b-col>
  </b-row>
</template>
<script>
  import {
    mapState,
    mapActions
  } from 'vuex'
  export default {
    name: 'Thumbnails',
    data: function() {
      return {}
    },
    created() {},
    computed: {
      ...mapState({
        thumbnailsData: state => state.thumbnailsData,
        ebook: state => state.ebook,
        readerMode: state => state.readerMode,
      })
    },
    methods: {
      ...mapActions(['updateCurrentPageByPageID', 'changeReaderMode']),
      async pageClick(id) {
        await this.updateCurrentPageByPageID(id)
        this.changeReaderMode('single')
      },
      getPagination(data) {
        if (!data) {
          return
        }
        let chapter = data.chapter
        if (typeof chapter == 'undefined') {
          return
        }
        if (chapter.type == 'folder') {
          return chapter.alias ? `${chapter.alias}-${data.order}` : `${chapter.order}-${data.order}`
        }
        if (chapter.type == 'file') {
          return chapter.alias || chapter.order
        }
      }
    },
  }
</script>
<style>
</style>
