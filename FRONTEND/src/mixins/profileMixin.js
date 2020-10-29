// import axios from "axios";
import store from "../store";

export const profileMixin = {
  data() {
    return {
      userData: null,
    }
  },
  computed: {},
  watch: {},
  methods: {
    logout: function () {
      localStorage.setItem(store.state.setIsLoginLocalStorageKey, false);
      localStorage.setItem(store.state.setTokenLocalStorageKey, "");
      localStorage.setItem(store.state.setEmailLocalStorageKey, "");

      this.$router.push("Login");
    }
  }
}