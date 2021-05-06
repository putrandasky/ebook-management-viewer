<template>
  <b-modal title="Print Layout" v-model="showPrintLayoutModal" :no-close-on-backdrop="true" centered size="md">
    <b-button-group class="w-100">
      <b-btn @click="state = 'Single'" :variant="state == 'Single' ?'secondary' : 'outline-secondary' " size="sm">
        Single Page
      </b-btn>
      <b-btn @click="state = 'Facing'" :variant="state == 'Facing' ?'secondary' : 'outline-secondary' " size="sm">
        Facing Page
      </b-btn>
    </b-button-group>
    <div class="mt-2">
      <div class="mb-3">
        For best results when printing in "{{state}}" mode, set your printer layout orientation to "{{printerOrientation}}"
      </div>
      <b-row>
        <b-col col>
          <label for=" from">From</label>
          <b-input id="from" placeholder="" v-model="input.from"></b-input>
        </b-col>
        <b-col col>
          <label for="to">To</label>
          <b-input id="to" placeholder="" v-model="input.to"></b-input>
        </b-col>
      </b-row>

    </div>
    <template #modal-footer>
      <div class="w-100">
        <b-button variant="secondary" size="sm" class="float-right" @click="handleSubmitButton">
          Print
        </b-button>
      </div>
    </template>

    <div id="printable" class="d-none d-print-block">
      <b-row v-if="singlePage.length != 0">
        <b-col v-for="(v,i) in singlePage" :key="i" style="height:100%;text-align:center">
          <b-img @load="onImageLoaded" :src="`/storage/book/${ebook.id}/${v.original_name}`" style="max-width:100%;max-height:100%;"></b-img>

        </b-col>
      </b-row>
      <b-row v-if="facingPage.length != 0">
        <b-col v-for="(v,i) in facingPage" :key="i" style="height:100%;text-align:center">
          <b-row style="display:flex">
            <b-col style="flex-basis:0;flex-grow:1;max-width:100%">
              <b-img @load="onImageLoaded" :src="`/storage/book/${ebook.id}/${v.odd.original_name}`" style="max-width:100%;max-height:100%;"></b-img>
            </b-col>
            <b-col style="flex-basis:0;flex-grow:1;max-width:100%">
              <div v-if="!v.even" style="width:100%;height:100%;background-color:#FFFFFF">
              </div>
              <b-img @load="onImageLoaded" v-if="v.even" :src="`/storage/book/${ebook.id}/${v.even.original_name}`" style="max-width:100%;max-height:100%;"></b-img>
            </b-col>
          </b-row>

        </b-col>
      </b-row>
    </div>
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
    name: 'PrintLayoutFormat',
    data: function() {
      return {
        showPrintLayoutModal: false,
        state: 'Single',
        facingPage: [],
        singlePage: [],
        totalFacingPage: 0,
        imageLoadCounted: 0,
        index: {
          start: 0,
          last: 0
        },
        input: {
          from: '',
          to: ''
        }
      }
    },
    created() {},
    mounted() {
      EventBus.$on('handlePrintButton', data => {
        this.showPrintLayoutModal = data
      })
    },
    watch: {
      state() {
        this.facingPage = []
        this.singlePage = []
        this.totalFacingPage = 0
        this.imageLoadCounted = 0
        this.index.start = 0
        this.index.last = 0
      }
    },
    computed: {
      ...mapState({
        pages: state => state.pages,
        facingPageData: state => state.facingPageData,
        ebook: state => state.ebook
      }),
      printerOrientation() {
        return this.state == 'Single' ? "Portrait" : "Landscape"
      },
    },
    methods: {
      ...mapActions(['findIndexByPageLabel', 'setAllFacingPageData']),

      handleSubmitButton() {
        this.selectedPage()

      },
      async selectedPage() {
        let startIndex = await this.getPageIndex(this.input.from)
        let lastIndex = await this.getPageIndex(this.input.to)

        //prevent reload image while same index input
        if (startIndex == this.index.start && lastIndex == this.index.last && this.imageLoadCounted > 0) {
          this.checkLoadedImage()
          return
        }
        this.index.start = startIndex
        this.index.last = lastIndex
        if (this.state == 'Single') {
          for (let i = startIndex; i <= lastIndex; i++) {
            this.singlePage.push(this.pages[i])
          }
        }
        if (this.state == 'Facing') {
          let totalOddData = 0
          let totalEvenData = 0
          await this.setAllFacingPageData({
            startIndex: startIndex,
            lastIndex: lastIndex,
            blankPages: false,
            thumbnails: false
          })
          this.facingPage = await this.facingPageData

          for (let i = 0; i < this.facingPage.length; i++) {
            if (this.facingPage[i].odd) {
              totalOddData++
            }
            if (this.facingPage[i].even) {
              totalEvenData++
            }
          }
          this.totalFacingPage = totalOddData + totalEvenData
        }


      },
      getPageIndex(pageLabel) {
        let splitSeparated = pageLabel.split('-')
        return this.findIndexByPageLabel(splitSeparated)
      },

      onImageLoaded() {
        this.imageLoadCounted++
        this.checkLoadedImage()

      },
      checkLoadedImage() {
        if (this.state == 'Single' && this.imageLoadCounted == this.singlePage.length) {
          this.toPrint()
        }
        if (this.state == 'Facing' && this.imageLoadCounted == this.totalFacingPage) {
          this.toPrint()
        }
      },
      toPrint() {
        var divToPrint = document.getElementById('printable');
        var newWin = window.open();
        newWin.document.write(divToPrint.innerHTML);
        newWin.document.close();
        newWin.print();
        newWin.close();
      }

    },
  }
</script>
<style>
</style>
