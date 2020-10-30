<template>
  <section style="width: 100%;background-color: #f5f8fb;border-radius: 10px;">
    <NavMain />
    <v-content
      style="clear: both;padding: 2%;margin:0;width: 96%;margin:1% 1%;background-color:#fff;"
    >
      <section class="breadCrumbsBox">
        <v-breadcrumbs :items="breadCrumbsData" large></v-breadcrumbs>
      </section>
      <section class="clearfix"></section>
      <br />

      <section id="profileBox">
        <h3 style="font-weight: bolder;text-align:left;">
          Profile
          <i @click="editProfile()" class="fa fa-edit" style="margin-right: 10px;"></i>
        </h3>
        <div style="width: 18%;float:left;">
          <img
            style="border-radius: 50%"
            :src="require('../assets/male.jpeg')"
            :alt="userData.first_name"
            :title="userData.first_name"
            width="150px"
          />
        </div>
        <div style="width: 80%;float:left;">
          <p v-if="isActive" style="color:#00ff00;font-weight: bold">
            <i class="fa fa-power-on" style="margin-right: 10px;"></i>
            {{userData.status}}
          </p>
          <p v-else style="color:#ff0000;font-weight: bold">
            <i class="fa fa-power-off" style="margin-right: 10px;"></i>
            {{userData.status}}
          </p>
          <p>
            <i class="fas fa-user" style="margin-right: 10px;"></i>
            {{userData.first_name}} {{userData.middle_name}} {{userData.last_name}}
          </p>
          <p>
            <i class="fa fa-envelope" style="margin-right: 10px;"></i>
            {{userData.email}}
          </p>
          <p>
            <i class="fa fa-phone" style="margin-right: 10px;"></i>
            {{userData.mobile}}
          </p>
          <p title="Copy your link and share to other users">
            <i class="fa fa-users" style="margin-right: 10px;"></i>
            {{fullReferrerCode}}
          </p>
        </div>
        <div style="clear:both;"></div>
      </section>
    </v-content>
    <Footer />
  </section>
</template>

<script>
import axios from "axios";
import { profileMixin } from "../mixins/profileMixin.js";
import store from "../store";
import NavMain from "../components/Navs/NavMain.vue";
import Footer from "../components/Footers/Footer.vue";

export default {
  name: "Profile",
  mixins: [profileMixin],
  components: {
    NavMain,
    Footer
  },
  created: function() {
    let tempToken = localStorage.getItem(store.state.setTokenLocalStorageKey);

    if (tempToken === "") {
      //GOT TOKEN
      this.$router.push("/#");
    }

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
    console.log(localStorage.getItem(store.state.setTokenLocalStorageKey));

    axios
      .post(this.endpoint, dataToSend, config)
      .then(function(response) {
        if (response.data.status) {
          vmObjectInstance.userData = response.data.data;
          vmObjectInstance.profileName =
            vmObjectInstance.userData.first_name +
            " " +
            vmObjectInstance.userData.last_name;
          store.commit(
            "setUserFirstName",
            vmObjectInstance.userData.first_name
          );
          store.commit("setUserLastName", vmObjectInstance.userData.last_name);

          if (vmObjectInstance.userData.status == "ACTIVE") {
            vmObjectInstance.isActive = true;
          } else {
            vmObjectInstance.isActive = false;
          }

          if (vmObjectInstance.userData.referral_codes != "") {
            vmObjectInstance.fullReferrerCode =
              store.state.urlStore.siteUrl +
              "#/Signup/" +
              vmObjectInstance.userData.referral_codes;
          }

          vmObjectInstance.breadCrumbsData = [
            {
              text: "Edit Profile",
              disabled: false,
              href: "#/EditProfile/" + vmObjectInstance.profileName
            },
            {
              text: "View Profile",
              disabled: true,
              href: "#/"
            }
          ];
        } else {
          vmObjectInstance.logout();
        }
      })
      .catch(function(error) {
        console.error(error);
      });
  },
  data: function() {
    return {
      loadingPage: true,
      isLogin: store.state.isLogin,
      userData: "",
      profileName: "",
      isActive: false,
      fullReferrerCode: "",
      endpoint:
        store.state.urlStore.baseUrl + store.state.urlStore.getUserByMobileUrl,
      serverUrl: store.state.urlStore.serverUrl,
      breadCrumbsData: [
        {
          text: "Edit Profile",
          disabled: false,
          href: "#/EditProfile/" + this.profileName
        },
        {
          text: "View Profile",
          disabled: true,
          href: "#/"
        }
      ]
    };
  },
  methods: {
    editProfile() {
      // this.$router.push(this.breadCrumbsData[0].href);
      // window.open(this.breadCrumbsData[0].href); // open in new tab
      location.replace(this.breadCrumbsData[0].href);
    }
  }
};
</script>


<style scoped>
#profileBox {
  clear: both;
}

#profileBox p {
  float: left;
  width: 100%;
  text-align: left;
}
</style>