<template>
  <b-row class="d-flex align-items-center justify-content-center h-100">
    <b-col col class="px-0">
      <div ref="hotlinkWrapper" class="w-100 h-100" style="position:absolute" v-if="!cropMode">
        <div v-for="(v,i) in hotspotlinks" :key="i" class="hotlink bg-danger" :class="{'blink':isBlink}" :style=" {'z-index':101,
            'width':`${updateWidth(v)}px`,
            'height':`${updateHeight(v)}px`,
            'top':`${updateTop(v)}px`,
            'left':`${updateLeft(v)}px`
            }" @click="openLink(v.address)">
        </div>
        <v-touch class="w-100 h-100" v-on:swipeleft="onSwipe('left')" v-on:swiperight="onSwipe('right')" style="z-index:101;position:absolute"></v-touch>
      </div>
      <b-overlay v-if="!cropMode" :show="isShowLoader1" blur="" variant="light" class="w-100 h-100 position-absolute"></b-overlay>
      <b-img class="shadow" ref="imageOdd" fluid :src="`/storage/book/${ebook.id}/${imgSrc}`" @click="handleImageOdd" @load="imageLoaded(1)"></b-img>
    </b-col>
    <b-col col class="px-0">
      <div ref="hotlinkWrapper2" class="w-100 h-100" style="position:absolute" v-if="!cropMode">
        <div v-for="(v,i) in hotspotlinks2" :key="i" class="hotlink bg-danger" :class="{'blink':isBlink}" :style="{'z-index':101,
            'width':`${updateWidth2(v)}px`,
            'height':`${updateHeight2(v)}px`,
            'top':`${updateTop2(v)}px`,
            'left':`${updateLeft2(v)}px`
            }" @click="openLink(v.address)">
        </div>
        <v-touch class="w-100 h-100" v-on:swipeleft="onSwipe('left')" v-on:swiperight="onSwipe('right')" style="z-index:100;position:absolute"></v-touch>

      </div>
      <div v-if="!imgSrc2" class="w-100 h-100 bg-white">
      </div>
      <b-overlay v-if="!cropMode && imgSrc2" :show="isShowLoader2" blur="" variant="light" class="w-100 h-100 position-absolute"></b-overlay>
      <b-img class="shadow" v-if="imgSrc2" ref="imageEven" fluid :src="`/storage/book/${ebook.id}/${imgSrc2}`" @click="handleImageEven" @load="imageLoaded(2)"></b-img>
    </b-col>
    <!-- <b-col sm="12" class="pl-0">
      <b-img fluid :src="combinedData" style="max-height:100vh"></b-img>
    </b-col> -->
  </b-row>
</template>
<script>
  //   import mergeImages from 'merge-images';
  import Cropper from "cropperjs";
  import 'cropperjs/dist/cropper.css';
  import {
    EventBus
  } from "@/reader/event.js";
  import {
    mapState,
    mapMutations,
    mapActions,
    mapGetters

  } from 'vuex'
  export default {
    name: 'FacingReader',
    data: function() {
      return {
        isBlink: false,
        isLoaded: true,
        cropper: {},
        croppedImage: "",
        imageMode: "",
        isShowLoader1: true,
        isShowLoader2: true,
        imageLoadCounted: 0,
        // combinedData: null
      }
    },
    created() {},
    mounted() {
      let self = this
      EventBus.$on("showCropPreview", data => {
        this.cropped()
      })
      this.$nextTick(function() {
        window.addEventListener('resize', this.updateParentPageData)
      })
      this.isBlink = true

    },
    destroyed() {
      window.removeEventListener('resize', this.updateParentPageData)
    },
    computed: {
      ...mapState({
        imgSrc: state => state.currentPage.original_name,
        hotspotlinks: state => state.currentPage.hotspotlinks,
        imgSrc2: state => state.nextPage.original_name,
        hotspotlinks2: state => state.nextPage.hotspotlinks,
        cropMode: state => state.cropMode,
        ebook: state => state.ebook,

      }),
      ...mapGetters([
        'updateHeight',
        'updateTop',
        'updateWidth',
        'updateLeft',
        'updateHeight2',
        'updateTop2',
        'updateWidth2',
        'updateLeft2'
      ])
    },
    watch: {
      'imgSrc': function(newVal, oldVal) {
        this.imageLoadCounted = 0
        this.isShowLoader1 = true
        this.isShowLoader2 = true
      },
      'imgSrc2': function(newVal, oldVal) {},
      'cropMode': function(newVal, oldVal) {
        if (!this.imageMode) {
          return
        }
        if (newVal) {
          this.cropper = new Cropper(this.image, {
            zoomable: false,
            scalable: false,
            background: false,
            guides: true,
            viewMode: 3,
            autoCrop: false,
            rotatable: false,
          });
        }
        if (!newVal) {
          this.cropper.destroy()
        }
      },
      'imageMode': function(newVal, oldVal) {
        if (Object.keys(this.cropper).length != 0) {

          this.cropper.destroy()
        }
        this.cropper = new Cropper(this.image, {
          zoomable: false,
          scalable: false,
          background: false,
          guides: true,
          viewMode: 3,
          autoCrop: false,
          rotatable: false,
        });
      }
    },
    methods: {
      ...mapActions(['swipeAction', 'updateCurrentPageByArrowButton']),
      ...mapMutations(['SETCLIENTXSTART', 'SETCROPMODE', 'SETSHOWCROPPREVIEWMODAL', 'SETCROPPREVIEWSRC', 'SETPARENTODDPAGEDATA', 'SETPARENTODDPAGEDATA2']),
      mouseDown(e) {
        this.SETCLIENTXSTART(e.clientX)
      },
      mouseUp(e) {
        this.swipeAction(e.clientX)
      },
      onSwipe(direction) {
        this.updateCurrentPageByArrowButton(direction)
      },
      handleImageOdd(e) {
        if (this.imageMode == 'imageOdd' && !this.cropMode) {
          return
        }
        this.image = this.$refs.imageOdd;
        this.imageMode = 'imageOdd'
        console.log('odd');
      },
      handleImageEven(e) {
        if (this.imageMode == 'imageEven' && !this.cropMode) {
          return
        }
        this.image = this.$refs.imageEven;
        this.imageMode = 'imageEven'
      },
      cropped() {
        const canvas = this.cropper.getCroppedCanvas();
        this.croppedImage = canvas.toDataURL("image/png");
        this.SETCROPPREVIEWSRC(this.croppedImage)
        this.SETSHOWCROPPREVIEWMODAL(true)
      },
      imageLoaded(page) {
        this.imageLoadCounted++
        this.checkLoadedImage()
        if (page == 1) {
          this.isShowLoader1 = false
        }
        if (page == 2) {
          this.isShowLoader2 = false
        }

      },
      checkLoadedImage() {

        if (this.imageLoadCounted == 2) {
          this.isBlink = true
          setTimeout(() => {
            this.isBlink = false
          }, 1000);
          this.updateParentPageData()
        }
      },
      updateParentPageData() {

        let data = {
          height: this.$refs.imageOdd.clientHeight,
          width: this.$refs.imageOdd.clientWidth
        }
        let data2 = {
          height: this.$refs.imageEven.clientHeight,
          width: this.$refs.imageEven.clientWidth
        }
        this.SETPARENTODDPAGEDATA(data)
        this.SETPARENTODDPAGEDATA2(data2)

      },
      openLink(url) {
        window.open(url, "_blank")

      }
    },
  }
</script>
<style>
</style>
