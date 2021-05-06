<template>
  <b-modal title="Crop Preview" v-model="showCropPreviewModal" @close="handleCancel" @ok="handleSaveCrop" ok-title="Download" :no-close-on-backdrop="true" centered modal-class="d-flex justify-content-center">
    <b-img fluid :src="cropPreviewSrc"></b-img>
    <template #modal-footer>
      <div class="w-100">
        <b-button variant="secondary" size="sm" class="float-right  ml-2" @click="handleSaveCrop">
          Download
        </b-button>

        <b-btn variant="secondary" @click="handleCopyClipboard" size="sm" class="float-right" v-if="shareAbleLink">
          <b v-html="buttonCopyText">∂
          </b>
        </b-btn>
        <b-overlay :show="busy" rounded opacity="0.6" spinner-small spinner-variant="dark" class="d-inline-block float-right" v-if="!shareAbleLink">
          <b-button variant="outline-secondary" size="sm" class=" " @click="generateShareableLink">
            Share
          </b-button>
        </b-overlay>

      </div>
    </template>
  </b-modal>
</template>
<script>
  import {
    mapState,
    mapMutations,
    mapActions
  } from 'vuex'
  export default {
    name: 'CropPreview',
    data: function() {
      return {
        shareAbleLink: '',
        buttonCopyText: 'Click to Copy',
        busy: false,
      }
    },
    created() {},

    computed: {
      ...mapState({
        ebook: state => state.ebook,
        currentPage: state => state.currentPage,
        cropPreviewSrc: state => state.cropPreviewSrc,
        showCropPreviewModal: state => state.showCropPreviewModal,
      })
    },
    methods: {
      ...mapMutations(['SETSHOWCROPPREVIEWMODAL', 'SETCROPMODE', 'SETCROPPREVIEWSRC']),
      handleCancel() {
        this.shareAbleLink = ''
        this.SETSHOWCROPPREVIEWMODAL(false)
        this.busy = false

      },
      handleSaveCrop(e) {
        e.preventDefault()


        const link = document.createElement('a')
        link.href = this.cropPreviewSrc
        link.download = `${this.ebook.name} - ${this.$route.params.page}`
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        // this.SETSHOWCROPPREVIEWMODAL(false)
        // this.SETCROPMODE(false)
        // this.SETCROPPREVIEWSRC("")
      },
      generateShareableLink() {
        this.busy = true
        axios.post(`/api/generate-shareable-link-crop-image`, {
            cropImage: this.cropPreviewSrc
          })
          .then((response) => {
            let protocol = window.location.protocol + '//'
            let host = window.location.host
            this.shareAbleLink = protocol + host + response.data
            this.busy = false

          })
          .catch((error) => {
            console.log(error);
          })
      },
      handleCopyClipboard() {
        this.copyTextToClipboard(this.shareAbleLink)
        this.buttonCopyText = '<i class="fa fa-check"></i> Copied!'
        setTimeout(() => {
          this.buttonCopyText = "Click to Copy"
        }, 500);
      },
      fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
          console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
      },

      copyTextToClipboard(text) {
        if (!navigator.clipboard) {
          this.fallbackCopyTextToClipboard(text);
          return;
        }
        navigator.clipboard.writeText(text).then(function() {
          console.log('Async: Copying to clipboard was successful!');
        }, function(err) {
          console.error('Async: Could not copy text: ', err);
        });
      }

    },
  }
</script>
<style>
</style>
