<template>
  <div>
    <b-button variant="danger" class="btn-content d-none d-md-inline-block" @click="handleSidebarButton" v-b-toggle="'sidebar-1'">Content</b-button>
    <b-sidebar title="Navigation" backdrop id="sidebar-1" :z-index="1001" :shadow="true">
      <b-list-group flush class="mt-3">
        <b-list-group-item v-for="(v,i) in dataSidebarPrimary" :key="i" @click="handleClickPrimarySidebar(v.pages,v.type)" v-b-toggle="toggleData1">
          <span v-if="v.type == 'file'">{{v.pages[0].name}} </span>
          <span v-if="v.type == 'folder'">{{v.name}} <i class="fa fa-chevron-right float-right"></i></span>
        </b-list-group-item>
      </b-list-group>
    </b-sidebar>
    <b-sidebar title="Navigation" backdrop id="sidebar-2" :z-index="1000" :shadow="true">
      <div class="m-3">
        <b-btn size="sm" variant="outline-secondary" @click="handleBackButton" v-b-toggle="'sidebar-1 sidebar-2'"> <i class="fa fa-chevron-left"></i> Back</b-btn>
      </div>

      <b-list-group flush>
        <b-list-group-item v-for="(v,i) in dataSidebarSecondary" :key="i" @click="handleClickSecondarySidebar(v.id)">
          {{v.name}}
        </b-list-group-item>

      </b-list-group>
    </b-sidebar>
  </div>
</template>
<script>
  import {
    mapState,
    mapActions
  } from 'vuex'
  export default {
    name: 'ContentNavigation',
    data: function() {
      return {
        showSidebarPrimary: false,
        showSidebarSecondary: false,
        dataSidebarSecondary: [],
        toggleData1: '',
        toggleData2: ''

      }
    },
    created() {},
    computed: {
      ...mapState({
        dataSidebarPrimary: state => state.chapters,
      })
    },
    methods: {
      ...mapActions(['updateCurrentPageByPageID']),
      handleSidebarButton() {
        // this.toggleData1 = 'sidebar-2'
      },
      handleBackButton() {
        // this.showSidebarPrimary = true
        // this.showSidebarsecondary = false
        this.toggleData1 = ''
        this.toggleData2 = ''
      },
      handleClickPrimarySidebar(pages, type) {
        if (type == "folder") {
          //   this.showSidebarPrimary = false
          this.dataSidebarSecondary = pages
          this.toggleData1 = 'sidebar-1 sidebar-2'
          this.toggleData2 = 'sidebar-2'

          //   this.showSidebarSecondary = true
        }
        if (type == "file") {
          //   this.showSidebarPrimary = false
          //   this.showSidebarsecondary = false
          //   this.toggleData1 = 'sidebar-1 '
          this.updateCurrentPageByPageID(pages[0].id)
        }
      },
      handleClickSecondarySidebar(id) {
        // this.showSidebarsecondary = false
        console.log(this.toggleData2);

        this.updateCurrentPageByPageID(id)
        // this.toggleData2 = 'sidebar-2'
        this.toggleData1 = ''
        this.toggleData2 = ''
      },
      //   handleClickPrimarySidebar(pages, type) {
      //   if (type == "folder") {
      //   this.showSidebarPrimary = false
      //   this.showSidebarsecondary = true
      //   document.getElementById('sidebar-2').style.removeProperty('display')
      //   this.dataSidebarSecondary = pages
      //   }
      //   if (type == "file") {
      //   this.showSidebarPrimary = false
      //   this.showSidebarsecondary = false
      //   this.updateCurrentPageByPageID(pages[0].id)
      //   document.getElementById('sidebar-2').style.setProperty('display', 'none')
      //   }
      //   },
      //   handleClickSecondarySidebar(id) {
      //   this.showSidebarsecondary = false
      //   this.updateCurrentPageByPageID(id)

      //   document.getElementById('sidebar-2').classList.add('slide')
      //   setTimeout(() => {
      //   document.getElementById('sidebar-2').classList.remove('slide')
      //   document.getElementById('sidebar-2').style.setProperty('display', 'none')
      //   }, 300);

      //   }
    },
  }
</script>
<style scoped>

</style>
