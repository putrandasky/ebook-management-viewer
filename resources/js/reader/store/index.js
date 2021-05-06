import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const MAINDATA = 'MAINDATA';
const SETCURRENTDATA = 'SETCURRENTDATA';
const SETNEXTDATA = 'SETNEXTDATA';
const SETTHUMBNAILS = 'SETTHUMBNAILS';
const SETTHUMBNAILSDATA = 'SETTHUMBNAILSDATA';
const SETALLFACINGPAGEDATA = 'SETALLFACINGPAGEDATA';
const SETREADERMODE = 'SETREADERMODE';
const SETSHOWBOOKMARKSMODAL = 'SETSHOWBOOKMARKSMODAL';
const SETCROPMODE = 'SETCROPMODE';
const SETCROPPREVIEWSRC = 'SETCROPPREVIEWSRC';
const SETSHOWCROPPREVIEWMODAL = 'SETSHOWCROPPREVIEWMODAL';
const SETPARENTODDPAGEDATA = 'SETPARENTODDPAGEDATA';
const SETPARENTODDPAGEDATA2 = 'SETPARENTODDPAGEDATA2';
const SETCLIENTXSTART = 'SETCLIENTXSTART';
const SETCLIENTXEND = 'SETCLIENTXEND';

export const store = new Vuex.Store({
  state: {
    ebook: {},
    chapters: [],
    pages: [],
    currentIndex: 0,
    currentChapter: {},
    currentPage: {
      id: null,
      order: null,
      original_name: "",
    },
    nextPage: {
      id: null,
      order: null,
      original_name: "",
    },
    thumbnailsData: [],
    thumbnails: false,
    facingPageData: [],
    readerMode: 'single',
    filteredData: [],
    showSearchResultSidebar: false,
    showBookmarksModal: false,
    showCropPreviewModal: false,
    cropMode: false,
    cropPreviewSrc: "",
    clientX: {
      start: null,
      end: null
    },
    parentPage: {
      odd: {
        height: 0,
        width: 0
      },
      even: {
        height: 0,
        width: 0
      }
    }
  },
  mutations: {

    [MAINDATA](state, n) {
      state.ebook = n.ebook;
      state.chapters = n.chapters;
      state.pages = n.pages;
    },
    [SETCURRENTDATA](state, n) {
      state.currentIndex = n.index;
      state.currentChapter = n.chapter;
      state.currentPage = n.page;
    },
    [SETNEXTDATA](state, n) {
      state.nextPage = n;
    },
    [SETTHUMBNAILSDATA](state, n) {
      state.thumbnailsData = n;
    },
    [SETTHUMBNAILS](state, n) {
      state.thumbnails = n;
    },
    [SETALLFACINGPAGEDATA](state, n) {
      state.facingPageData = n;
    },
    [SETREADERMODE](state, n) {
      state.readerMode = n;
    },
    [SETSHOWBOOKMARKSMODAL](state, n) {
      state.showBookmarksModal = n;
    },
    [SETCROPMODE](state, n) {
      state.cropMode = n;
    },
    [SETCROPPREVIEWSRC](state, n) {
      state.cropPreviewSrc = n;
    },
    [SETSHOWCROPPREVIEWMODAL](state, n) {
      state.showCropPreviewModal = n;
    },
    [SETPARENTODDPAGEDATA](state, n) {
      state.parentPage.odd = n;
    },
    [SETPARENTODDPAGEDATA2](state, n) {
      state.parentPage.even = n;
    },
    [SETCLIENTXSTART](state, n) {
      state.clientX.start = n;
    },
    [SETCLIENTXEND](state, n) {
      state.clientX.end = n;
    },

  },
  actions: {

    async swipeAction({
      state,
      commit,
      dispatch
    }, data) {
      commit(SETCLIENTXEND, data)
      let deltaClient = state.clientX.start - state.clientX.end
      if (deltaClient > -10 && deltaClient < 10) {
        return
      }
      let direction = state.clientX.start > state.clientX.end ? 'left' : 'right'
      dispatch('updateCurrentPageByArrowButton', direction)
    },
    async updateCurrentPageByArrowButton({
      state,
      commit,
      dispatch
    }, direction) {
      let increment = 1
      if (state.readerMode == "facing") {
        increment = 2
      }
      if (direction == "right") {
        let nextIndex = state.currentIndex + increment
        nextIndex > state.pages.length - 1 ? nextIndex = state.pages.length - 1 : ""
        dispatch('updateCurrentPageByIndex', nextIndex)
      }
      if (direction == "left") {
        let prevIndex = state.currentIndex - increment
        prevIndex < 0 ? prevIndex = 0 : ""
        dispatch('updateCurrentPageByIndex', prevIndex)
      }
    },
    async updateCurrentPageByPageLabel({
      commit,
      dispatch
    }, value) {
      let splitedValue = value.split("-")
      let index = await this.dispatch('findIndexByPageLabel', splitedValue)
      dispatch('updateCurrentPageByIndex', index)
    },
    async updateCurrentPageByPageID({
      commit,
      dispatch
    }, pageId) {
      let index = await dispatch('findIndexByPageID', pageId)
      dispatch('updateCurrentPageByIndex', index)
    },
    async updateCurrentPageByIndex({
      commit,
      dispatch
    }, index) {
      let data = await dispatch('getDataByIndex', index)
      commit(SETCURRENTDATA, data)
      dispatch('updateNextPage')
    },
    updateNextPage({
      state,
      commit
    }) {
      let nextPage = state.pages[state.currentIndex + 1]
      if (typeof nextPage == 'undefined') {
        let data = {
          id: null,
          order: null,
          original_name: ""
        }
        commit(SETNEXTDATA, data)
        return
      }
      commit(SETNEXTDATA, nextPage)
      return
    },
    getDataByIndex({
      state
    }, index) {
      let data = {
        index: index,
        chapter: state.pages[index].chapter,
        page: state.pages[index],
      }
      return data
    },
    findIndexByPageLabel({
      state
    }, value) {
      if (value.length == 2) {
        for (let i = 0; i < state.pages.length; i++) {
          if (state.pages[i].order == value[1] && (state.pages[i].chapter.alias == value[0] || state.pages[i].chapter.order == value[0])) {
            return i;
          }
        }
      }
      if (value.length == 1) {
        for (let i = 0; i < state.pages.length; i++) {
          if (state.pages[i].chapter.alias == value[0] || state.pages[i].chapter.order == value[0]) {
            return i;
          }
        }
      }
    },
    findIndexByPageID({
      state
    }, pageId) {
      for (let i = 0; i < state.pages.length; i++) {
        if (state.pages[i].id == pageId) {
          return i;
        }
      }
    },
    changeReaderMode({
      state,
      commit
    }, mode) {
      commit(SETREADERMODE, mode)
      commit(SETTHUMBNAILS, false)
    },
    setAllFacingPageData({
      state,
      commit
    }, data) {
      let facingPageData = []

      for (let index = data.startIndex; index <= data.lastIndex; index += 2) {
        let shiftedIndex = index + 1
        let evenData = null
        let oddData = state.pages[index]

        if (typeof state.pages[shiftedIndex] !== 'undefined' && shiftedIndex <= data.lastIndex) {
          evenData = state.pages[shiftedIndex]
          //check if between page has different chapter
          if (data.blankPages) {
            if ((evenData.chapter.type != oddData.chapter.type) ||
              evenData.chapter.type == 'folder' &&
              oddData.chapter.type == 'folder' &&
              (evenData.chapter.id !== oddData.chapter.id)
            ) {
              //shifting even page to odd page on the next page
              evenData = null
              index--
            }
          }
        }
        let page = {
          even: evenData,
          odd: oddData
        }
        facingPageData.push(page)
      }
      if (data.thumbnails) {
        commit(SETTHUMBNAILSDATA, facingPageData)
      }
      if (!data.thumbnails) {
        commit(SETALLFACINGPAGEDATA, facingPageData)
      }

    }

  },
  getters: {

    updateHeight: (state) => (data) => {
      return parseFloat(state.parentPage.odd.height / data.parent_height * data.area_height)
    },
    updateTop: (state) => (data) => {
      return parseFloat(state.parentPage.odd.height / data.parent_height * data.area_top)
    },
    updateWidth: (state) => (data) => {
      return parseFloat(state.parentPage.odd.width / data.parent_width * data.area_width)
    },
    updateLeft: (state) => (data) => {
      return parseFloat(state.parentPage.odd.width / data.parent_width * data.area_left)
    },
    updateHeight2: (state) => (data) => {
      return parseFloat(state.parentPage.even.height / data.parent_height * data.area_height)
    },
    updateTop2: (state) => (data) => {
      return parseFloat(state.parentPage.even.height / data.parent_height * data.area_top)
    },
    updateWidth2: (state) => (data) => {
      return parseFloat(state.parentPage.even.width / data.parent_width * data.area_width)
    },
    updateLeft2: (state) => (data) => {
      return parseFloat(state.parentPage.even.width / data.parent_width * data.area_left)
    },
  }
});
