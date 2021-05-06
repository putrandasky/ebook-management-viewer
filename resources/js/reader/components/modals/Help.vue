<template>
  <b-modal id="helpModal" title="HELP" :no-close-on-backdrop="true" centered hide-footer>

    <b-card v-for="(v,i) in texts" :key="i" no-body class="mb-1">
      <b-card-header header-tag="header" class="p-1" role="tab">
        <b-button block v-b-toggle="`help-accordion-${i}`" variant="danger">
          {{v.title}}</b-button>
      </b-card-header>
      <b-collapse :id="`help-accordion-${i}`" visible accordion="help-accordion" role="tabpanel">
        <b-card-body>
          <b-card-text v-html="v.content"></b-card-text>
        </b-card-body>
      </b-collapse>
    </b-card>
  </b-modal>
</template>
<script>
  export default {
    name: 'Help',
    data: function() {
      return {
        texts: [{
            title: 'PREFACE',
            content: 'The digital edition is an interactive replica of the printed publication. Please see instructions below to help navigate the interface. You can always access this information from the button in the top-right corner.'
          },
          {
            title: 'NAVIGATING THE CATALOG',
            content: 'Type a page number into the white box at the top to jump to a specific page<br>Click any highlighted, or linked, page numbers in the publication for direct page navigation<br>Use the table of contents or thumbnail images to easily maneuver publication content'
          },
          {
            title: 'KEYWORD SEARCH',
            content: 'Enter a keyword into the search box<br>Search results will display in the search results window<br>Choose any result to be taken to the specific page, on which search results are highlighted'
          },
        ]
      }
    },
    created() {},
    methods: {},
  }
</script>
<style>
</style>
