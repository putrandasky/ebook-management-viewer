<template>
  <b-modal title="PDF Options" v-model="showPdfOptionModal" :no-close-on-backdrop="true" centered size="md">
    <b-button-group class="w-100">
      <b-btn @click="state = 'entire'" :variant="state == 'entire' ?'secondary' : 'outline-secondary' " size="sm">
        Entire Catalog
      </b-btn>
      <b-btn @click="state = 'range'" :variant="state == 'range' ?'secondary' : 'outline-secondary' " size="sm">
        Page Range
      </b-btn>
    </b-button-group>
    <b-collapse id="collapse-4" v-model="showCollapse" class="mt-2">
      <label for="page-input">Enter page label with tilde (~) and separated by comma</label>
      <b-input id="page-input" placeholder="e.g. 1-2~3-1,4~8" v-model="pageRangeInput"></b-input>
    </b-collapse>
    <template #modal-footer>
      <div class="w-100">
        <b-button variant="secondary" size="sm" class="float-right" @click="handleSubmitButton">
          Submit
        </b-button>
      </div>
    </template>
  </b-modal>
</template>
<script>
  import {
    EventBus
  } from "@/reader/event.js";
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  export default {
    name: 'DownloadPdf',
    data: function() {
      return {
        showPdfOptionModal: false,
        state: 'entire',
        pageRangeInput: ''
      }
    },
    created() {},
    mounted() {
      EventBus.$on("setShowPdfOptionModal", data => {
        this.showPdfOptionModal = data
      })
    },
    computed: {
      ...mapState({
        ebook: state => state.ebook,
      }),
      showCollapse() {
        return this.state == 'range' ? true : false
      },
    },
    methods: {
      ...mapActions(['findIndexByPageLabel']),
      async handleSubmitButton() {

        if (this.state == 'entire') {
          axios.post(`/generatepdf/entire`, {
              ebook_id: this.ebook.id
            })
            .then((response) => {
              this.openUrl(response.data)
            })
            .catch((error) => {
              console.log(error);
            })
        }
        if (this.state == 'range') {
          let commaSeparated = this.pageRangeInput.split(',')
          let pageIndexArray = []
          for (let i = 0; i < commaSeparated.length; i++) {
            let tildeSeparated = commaSeparated[i].split('~')
            //check input has page range with tilde symbols
            let pageRangeObject = {}
            for (let a = 0; a < tildeSeparated.length; a++) {
              let splitSeparated = tildeSeparated[a].split('-')
              let index = await this.findIndexByPageLabel(splitSeparated)
              pageRangeObject[a] = index
            }
            pageIndexArray.push(pageRangeObject)
          }
          axios.post(`/generatepdf/range`, {
              ebook_id: this.ebook.id,
              page_index: pageIndexArray
            })
            .then((response) => {
              console.log(response.data)
              this.openUrl(response.data)
            })
            .catch((error) => {
              console.log(error);
            })
        }

      },
      openUrl(pathname) {
        let protocol = window.location.protocol + '//'
        let host = window.location.host
        let url = protocol + host + pathname
        window.open(url, "_blank")
      },

    },
  }
</script>
<style>
</style>
