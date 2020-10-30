<template>
  <section class="container">
    <NavMain />
    <section class="breadCrumbsBox">
      <v-breadcrumbs :items="breadCrumbsData" large></v-breadcrumbs>
    </section>
    <section class="clearfix"></section>
    <br />
    <br />

    <h1>
      Edit Profile
      <i class="fas fa-user-tie iconlight"></i>
    </h1>
    <p class="text-muted">You can only edit middle name</p>
    <form>
      <sweetalert-icon icon="loading" v-show="showLoadingIcon" />
      <sweetalert-icon icon="success" v-show="showSuccessIcon" />
      <sweetalert-icon icon="error" v-show="showErrorIcon" />

      <v-row justify="center">
        <v-dialog v-model="sendingRequest" hide-overlay persistent width="300">
          <v-card color="green" dark>
            <v-card-text>
              Sending Request Please Stand By
              <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-row>

      <v-row justify="center">
        <v-dialog v-model="showErrorAlert" persistent max-width="290">
          <v-card>
            <v-alert v-show="showErrorAlert" dense text type="error">{{errorMessage}}</v-alert>
            <sweetalert-icon icon="error" v-show="showErrorAlert" />
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green darken-1" text @click="showErrorAlert = false">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>

      <v-row justify="center">
        <v-dialog v-model="showSuccessAlert" persistent max-width="290">
          <v-card>
            <v-alert v-show="showSuccessAlert" dense text type="success">{{successMessage}}</v-alert>
            <sweetalert-icon icon="success" v-show="showSuccessAlert" />
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green darken-1" text @click="showSuccessAlert = false">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>

      <v-container class="grey lighten-5">
        <v-row>
          <v-col cols="4">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                v-model="userData.first_name"
                title="Readonly"
                label="First Name"
                readonly
              ></v-text-field>
            </v-card>
          </v-col>
          <v-col cols="4">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                :error-messages="middleNameErrors"
                v-model="middle_name"
                label="Middle Name"
              ></v-text-field>
            </v-card>
          </v-col>
          <v-col cols="4">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                v-model="userData.last_name"
                title="Readonly"
                label="last Name"
                readonly
              ></v-text-field>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="grey lighten-5">
        <v-row>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                v-model="userData.mobile"
                label="Mobile"
                title="Readonly"
                required
                readonly
              ></v-text-field>
            </v-card>
          </v-col>

          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field v-model="userData.email" title="Readonly" label="Email" readonly></v-text-field>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <br />
      <br />

      <v-btn
        color="green"
        class="mr-4"
        style="color: #ffffff"
        @click="submit"
        v-show="!showLoadingIcon"
      >
        <i class="fas fa-user-tie"></i> Update Profile
      </v-btn>

      <v-btn
        color="green"
        class="mr-4"
        style="color: #ffffff"
        loading
        disabled
        v-show="showLoadingIcon"
      >
        <i class="fas fa-user-tie"></i> Update Profile
      </v-btn>
    </form>
    <Footer />
  </section>
</template>

<script>
import { validationMixin } from "vuelidate";
import { maxLength } from "vuelidate/lib/validators";
import store from "../store";
import axios from "axios";
import NavMain from "../components/Navs/NavMain.vue";
import Footer from "../components/Footers/Footer.vue";

export default {
  name: "EditProfile",
  components: {
    NavMain,
    Footer
  },
  created: function() {
    this.getProfile();
  },
  data: () => ({
    showLoadingIcon: false,
    showSuccessIcon: false,
    showErrorIcon: false,
    sendingRequest: false,
    showSuccessAlert: false,
    showErrorAlert: false,
    successMessage: "",
    errorMessage: "",
    endpoint: store.state.urlStore.baseUrl + store.state.urlStore.updateUserUrl,
    endpointGetProfileByMobile:
      store.state.urlStore.baseUrl + store.state.urlStore.getUserByMobileUrl,
    serverBaseUrl: store.state.urlStore.serverUrl,
    breadCrumbsData: [
      {
        text: "View Profile",
        disabled: false,
        href: "#/Profile"
      },
      {
        text: "Edit Profile",
        disabled: true,
        href: ""
      }
    ],
    middle_name: "",
    userData: {
      id: "",
      type: "",
      first_name: "",
      middle_name: "",
      last_name: "",
      email: ""
    }
  }),
  mixins: [validationMixin],
  validations: {
    middle_name: { maxLength: maxLength(50) }
  },
  computed: {
    middleNameErrors() {
      const errors = [];
      if (!this.$v.middle_name.$dirty) return errors;
      !this.$v.middle_name.maxLength &&
        errors.push("Middle must be at most 50 characters long");
      return errors;
    }
  },
  methods: {
    submit() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.showLoadingIcon = true;
        this.sendingRequest = true;
        this.userData.middle_name = this.middle_name;
        let url = this.endpoint + this.userData.id;

        let vmObjectInstance = this;
        let dataToSend = {
          middle_name: this.userData.middle_name
        };

        let config = {
          headers: {
            Authorization: `Bearer ${localStorage.getItem(
              store.state.setTokenLocalStorageKey
            )}`
          }
        };

        axios
          .put(url, dataToSend, config)
          .then(function(response) {
            if (response.status === 200) {
              vmObjectInstance.showLoadingIcon = false;
              vmObjectInstance.sendingRequest = false;
              vmObjectInstance.showErrorIcon = false;
              vmObjectInstance.successMessage = "Profile edited succesfully";
              vmObjectInstance.showSuccessAlert = true;
              vmObjectInstance.showSuccessIcon = true;
            } else {
              vmObjectInstance.showLoadingIcon = false;
              vmObjectInstance.showErrorIcon = true;
              vmObjectInstance.sendingRequest = false;
            }
          })
          .catch(function(error) {
            console.error(error);
            vmObjectInstance.showLoadingIcon = false;
            vmObjectInstance.showSuccessIcon = false;
            vmObjectInstance.sendingRequest = false;
            vmObjectInstance.showErrorIcon = true;
          });
      }
    },
    getProfile() {
      this.profileName = this.$route.params.name;
      let vmObjectInstance = this;
      vmObjectInstance.loadingPage = true;

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

      axios
        .post(this.endpointGetProfileByMobile, dataToSend, config)
        .then(function(response) {
          vmObjectInstance.loadingPage = false;
          if (response.data.status) {
            vmObjectInstance.userData = response.data.data;
            vmObjectInstance.middle_name =
              vmObjectInstance.userData.middle_name;
          }
        })
        .catch(function(error) {
          console.error(error);
        });
    }
  }
};
</script>

<style scoped>
#jobResponsibilityBox {
  background-color: #fafafa;
  width: 100%;
  border-radius: 10px;
}

#minimumQualificationBox {
  background-color: #fafafa;
  width: 100%;
  border-radius: 10px;
}

.listHeading {
  border-bottom: 1px solid #333333;
  width: auto;
}
</style>
