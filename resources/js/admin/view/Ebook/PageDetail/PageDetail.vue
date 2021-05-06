<template>
  <b-container class="pt-3">
    <div class="w-100 mb-2">
      <b-btn size="sm" variant="outline-secondary" class="shadow-sm" @click="previousPage()">Back</b-btn>
    </div>
    <b-row>
      <b-col md="6" order="2" order-md="1">
        <b-card v-if="isLoaded" id="parent" ref="parent" class="shadow-sm " no-body img-top>
          <template v-if="page.hotspotlinks.length>0 && imageLoaded">
            <vue-draggable-resizable v-for="(v,i) in page.hotspotlinks" :key="i" style="z-index:900" drag-handle=".inner-box" :w="v.area_width" :h="v.area_height" :x="v.area_left" :y="v.area_top" :resizable="draggerBehavior.isResizeable" :draggable="draggerBehavior.isDraggable" @dragging="handleDrag(i,...arguments)" @resizing="handleResize(i,...arguments)" :active="true" :prevent-deactivation="true" :parent="true">
              <div v-if="isAreaChecking !== i" class="inner-box bg-danger" :style="{width:v.area_width+'px',height:v.area_height+'px'}">
                <!-- <p>{{v.area_left}} x {{v.area_top}}</p>
                <br>
                <p>{{v.area_width}} x {{v.area_height}}</p> -->
              </div>
            </vue-draggable-resizable>
          </template>
          <b-card-img @load="onLoadImage" style="z-index:90" fluid :src="`/storage/book/${page.chapter.ebook.id}/${page.original_name}`"></b-card-img>

        </b-card>
      </b-col>
      <b-col class="mb-3" md="6" order="1" order-md="2">
        <form-update class="mb-3" :name="page.name" :is_marked="page.is_marked"></form-update>

        <b-card title="Hotspot Links">
          <b-btn class="float-right" variant="outline-secondary" size="sm" @click="addHotspotLink">Add Hotspot</b-btn>
          <div style="clear:both" v-if="page.hotspotlinks.length>0">
            <b-form-group v-for="(v,i) in page.hotspotlinks" :key="v.id" label="Link Address" label-for="name">
              <b-input-group>
                <template #prepend>
                  <b-button @mousedown="onMouseDownCheckArea(i)" @mouseup="onMouseUpCheckArea(i)" variant="secondary"><i class="fa fa-crosshairs"></i></b-button>
                </template>
                <b-form-input required min="5" placeholder="Input link with 'https:// " v-model="v.address"></b-form-input>
                <template #append>
                  <b-button @click="deleteHotspotLink(i)" variant="danger"><i class="fa fa-close"></i></b-button>
                </template>
              </b-input-group>
            </b-form-group>
            <b-btn class="float-right" variant="secondary" size="sm" @click="submitHotspotLink">Submit</b-btn>

          </div>
        </b-card>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
  import FormUpdate from './PageDetailFormUpdate'
  import VueDraggableResizable from 'vue-draggable-resizable'
  import 'vue-draggable-resizable/dist/VueDraggableResizable.css'
  import debounce from '@/admin/package/debounce'

  export default {
    name: 'PageDetail',
    components: {
      FormUpdate,
      VueDraggableResizable

    },
    data: function() {
      return {
        imageLoaded: false,
        currentArea: 0,
        isLoaded: false,
        page: {
          hotspotlinks: []
        },
        showingHotspot: [],
        isAreaChecking: null,
        parent: {
          parent_height: 0,
          parent_width: 0
        },
        draggerBehavior: {
          isResizeable: true,
          isDraggable: true,
        },
      }
    },
    created() {
      this.getData()
    },
    mounted() {
      this.$nextTick(function() {
        window.addEventListener('resize', this.updateParentPageData)
      })
    },
    destroyed() {
      window.removeEventListener('resize', this.updateParentPageData)
    },
    watch: {
      'parent.parent_height': function(newVal, oldVal) {
        for (let i = 0; i < this.page.hotspotlinks.length; i++) {
          let oldData = this.page.hotspotlinks[i]
          this.page.hotspotlinks[i].area_top = parseFloat(newVal / oldData.parent_height * oldData.area_top)
          this.page.hotspotlinks[i].area_height = parseFloat(newVal / oldData.parent_height * oldData.area_height)
          this.page.hotspotlinks[i].parent_height = parseFloat(newVal)

        }

      },
      'parent.parent_width': function(newVal, oldVal) {
        for (let i = 0; i < this.page.hotspotlinks.length; i++) {
          let oldData = this.page.hotspotlinks[i]
          this.page.hotspotlinks[i].area_width = parseFloat(newVal / oldData.parent_width * oldData.area_width)
          this.page.hotspotlinks[i].area_left = parseFloat(newVal / oldData.parent_width * oldData.area_left)
          this.page.hotspotlinks[i].parent_width = parseFloat(newVal)
        }
      },
    },
    methods: {
      onMouseDownCheckArea(i) {
        this.isAreaChecking = i
      },
      onMouseUpCheckArea(i) {
        this.isAreaChecking = null

      },
      previousPage() {
        let type = this.page.chapter.type
        if (type == 'file') {
          this.$router.push({
            name: 'chapters',
            params: {
              slug: this.$route.params.slug,
            }
          })
        }
        if (type == 'folder') {

          this.$router.push({
            name: 'pages',
            params: {
              slug: this.$route.params.slug,
              chapterId: this.page.chapter_id,
            }
          })
        }
      },
      deleteHotspotLink(i) {

        if (typeof this.page.hotspotlinks[i].id == 'undefined') {
          this.page.hotspotlinks.splice(i, 1)
          return
        }
        let id = this.page.hotspotlinks[i].id
        axios.delete(`/api/admin/hotspotlinks/${id}`)
          .then((response) => {
            this.page.hotspotlinks.splice(i, 1)
            this.toastSuccess(response.data.message)
          })
          .catch((error) => {
            console.log(error);
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })

      },
      submitHotspotLink() {
        axios.post(`/api/admin/hotspotlinks/${this.$route.params.pageId}`, {
            data: this.page.hotspotlinks
          })
          .then((response) => {
            this.toastSuccess(response.data.message)

          })
          .catch((error) => {
            console.log(error);
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      },
      addHotspotLink() {
        this.page.hotspotlinks.push({
          //   id: 1,
          address: '',
          area_top: 0,
          area_left: 0,
          area_width: 100,
          area_height: 100,
          parent_height: this.parent.parent_height,
          parent_width: this.parent.parent_width,
        })
      },
      onLoadImage() {
        this.$nextTick(function() {
          this.imageLoaded = true
          this.updateParentPageData()
        })
      },
      updateParentPageData() {

        let data = {
          parent_height: this.$refs.parent.clientHeight,
          parent_width: this.$refs.parent.clientWidth
        }
        this.parent = data

      },
      handleDrag(i, x, y) {

        this.page.hotspotlinks[i].area_top = y
        this.page.hotspotlinks[i].area_left = x
      },
      handleResize(i, x, y, width, height) {

        this.page.hotspotlinks[i].area_top = y
        this.page.hotspotlinks[i].area_left = x
        this.page.hotspotlinks[i].area_width = width
        this.page.hotspotlinks[i].area_height = height
      },
      getData() {
        axios.get(`/api/admin/page/${this.$route.params.pageId}`)
          .then((response) => {
            this.page = response.data
            for (let i = 0; i < this.page.hotspotlinks.length; i++) {
              this.showingHotspot.push(true)
            }
            this.isLoaded = true

          })
          .catch((error) => {
            console.log(error);
            if ([401, 419].includes(error.response.status)) {
              return
            }
            this.toastError("Ooops, There's Something Error")
          })
      }
    },
  }
</script>
<style scoped>
  .vdr {
    border: 1px dashed #000;
  }


  .vdr>.inner-box {
    display: flex;
    justify-content: center;
    align-items: center;
    vertical-align: middle;
    padding: 3px;
    opacity: 0.3;
    cursor: move;
  }
</style>>
