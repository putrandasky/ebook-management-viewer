<template>
  <b-row class="d-flex align-items-center justify-content-center h-100">
    <!-- <b-btn @click="cropped">test</b-btn> -->
    <b-col ref="viewerSingle" sm="12" md="8" lg="7" xl="6" class="px-0">
      <div ref="hotlinkWrapper" class="w-100 h-100" style="z-index:100;position:absolute" v-if="!cropMode">
        <div v-for="(v,i) in hotspotlinks" :key="i" class="hotlink bg-danger" :class="{'blink':isBlink}" :style="{'z-index':101,
            'width':`${updateWidth(v)}px`,
            'height':`${updateHeight(v)}px`,
            'top':`${updateTop(v)}px`,
            'left':`${updateLeft(v)}px`
            }" @click="openLink(v.address)">
        </div>
        <v-touch class="w-100 h-100" v-on:swipeleft="onSwipe('left')" v-on:swiperight="onSwipe('right')" style="z-index:100;position:absolute"></v-touch>
      </div>
      <b-overlay v-if="!cropMode" :show="isShowLoader" blur="" variant="light" class="w-100 h-100 position-absolute"></b-overlay>
      <b-img class="shadow" ref="image" fluid :src="`/storage/book/${ebook.id}/${imgSrc}`" @load="imageLoaded"></b-img>
      <!-- <vue-cropper ref="cropper" :guides="true" :view-mode="2" drag-mode="crop" :auto-crop-area="0.5" :min-container-width="250" :min-container-height="180" :background="true" :rotatable="false" :src="`/images/1/${imgSrc}`" alt="Source Image" :aspect-ratio="1"></vue-cropper> -->
    </b-col>
  </b-row>
</template>
<script>
  import {
    mapState,
    mapMutations,
    mapActions,
    mapGetters
  } from 'vuex'
  import {
    EventBus
  } from "@/reader/event.js";
  import Cropper from "cropperjs";
  import 'cropperjs/dist/cropper.css';
  export default {
    name: 'SingleReader',

    data: function() {
      return {
        cropper: {},
        croppedImage: "",
        image: {},
        isBlink: false,
        isShowLoader: true


      }
    },
    destroyed() {
      window.removeEventListener('resize', this.updateParentPageData)
    },
    mounted() {
      this.image = this.$refs.image;
      EventBus.$on("showCropPreview", data => {
        this.cropped()
      })
      this.$nextTick(function() {
        window.addEventListener('resize', this.updateParentPageData)
      })

    },

    computed: {
      ...mapState({
        imgSrc: state => state.currentPage.original_name,
        hotspotlinks: state => state.currentPage.hotspotlinks,
        cropMode: state => state.cropMode,
        ebook: state => state.ebook,
      }),
      ...mapGetters([
        'updateHeight',
        'updateTop',
        'updateWidth',
        'updateLeft'
      ])
    },
    watch: {

      'imgSrc': function(newVal, oldVal) {
        this.isShowLoader = true
      },
      'cropMode': function(newVal, oldVal) {
        console.log(newVal);
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
      }
    },
    methods: {
      ...mapMutations(['SETCLIENTXSTART', 'SETCROPMODE', 'SETSHOWCROPPREVIEWMODAL', 'SETCROPPREVIEWSRC', 'SETPARENTODDPAGEDATA']),
      ...mapActions(['updateCurrentPageByArrowButton', 'swipeAction']),
      mouseDown(e) {
        this.SETCLIENTXSTART(e.clientX)
      },
      mouseUp(e) {

        this.swipeAction(e.clientX)
      },
      onSwipe(direction) {
        this.updateCurrentPageByArrowButton(direction)
      },
      cropped() {
        const canvas = this.cropper.getCroppedCanvas();
        this.croppedImage = canvas.toDataURL("image/png");
        this.SETCROPPREVIEWSRC(this.croppedImage)
        this.SETSHOWCROPPREVIEWMODAL(true)
      },

      touchStart(e) {
        console.log(e);
      },
      touchEnd(e) {
        console.log(e);
      },
      imageLoaded() {
        this.isShowLoader = false
        this.isBlink = true
        setTimeout(() => {
          this.isBlink = false
        }, 1000);
        this.updateParentPageData()
      },
      updateParentPageData() {

        let data = {
          height: this.$refs.image.clientHeight,
          width: this.$refs.image.clientWidth
        }
        this.SETPARENTODDPAGEDATA(data)

      },
      openLink(url) {
        window.open(url, "_blank")

      }
    },
  }
</script>
<style lang="scss">

</style>>
