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
    <p class="text-muted">You cannot edit company name and email</p>
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
              <v-alert
              v-show="showErrorAlert"
              dense
              text
              type="error"
              >
              {{errorMessage}}
              </v-alert>
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
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                v-model="companyData.name"
                title="Readonly"
                label="Company Name"
                readonly
              ></v-text-field>
            </v-card>
          </v-col>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field v-model="companyData.email" title="Readonly" label="Email" readonly></v-text-field>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="grey lighten-5">
        <v-row>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                v-model="phone"
                :error-messages="phoneErrors"
                label="Phone"
                required
                :counter="50"
                @input="$v.phone.$touch()"
                @blur="$v.phone.$touch()"
              ></v-text-field>
            </v-card>
          </v-col>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field v-model="website" label="Website" :counter="250"></v-text-field>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-container class="grey lighten-5">
        <v-row>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-textarea
                v-model="about"
                :counter="500"
                label="About Company"
                @input="$v.about.$touch()"
                @blur="$v.about.$touch()"
              ></v-textarea>
            </v-card>
          </v-col>
          <v-col cols="6">
            <v-container class="grey lighten-5">
              <v-row>
                <v-col cols="8" md="8">
                  <v-file-input
                    :rules="rules"
                    accept="image/*"
                    placeholder="Company Logo (image format only)"
                    hint="Company Logo (Image format only)"
                    v-model="logoFile"
                    error-message="Please Update Company Logo"
                    required
                    label="Company Logo"
                    show-size
                    counter
                    chips
                  ></v-file-input>
                </v-col>

                <v-col cols="4" md="4">
                  <img
                    v-show="companyLogoExist"
                    :src="companyLogo"
                    alt="company logo"
                    title="company logo"
                    width="100px"
                  />
                </v-col>
              </v-row>
            </v-container>
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
import { required, maxLength } from "vuelidate/lib/validators";
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
    showErrorAlert:false,
    successMessage: "",
    errorMessage: "",
    endpoint: store.state.urlStore.baseUrl + store.state.urlStore.updateUserUrl,
    endpointGetProfileByName:
      store.state.urlStore.baseUrl + store.state.urlStore.getUserByNameUrl,
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
    logoFile: null,
    profileName: "",
    phone: "",
    website: "",
    about: "",
    companyLogoExist: false,
    companyLogo: "",
    companyData: {
      id: "",
      name: "",
      email: "",
      website: "",
      phone: "",
      about: "",
      logo: ""
    },
    rules: [
      value =>
        !value ||
        value.size < store.state.companyLogoUploadLimit ||
        "image size should be less than 5 MB!"
    ]
  }),
  mixins: [validationMixin],
  validations: {
    phone: { required, maxLength: maxLength(50) }
  },
  computed: {
    phoneErrors() {
      const errors = [];
      if (!this.$v.phone.$dirty) return errors;
      !this.$v.phone.maxLength &&
        errors.push("Phone must be at most 50 characters long");
      !this.$v.phone.required && errors.push("Phone is required.");
      return errors;
    }
  },
  methods: {
    submit() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.showLoadingIcon = true;
        this.sendingRequest = true;
        this.companyData.phone = this.phone;
        this.companyData.website = this.website;
        this.companyData.about = this.about;
        let url = this.endpoint + this.companyData.id;

        let vmObjectInstance = this;
        let dataToSend = {
          phone: this.companyData.phone,
          website: this.companyData.website,
          about: this.companyData.about
        };
        // console.log("edit profile >>> " , this.companyData);

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
    handleLogoFileUpload(vmObjectInstance, fileToUpload) {
      let formData = new FormData();
      formData.append("file", fileToUpload);
      formData.append("userID", vmObjectInstance.companyData.id);

      let url =
        store.state.urlStore.baseUrl + store.state.urlStore.updateCompanyLogo;
      console.log("upload url " + url);

      let config = {
        headers: {
          Authorization: `Bearer ${localStorage.getItem(
            store.state.setTokenLocalStorageKey
          )}`
        }
      };

      axios
        .post(url, formData, config)
        .then(data => {
          if (data.status === 200) {
 
            let vmObjectInstance = this;
            // this.getProfileByEmail(vmObjectInstance, email);

            vmObjectInstance.successMessage = "Company Logo Updated";
            vmObjectInstance.showSuccessAlert = true;
          }
        })
        .catch(function() {
          // console.log("UPLOAD FAILURE (company Logo upload)");
          vmObjectInstance.errorMessage =
            "UPLOAD FAILURE (company Logo upload)";
          vmObjectInstance.showErrorAlert = true;
        });
    },
    getProfile(){
      this.profileName = this.$route.params.name;
      let vmObjectInstance = this;

      let config = {
        headers: {
          Authorization: `Bearer ${localStorage.getItem(
            store.state.setTokenLocalStorageKey
          )}`
        }
      };

      vmObjectInstance.loadingPage = true;
      axios
        .get(this.endpointGetProfileByName + this.profileName, config)
        .then(function(response) {
          // console.log("response get Profile >>> ", response);
          vmObjectInstance.loadingPage = false;
          if (response.data.status) {
            vmObjectInstance.companyData = response.data.data;
            vmObjectInstance.phone = vmObjectInstance.companyData.phone;
            vmObjectInstance.website = vmObjectInstance.companyData.website;
            vmObjectInstance.about = vmObjectInstance.companyData.about;
            vmObjectInstance.companyLogo =
              vmObjectInstance.serverBaseUrl + vmObjectInstance.companyData.logo;

            if (vmObjectInstance.companyLogo != null) {
              vmObjectInstance.companyLogoExist = true;
            }
          }
        })
        .catch(function(error) {
          console.error(error);
        });
    
      }
  },
  watch: {
    logoFile: function(val) {
      let vmObjectInstance = this;
      this.handleLogoFileUpload(vmObjectInstance, val);
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
