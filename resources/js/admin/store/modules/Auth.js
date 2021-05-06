import router from "@/admin/router";
import {
  getError
} from "@/admin/utils/helpers";
import AuthService from "@/admin/services/AuthService";

export const namespaced = true;

export const state = {
  user: null,
  loading: false,
  error: null,
  toast: {
    show: false,
    title: '',
    content: '',

  }
};

export const mutations = {
  SET_USER(state, user) {
    state.user = user;
  },
  SET_LOADING(state, loading) {
    state.loading = loading;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
  SET_TOAST(state, n) {
    state.toast.show = n[0];
    state.toast.title = n[1];
    state.toast.content = n[2];
  }
};

export const actions = {
  logout({
    commit,
    dispatch
  }) {
    return AuthService.logout()
      .then(() => {
        commit("SET_USER", null);
        dispatch("setGuest", {
          value: "isGuest"
        });
        if (router.currentRoute.name !== "login")
          router.push({
            name: "login"
          });
      })
      .catch((error) => {
        commit("SET_ERROR", getError(error));
      });
  },
  async getAuthUser({
    commit
  }) {
    commit("SET_LOADING", true);
    try {
      const response = await AuthService.getAuthUser();
      commit("SET_USER", response.data.data);
      setTimeout(() => {
        commit("SET_LOADING", false);

      }, 500);
      return response.data.data;
    } catch (error) {
      commit("SET_LOADING", false);
      commit("SET_USER", null);
      commit("SET_ERROR", getError(error));
    }
  },
  setGuest(context, {
    value
  }) {
    window.localStorage.setItem("guest", value);
  },
};

export const getters = {
  authUser: (state) => {
    return state.user;
  },
  isAdmin: (state) => {
    return state.user ? state.user.isAdmin : false;
  },
  error: (state) => {
    return state.error;
  },
  loading: (state) => {
    return state.loading;
  },
  loggedIn: (state) => {
    return !!state.user;
  },
  guest: () => {
    const storageItem = window.localStorage.getItem("guest");
    if (!storageItem) return false;
    if (storageItem === "isGuest") return true;
    if (storageItem === "isNotGuest") return false;
  },
};
