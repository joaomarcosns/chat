import Vuex from "vuex";
import axios from "axios";
import createPersistedState from "vuex-persistedstate";

export default new Vuex.Store({
  state: {
    user: {},
  },
  mutations: {
    setUserState: (state, value) => (state.user = value),
  },
  actions: {
    userStateAction: ({ commit }) => {
      axios
        .get("/api/user")
        .then((response) => {
          commit("setUserState", response.data.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
  plugins: [createPersistedState()],
});
