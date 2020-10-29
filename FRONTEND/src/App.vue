<template>
  <div id="app">
    <v-app id="inspire">
      <v-content>
        <v-container fluid fill-height>
          <v-layout>
            <router-view />
          </v-layout>
        </v-container>
      </v-content>
    </v-app>
  </div>
</template>

<script>
import store from "./store";
import axios from "axios";

export default {
  name: "App",
  created: function() {
    let vmObjectInstance = this;
    let config = {
      headers: {
        Authorization: `Bearer ${localStorage.getItem(
          store.state.setTokenLocalStorageKey
        )}`
      }
    };

    let dataToSend = {
      mobile: localStorage.getItem(store.state.setMobileLocalStorageKey)
    };
    this.appaGetProfileDataCall(vmObjectInstance, dataToSend, config);
  },
  data: () => ({
    // savedUsername: localStorage.getItem(store.state.setUsernameLocalStorageKey),
    appGetUserDataEndpoint:
      store.state.urlStore.baseUrl + store.state.urlStore.getUserByEmailUrl
  }),
  methods: {
    appaGetProfileDataCall(vmObjectInstance, dataToSend, headers) {
      axios
        .post(this.appGetUserDataEndpoint, dataToSend, headers)
        .then(function(response) {
          if (!response.data.status) {
            alert("From Data");
            vmObjectInstance.appLogout();
          }
        })
        .catch(function(error) {
          console.error(error);
          // vmObjectInstance.appLogout();
        });
    },
    appLogout: function() {
      localStorage.setItem(store.state.setIsLoginLocalStorageKey, false);
      localStorage.setItem(store.state.setTokenLocalStorageKey, "");
      localStorage.setItem(store.state.setEmailLocalStorageKey, "");

      this.$router.push("Login");
    }
  }
};
</script>

<style>
.leftNavText {
  font-size: 1.6em !important;
  font-weight: 900;
  color: #ffffff !important;
}

#appBox {
  width: 100% !important;
  display: block;
  margin: auto;
}

.actionButton {
  margin: 0 10px;
}

#leftNavLogout {
  border: none;
  background-color: transparent;
  box-shadow: none;
  font-weight: 900;
  /* font-size: 0.8em !important; */
  color: #ffffff !important;
  cursor: pointer;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}

a {
  color: #ffffff;
}

.leftNavs a {
  color: #ffffff !important;
}

.breadCrumbs {
  color: #42b983;
}

.breadCrumbsBox {
  float: right;
}

.breadCrumbsBox > a {
  color: #42b983;
}

.clearfix {
  clear: both;
}

.subMenuButton {
  float: left;
  margin-right: 20px;
  margin-top: 20px;
}

.btn {
  padding: 3%;
}

h1 {
  color: #4caf50;
  font-weight: 700;
  font-size: 2em;
}
</style>
