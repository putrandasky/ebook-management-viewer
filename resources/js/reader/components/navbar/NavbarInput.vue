<template>
  <b-nav-form v-on:submit.prevent="handleEnterInput">
    <b-input size="sm" style="width:50px" v-model="inputValue" class="text-center"></b-input>
  </b-nav-form>
</template>
<script>
  import {
    mapState,
    mapActions
  } from 'vuex'
  export default {
    name: 'NavbarInput',
    data: function() {
      return {
        inputValue: "",
        oldInputValue: ""
      }
    },
    mounted() {
      if (this.currentPage.chapter.type == "folder") {
        this.joiningChapterAndPageOrder()
        return
      }
      this.setChapterAsPage()
    },
    watch: {
      'currentPage.id': function(newVal, oldVal) {
        if (this.currentPage.chapter.type == "folder") {
          this.joiningChapterAndPageOrder()
          this.updatePageInRouter()
          return
        }
        this.setChapterAsPage()
        this.updatePageInRouter()
      },
      //   'currentChapter.id': function(newVal, oldVal) {
      //     if (this.currentChapter.type == "folder") {
      //       this.joiningChapterAndPageOrder()
      //       return
      //     }
      //     this.inputValue = this.currentChapter.alias || this.currentChapter.order
      //   }
    },
    computed: {
      ...mapState({
        currentPage: state => state.currentPage,
        currentChapter: state => state.currentChapter,
      })
    },
    methods: {
      ...mapActions(['updateCurrentPageByPageLabel']),

      handleEnterInput(e) {
        e.preventDefault()
        if (this.inputValue == this.oldInputValue) {
          return
        }
        this.updateCurrentPageByPageLabel(this.inputValue)
        if (this.currentPage.chapter.type == "folder") {
          this.joiningChapterAndPageOrder()
          return
        }
        this.setChapterAsPage()
      },
      joiningChapterAndPageOrder() {
        if (this.currentPage.chapter.type == "folder") {
          if (this.currentChapter.alias) {
            this.inputValue = this.currentChapter.alias + '-' + this.currentPage.order
            this.oldInputValue = this.currentChapter.alias + '-' + this.currentPage.order
            return
          }
          this.inputValue = this.currentChapter.order + '-' + this.currentPage.order
          this.oldInputValue = this.currentChapter.order + '-' + this.currentPage.order
          return
        }
      },
      setChapterAsPage() {
        this.inputValue = this.currentChapter.alias || this.currentChapter.order
        this.oldInputValue = this.currentChapter.alias || this.currentChapter.order
      },
      updatePageInRouter() {

        this.$router.push({
          name: 'page',
          params: {
            page: this.inputValue,
          }
        }).catch(() => {})
      }
    },
  }
</script>
<style>
</style>
