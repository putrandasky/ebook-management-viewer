<template>
  <draggable v-bind="dragOptions" ghost-class="ghost" :list="chapters" @start="drag = true" @end="endDragging">
    <transition-group tag="div" class="row" type="transition" :name="!drag ? 'flip-list' : null">
      <b-col v-for="(v) in chapters" :key="v.id" class="mt-3 d-flex justify-content-center align-items-center" lg="3" md="4" sm="6" cols="12">
        <b-overlay blur="" rounded :no-center="v.type == 'folder'" :show="showOverlay == v.id " :variant="v.type == 'file'?'dark':'outline-light'" v-on:mouseleave.native="showOverlay = 0">
          <template #overlay>
            <div class="d-flex " :class="v.type =='folder'?'folders-icons position-absolute':''">

              <b-button pill class="mx-1" :variant="v.type == 'file'?'outline-danger':'danger'">
                <i class="fa fa-trash"></i>
              </b-button>
              <b-button pill class="mx-1" :variant="v.type == 'file'?'outline-light':'dark'" @click="openDetail(v)">
                <i class="fa fa-search"></i>
              </b-button>
              <b-button v-if="v.type == 'folder'" pill class="mx-1" :variant="v.type == 'file'?'outline-light':'dark'">
                <i class="fa fa-pencil"></i>
              </b-button>
            </div>
          </template>
          <div v-on:mouseenter="showOverlay = v.id">
            <b-card style="max-width:246px" class="shadow-sm" no-body img-top v-if="v.type == 'file'">
              <b-card-img v-if="v.type == 'file'" fluid :src="`/storage/book/${ebook.id}/tmb256-${v.pages.original_name}`"></b-card-img>
            </b-card>
            <div class="ffolder big gray mx-sm-3 my-lg-0 my-md-3  my-0" v-if="v.type == 'folder'">
              <span>{{v.pages_count}} Pages</span>
              <br>
              <div class="text-light">{{v.name}}</div>
            </div>
          </div>

        </b-overlay>

      </b-col>
    </transition-group>
  </draggable>
</template>
<script>
  export default {
    name: 'ChaptersList',
    props: {
      chapters: Array,
      ebook: Object
    },
    data: function() {
      return {
        showOverlay: 0,
        drag: false,

      }
    },
    created() {},
    computed: {
      dragOptions() {
        return {
          animation: 200,
          group: "order",
          disabled: false,
          ghostClass: "ghost"
        };
      }

    },
    methods: {
      openDetail(data) {
        if (data.type == 'folder') {
          this.$router.push({
            name: 'pages',
            params: {
              chapterId: data.id
            }
          })
        }
        if (data.type == 'file') {
          this.$router.push({
            name: 'pageDetail',
            params: {
              pageId: data.pages.id,
            }
          })
        }

      },
      endDragging() {
        this.drag = false
        this.reorder()
      },
      reorder() {
        axios.post(`/api/admin/chapter/reorder`, {
            chapters: this.chapters
          })
          .then((response) => {
            console.log(response.data)

          })
          .catch((error) => {
            console.log(error);
          })
      },
    },
  }
</script>
<style>
</style>
