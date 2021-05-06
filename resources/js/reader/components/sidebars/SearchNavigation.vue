<template>
  <b-sidebar right backdrop title="Search Results" id="sidebar-search" :z-index="1000" :shadow="true">
    <b-container>

      <b-row>
        <b-col cols="12" v-if="searchResult.length == 0">
          No Result Found
        </b-col>
        <b-col cols="12" v-if="searchResult.length !== 0">
          <b-card class="mt-2" v-for="(v,i) in searchResult" :key="i" footer-class="py-1" @click="handleClickSearchResult(v.id)" style="cursor:pointer">
            <text-highlight :queries="queries">{{v.name}}</text-highlight>
            <template #footer>
              <small><b>Page : {{setPageLabel(v)}}</b></small>
            </template>

          </b-card>
        </b-col>
      </b-row>
    </b-container>

    <!-- <b-list-group flush>
      <b-list-group-item v-for="(v,i) in dataSidebarSecondary" :key="i" @click="handleClickSecondarySidebar(v.id)">
        {{v.name}}
      </b-list-group-item>

    </b-list-group> -->
  </b-sidebar>
</template>

<script>
  import TextHighlight from 'vue-text-highlight';
  import {
    EventBus
  } from "@/reader/event.js";
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  export default {
    name: 'SearchNavigation',
    components: {
      TextHighlight
    },
    data: function() {
      return {
        showSidebarSearchContent: false,
        searchResult: [],
        queries: [],
      }
    },
    created() {},
    mounted() {
      EventBus.$on("handleSearchContent", data => {
        // this.showSidebarSearchContent = true
        // this.openSidebar()
        console.log(data);
        this.searchResult = this.pages.filter(a => a.name.toLowerCase().indexOf(data.input.toLowerCase()) > -1)
        this.queries = data.input.split(' ')
      })
      EventBus.$on("handleCancelSearchContent", data => {
        // this.closeSidebar()
        // this.showSidebarSearchContent = false

        setTimeout(() => {
          this.searchResult = []
          this.queries = []
        }, 300);
      })

    },
    computed: {
      ...mapState({
        pages: state => state.pages
      }),

    },
    methods: {
      ...mapActions(['updateCurrentPageByPageID']),

      setPageLabel(thisPage) {
        if (thisPage.chapter.type == "folder") {
          if (thisPage.chapter.alias) {
            return thisPage.chapter.alias + '-' + thisPage.order
          }
          return thisPage.chapter.order + '-' + thisPage.order
        }
        return thisPage.chapter.alias || thisPage.chapter.order
      },
      handleClickSearchResult(pageId) {
        this.updateCurrentPageByPageID(pageId)
      },
      openSidebar() {
        let element = document.getElementById('sidebar-search')
        element.classList.add('slide')
        element.classList.add('show')
        element.style.removeProperty('display')
        element.removeAttribute('aria-hidden')
        setTimeout(() => {
          element.classList.remove('slide')
          element.classList.remove('show')
        }, 300);
      },
      closeSidebar() {
        let element = document.getElementById('sidebar-search')
        element.classList.add('slide')
        element.setAttribute('aria-hidden', true)

        setTimeout(() => {
          element.classList.remove('slide')
          element.style.setProperty('display', 'none')

        }, 300);
      }

    },
  }
</script>
<style>
</style>
