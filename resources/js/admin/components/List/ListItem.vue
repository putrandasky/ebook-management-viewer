<template>
  <draggable v-bind="dragOptions" ghost-class="ghost" :list="items" @start="drag = true" @end="endDragging">
    <transition-group tag="div" class="row mx-5" type="transition" :name="!drag ? 'flip-list' : null">
      <b-col v-for="(v,i) in items" :key="v.id" class="mt-3 px-0 d-flex justify-content-center align-items-center" lg="3" md="4" sm="6" cols="12">
        <b-overlay blur="" rounded :no-center="shouldNoCenter(v)" :show="showOverlay == v.id " :variant="defineVariant(v)" v-on:mouseleave.native="showOverlay = 0">
          <template #overlay>
            <slot name="buttons" v-bind:item="v" v-bind:index="i"></slot>
          </template>
          <div v-on:mouseenter="showOverlay = v.id">
            <slot name="items" v-bind:item="v" v-bind:index="i"></slot>
          </div>

        </b-overlay>

      </b-col>
    </transition-group>
  </draggable>
</template>
<script>
  export default {
    name: 'ListItem',
    props: {
      items: Array,
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
      defineVariant(data) {
        if (typeof data.type === 'undefined') {
          return 'dark'
        }
        return data.type == 'file' ? 'dark' : 'outline-light'
      },
      shouldNoCenter(data) {
        if (typeof data.type === 'undefined') {
          return false
        }
        return data.type == 'folder'

      },
      endDragging() {
        this.drag = false
        this.$emit('endDragging', this.items)
      },

    },
  }
</script>
<style>
</style>
