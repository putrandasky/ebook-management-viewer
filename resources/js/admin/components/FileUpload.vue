<template>
  <div class="drag-drop-area">
    <div class="upload">
      <div class=" float-right mb-2">
        <button type="button" class="btn btn-outline-danger btn-sm" v-if=" files.length > 0 && ( !$refs.upload || !$refs.upload.active)" @click.prevent="files = []">
          <i class="fa fa-trash" aria-hidden="true"></i>
          Clear Files
        </button>
        <file-upload :headers="{'x-xsrf-token': xsrfToken}" class="btn btn-outline-secondary btn-sm" style="cursor:pointer" :post-action="url" :multiple="true" :drop="true" :drop-directory="true" v-model="files" accept="image/jpeg,image/jpg" ref="upload" @input-filter="inputFilter" @input-file="inputFile">
          <i class="fa fa-plus"></i>
          Add Files
        </file-upload>

      </div>
      <div v-if="files.length" class="pb-3" style="border: 2px dashed grey; clear:both">

        <div class="mb-1 p-1" v-for="(file) in files" :key="file.id" style="position:relative">
          <b-media>
            <template #aside>
              <b-overlay blur="" :show="showOverlay == file.id" variant="dark" v-on:mouseleave.native="showOverlay = 0">
                <template #overlay>
                  <b-button pill variant="outline-danger" @click.prevent="$refs.upload.remove(file)" size="sm">
                    <i class="fa fa-trash"></i>
                  </b-button>

                </template>
                <b-img class="shadow-sm" v-on:mouseenter="showOverlay = file.id" width="64" :src="file.blob" />
              </b-overlay>
            </template>
            <div style="overflow-wrap:anywhere"><strong>{{file.name}}</strong>
            </div>
            <div>
              <small>Size : {{file.size | formatSize}} </small>
            </div>
            <b-progress height="2px" v-if="file.active || file.success || file.error" :value="file.progress * 1" variant="primary"></b-progress>
            <div>
              <span v-if="file.error"><span class="badge badge-pill badge-danger"> <i class="fa fa-exclamation"></i> {{file.error}}</span> </span>
              <span v-else-if="file.success"><span class="badge badge-pill badge-success"> <i class="fa fa-check"></i> Uploaded</span> </span>
              <span v-else-if="file.active">{{file.progress}}</span>
              <span v-else></span>
            </div>
          </b-media>
        </div>
      </div>
      <div v-if="!files.length" class="text-center p-3" style="border: 2px dashed grey; clear:both">
        <h4 class="text-muted">Drop files here to upload</h4>
        <h6 class="text-muted">(Accepted Files : jpeg/jpg & Max Size : 1mb)</h6>

        <!-- <label for="file" class="btn btn-lg btn-primary">Select Files</label> -->
      </div>

      <div v-show="$refs.upload && $refs.upload.dropActive" class="drop-active">
        <h3>Drop files to upload</h3>
      </div>

      <div class="example-btn mt-2 float-right">
        <button type="button" class="btn btn-secondary btn-sm" v-if=" files.length > 0 && ( !$refs.upload || !$refs.upload.active)" @click.prevent="$refs.upload.active = true">
          <i class="fa fa-arrow-up" aria-hidden="true"></i>
          Start Upload
        </button>


        <button type="button" class="btn btn-danger btn-sm" v-if="files.length > 0 &&  $refs.upload && $refs.upload.active" @click.prevent="$refs.upload.active = false">
          <i class="fa fa-stop" aria-hidden="true"></i>
          Stop Upload
        </button>

      </div>
    </div>
  </div>
</template>
<script>
  import Cookies from 'js-cookie'
  const VueUploadComponent = require('vue-upload-component')

  export default {
    name: 'FileUpload',
    props: ['url'],
    components: {
      'file-upload': VueUploadComponent
    },
    data: function() {
      return {
        showOverlay: 0,
        data: [],
        files: [],
        xsrfToken: ''
      }
    },
    created() {},
    mounted() {
      this.xsrfToken = Cookies.get('XSRF-TOKEN')
    },
    methods: {
      onmouseover(i) {
        this.showOverlay = i + 1
      },
      onmouseblur() {
        this.showOverlay = 0
      },
      inputFile(newFile, oldFile) {
        if (newFile && !oldFile) {
          // Add file
        }

        if (newFile && oldFile) {
          // Update file

          // Start upload
          if (newFile.active !== oldFile.active) {
            console.log('Start upload', newFile.active, newFile)

            // min size
            // if (newFile.size >= 0 && newFile.size < 5000 * 1024) {
            //   newFile = this.$refs.upload.update(newFile, {
            //     error: 'size'
            //   })
            // }
          }

          // Upload progress
          if (newFile.progress !== oldFile.progress) {
            console.log('progress', newFile.progress, newFile)
          }

          // Upload error
          if (newFile.error !== oldFile.error) {
            console.log('error', newFile.error, newFile)
          }

          // Uploaded successfully
          if (newFile.success !== oldFile.success) {
            console.log('success', newFile.success, newFile)
            this.$emit('uploaded', newFile)
          }
        }

        if (!newFile && oldFile) {
          // Remove file

          // Automatically delete files on the server
          if (oldFile.success && oldFile.response.id) {
            // $.ajax({
            //   type: 'DELETE',
            //   url: '/file/delete?id=' + oldFile.response.id,
            // });
          }
        }

        // Automatic upload
        // if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
        //   if (!this.$refs.upload.active) {
        //     this.$refs.upload.active = true
        //   }
        // }
      },
      inputFilter(newFile, oldFile, prevent) {
        if (newFile && !oldFile) {
          // Add file

          // Filter non-image file
          // Will not be added to files
          if (!/\.(jpeg|jpe|jpg|webp)$/i.test(newFile.name)) {
            return prevent()

          }
          if (newFile.size > 1000 * 1024) {

            return prevent()
          }

          // Create the 'blob' field for thumbnail preview
          newFile.blob = ''
          let URL = window.URL || window.webkitURL
          if (URL && URL.createObjectURL) {
            newFile.blob = URL.createObjectURL(newFile.file)
          }
        }

        if (newFile && oldFile) {
          // Update file

          // Increase the version number
          if (!newFile.version) {
            newFile.version = 0
          }
          newFile.version++
        }

        if (!newFile && oldFile) {
          // Remove file

          // Refused to remove the file
          // return prevent()
        }
      },
    },
  }
</script>
<style scoped>
  .drag-drop-area label.btn {
    margin-bottom: 0;
    margin-right: 1rem;
  }

  .drag-drop-area .drop-active {
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    position: fixed;
    z-index: 9999;
    opacity: .6;
    text-align: center;
    background: #000;
  }

  .drag-drop-area .drop-active h3 {
    margin: -.5em 0 0;
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
    font-size: 40px;
    color: #fff;
    padding: 0;
  }
</style>
