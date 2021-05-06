import * as API from "@/admin/services/API";

export default {
  getUser(userId) {
    return API.apiClient.get(`/api/users/${userId}`);
  },
  getUsers(page) {
    return API.apiClient.get(`/api/users/?page=${page}`);
  },
  paginateUsers(link) {
    return API.apiClient.get(link);
  },
  async deleteUser(payload) {
    await API.apiClient.get("/sanctum/csrf-cookie");
    return API.apiClient.delete(`/api/user/${payload}`);
  },
};
