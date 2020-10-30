<template>
  <nav>
    <v-navigation-drawer
      v-bind:value="leftDrawer"
      app
      id="navigation"
      class="navigation-sidebar bg-primary"
    >
      <!--Navigation-->
      <div class="navigation-header">
        <!-- <a href="#">
            <span class="logo">GREEN - Admin</span>
        </a>-->
        <!--<img src="logo.png" alt="logo" class="brand" height="50">-->
      </div>

      <!--Navigation Profile area-->
      <div class="navigation-profile">
        <img class="profile-img rounded-circle" src="assets/images/male.jpeg" alt="profile image" />
        <h4 class="name">{{getDisplayName}}</h4>
        <span class="designation">{{getAccountNumber}}</span>

        <a
          style="display: none"
          id="show-user-menu"
          href="javascript:void(0);"
          class="circle-white-btn profile-setting"
        >
          <i class="fa fa-cog"></i>
        </a>

        <!--logged user hover menu-->
        <div class="logged-user-menu bg-white">
          <div class="avatar-info">
            <img class="profile-img rounded-circle" src="assets/images/1.jpg" alt="profile image" />
            <h4 class="name">Meera</h4>
            <span class="designation">UI/UX EXPERT</span>
          </div>

          <ul class="list-unstyled" style="border: 1px solid #00ff00;margin-top: -100px">
            <li>
              <a href="javascript:void(0);">
                <i class="ion-ios-email-outline"></i>
                <span>Emails</span>
              </a>
            </li>
            <li>
              <a href="javascript:void(0);">
                <i class="ion-ios-person-outline"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <a href="user_change-password.html">
                <i class="ion-ios-locked-outline"></i>
                <span>Change Password</span>
              </a>
            </li>
            <li>
              <a href="javascript:void(0);">
                <i class="ion-log-out"></i>
                <span>Logout</span>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!--Navigation Menu Links-->
      <div class="navigation-menu">
        <ul class="menu-items custom-scroll">
          <li>
            <router-link class="leftNavText" to="/Dashboard">
              <span class="icon-thumbnail">
                <i class="fa fa-home"></i>
              </span>
              <span class="title">Home</span>
            </router-link>
          </li>

          <li>
            <router-link class="leftNavText" to="/Profile">
              <span class="icon-thumbnail">
                <i class="fa fa-user"></i>
              </span>
              <span class="title">Profile</span>
            </router-link>
          </li>
          <li>
            <router-link class="leftNavText" to="/Wallet">
              <span class="icon-thumbnail">
                <i class="fa fa-folder"></i>
              </span>
              <span class="title">Wallet</span>
            </router-link>
          </li>
          <li>
            <a href="javascript:void()" class="leftNavText" to="/" @click="logout">
              <span class="icon-thumbnail">
                <i class="fa fa-power-off"></i>
              </span>
              <span class="title">Logout</span>
            </a>
          </li>
        </ul>
      </div>
    </v-navigation-drawer>

    <v-app-bar app color="green" dark>
      <v-app-bar-nav-icon @click.stop="toogleLeftDrawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Howdy, {{getDisplayName}}</v-toolbar-title>
    </v-app-bar>
  </nav>
</template>

<script>
import store from "../../store";
import { mapState } from "vuex";

export default {
  name: "NavTop",
  data: () => ({
    isLogin: localStorage.getItem(store.state.setIsLoginLocalStorageKey),
    email: localStorage.getItem(store.state.setEmailLocalStorageKey)
    // isLogin: true
  }),
  created: function() {},
  computed: mapState({
    leftDrawer: state => state.leftNavDrawerStore.leftDrawer,
    getLeftDrawer() {
      return store.state.leftNavDrawerStore.leftDrawer;
    },
    getAppName() {
      return store.state.commonStore.appName;
    },
    currentRouteName() {
      return this.$route.name;
    },
    getDisplayName() {
      return store.state.userFirstName + " " + store.state.userLastName;
    },
    getAccountNumber() {
      return store.state.accountNumber;
    }
  }),
  methods: {
    toogleLeftDrawer: function(event) {
      console.log("ToogleLeftDrawer ", event);
      store.commit("toogleLeftDrawer");
    },
    logout: function() {
      localStorage.setItem(store.state.setIsLoginLocalStorageKey, false);
      localStorage.setItem(store.state.setTokenLocalStorageKey, "");
      localStorage.setItem(store.state.setEmailLocalStorageKey, "");
      localStorage.setItem(store.state.setMobileLocalStorageKey, "");

      this.$router.push("Login");
      // this.$router.go(this.$router.currentRoute);

      // this.$router.push("/#");
    }
  }
};
</script>

<style scoped>
nav {
  /* border: 5px solid #ff0000; */
  box-shadow: 3px 0px 3px #333333;
  /* overflow-x: auto !important; */
  /* overflow-y: auto !important; */
  /* height: 100% !important; */
  /* margin: 0; */
}

.leftNavs {
  background-color: #4caf50;
  color: #ffffff;
  margin-bottom: 10px;
}

.leftNavs:hover {
  box-shadow: 5px 5px 5px #333333;
  border: 1px dotted #ffffff;
}

.iconlight {
  color: #ffffff;
}

.iconlight:hover {
  color: #ffff00;
}

.title {
  text-align: left;
  font-size: 0.8em !important;
  font-weight: 900 !important;
  color: #ffffff !important;
}

#navigation {
  background-image: url(../../../public/assets/images/login-background.jpg) !important;
  background-color: #28a745 !important;
}

#navigation:before {
  background-image: url(../../../public/assets/images/login-background.jpg) !important;
  background-color: #28a745 !important;
}
</style>
