<template>
  <div class="create-vendor container">
    <NavMain />
      <section class="breadCrumbsBox">
        <v-breadcrumbs :items="breadCrumbsData" large></v-breadcrumbs>
      </section>
      <div style="clear:both;"></div>
      <br><br>
        <h1>Upload Document</h1>
        <div class="clearfix"></div>
        
        <form>
          <v-row justify="center">
            <v-dialog v-model="dialogCallResponse" persistent max-width="290">
              <v-card>
                <v-alert
                  v-show="showSuccessIcon"
                  dense
                  text
                  type="success"
                >
                  Vendor Created <strong> Success </strong>
                </v-alert>
                <sweetalert-icon icon="success" v-show="showSuccessIcon" />
                <v-alert
                  v-show="showErrorIcon"
                  dense
                  text
                  type="error"
                >
                  Error While Creating Vendor <strong> Failed </strong>
                </v-alert>
                <sweetalert-icon icon="error" v-show="showErrorIcon" />
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" text @click="dialogCallResponse = false">Close</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-row>
          
          <v-dialog
            v-model="dialog"
            hide-overlay
            persistent
            width="300"
          >
            <v-card
              color="green"
              dark
            >
              <v-card-text>
                Sending Request Please Stand By
                <v-progress-linear
                  indeterminate
                  color="white"
                  class="mb-0"
                ></v-progress-linear>
              </v-card-text>
            </v-card>
          </v-dialog>
          <sweetalert-icon icon="loading" v-show="showLoadingIcon" />
          <sweetalert-icon icon="success" v-show="showSuccessIcon" />
          <v-alert
            v-show="showSuccessIcon"
            dense
            text
            type="success"
          >
            Document Upload <strong> Success </strong>
          </v-alert>
          <sweetalert-icon icon="error" v-show="showErrorIcon" />
          <v-alert
            v-show="showErrorIcon"
            dense
            text
            type="error"
          >
            Error While Uploading Document <strong> Failed </strong>
          </v-alert>

        <v-container class="grey lighten-5">
          <v-textarea
            name="input-7-1"
            filled
            v-model="description"
            :error-messages="descriptionErrors"
            :counter="500"
            label="Describe Document"
            required
            @input="$v.description.$touch()"
            @blur="$v.description.$touch()"
            auto-grow
          ></v-textarea>

          <v-row>
            <v-col
              cols="6"
            >
              <v-card
                class="pa-2"
                outlined
                tile
              >
                <v-select
                  v-model="documentType"
                  :items="documentTypeOptions"
                  :error-messages="documentTypeErrors"
                  label="Document Type"
                ></v-select>
              </v-card>
            </v-col>
            <v-col
              cols="6"
            >
              <v-card
                class="pa-2"
                outlined
                tile
              >
                <v-menu
                  ref="expiringDateMenu"
                  v-model="expiringDateMenu"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="expiringDate"
                      :error-messages="expiringDateErrors"
                      label="Document Expiration Date"
                      prepend-icon=""
                      readonly
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="expiringDate"
                    no-title
                    scrollable
                  >
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="expiringDateMenu = false">Cancel</v-btn>
                    <v-btn text color="primary" @click="$refs.expiringDateMenu.save(date)">OK</v-btn>
                  </v-date-picker>
              </v-menu>   
              </v-card>
            </v-col>
          </v-row>

          <v-row>
            <v-col
              cols="6"
            >
              <v-card
                class="pa-2"
                outlined
                tile
              >
                <v-select
                  v-model="documentExpirationInterval"
                  :items="documentExpirationOptions"
                  :error-messages="documentExpirationErrors"
                  label="Document Expiration Interval"
                ></v-select>
              </v-card>
            </v-col>
            <v-col
              cols="6"
            >
              <v-file-input 
              accept="image/*,.pdf"
              placeholder="Pick an image or pdf document"
              hint="Official Document (Image or PDF format only)"
              v-model="document"
              :error-messages="documentUploadErrors"
              :rules="rules"
              required
              label="Official Document" 
              show-size 
              counter 
              chips></v-file-input>
            </v-col>
          </v-row>
          </v-container>
          <div style="clear:both;"></div><br>

          <v-btn color="green" style="color: #ffffff" class="mr-4" @click="submit"><i class="fa fa-folder"></i>&nbsp; Submit</v-btn>
          <v-btn @click="clear">clear</v-btn>
        </form>
      
    <Footer />
  </div>
</template>

<script>
import store from "../store";
import axios from "axios";
import { validationMixin } from "vuelidate";
import { required, maxLength } from "vuelidate/lib/validators";
import NavMain from "../components/Navs/NavMain.vue";
import Footer from "../components/Footers/Footer.vue";

export default {
  name: "UploadDocument",
  components: {
    NavMain,
    Footer
  },
  created: function() {
    let vmObjectInstance = this;

    let config = {
      headers: {
        Authorization: `Bearer ${localStorage.getItem(
          store.state.setTokenLocalStorageKey
        )}`
      }
    };

    axios
      .get(this.endpointGetActiveDocumentTypeUrl, config)
      .then(function(response) {
        if (response.data.status) {
            vmObjectInstance.documentTypeData = response.data.data;

            vmObjectInstance.documentTypeData.map((documentObj) => {
              if(documentObj !== null && documentObj.status === "ACTIVE"){
                  vmObjectInstance.documentTypeOptions.push({ text: `${documentObj.name}`, value: documentObj.id });
              }
            });
        }
      })
      .catch(function(error) {
        console.error(error);
      });

      axios
      .get(this.endpointGetActiveDocumentExpirationUrl, config)
      .then(function(response) {
        if (response.data.status) {
            vmObjectInstance.documentExpirationData = response.data.data;

            vmObjectInstance.documentExpirationData.map((documentExpiration) => {
              if(documentExpiration !== null && documentExpiration.status === "ACTIVE"){
                  vmObjectInstance.documentExpirationOptions.push({ text: `${documentExpiration.name}`, value: documentExpiration.value_in_months	 });
              }
            });
        }
      })
      .catch(function(error) {
        console.error(error);
      });

      let dataToSend = {
        email: localStorage.getItem(store.state.setEmailLocalStorageKey)
      };

      axios
        .post(this.endpointGetUserUrl, dataToSend, config)
        .then(function(response) {
          if (response.data.status) {
              vmObjectInstance.userData = response.data.data;
          }else{
            vmObjectInstance.logout();
          }
        })
        .catch(function(error) {
          console.error(error);
        });
  },
  mixins: [validationMixin],
  validations: {
    description: { required, maxLength: maxLength(50) },
    documentType:  { required },
    expiringDate: { required },
    document: { required },
    documentExpirationInterval: {required}
  },
  data: () => ({
    showLoadingIcon: false,
    showSuccessIcon: false,
    showErrorIcon: false,
    dialog: false,
    dialogCallResponse: false,
    endpointGetActiveDocumentTypeUrl: store.state.urlStore.baseUrl + store.state.urlStore.getActiveDocumentTypeUrl,
    endpointGetActiveDocumentExpirationUrl : store.state.urlStore.baseUrl + store.state.urlStore.getAllActiveDocumentExpirationUrl,
    endpointGetUserUrl:
        store.state.urlStore.baseUrl + store.state.urlStore.getUserByEmailUrl,
    endpointUploadDocumentUrl: store.state.urlStore.baseUrl + store.state.urlStore.uploadDocumentUrl,    
    rules: [
      value =>
        !value ||
        value.size < store.state.allowedUploadLimit ||
        "Document or image size should be less than 2 MB!"
    ],
    documentTypeOptions: [],
    documentExpirationOptions: [],
    description: "",
    document: "",
    documentType: "",
    documentTypeData: [],
    documentExpirationInterval: "",
    documentExpirationData: [],
    expiringDate: "",
    expiringDateMenu: false,
    userData: null,

    breadCrumbsData: [
      {
        text: "View My Documents",
        disabled: false,
        href: "#/ManageUserDocumentsCrud"
      },
      {
        text: "Upload Document",
        disabled: true,
        href: ""
      }
    ],

    objectToSend: {
      userID: 0,
      file: "",
      uploadedBy: 0,
      description: "",
      documentTypeID: 0,
      expiringDate: "",
      expirationInterval: ""
    }
  }),
  computed: {
    descriptionErrors() {
      const errors = [];
      if (!this.$v.description.$dirty) return errors;
      !this.$v.description.maxLength &&
        errors.push("Description must be no more than 500 characters long");
      !this.$v.description.required && errors.push("Description is required.");
      return errors;
    },
    documentTypeErrors() {
      const errors = [];
      !this.$v.documentType.required && errors.push("Document Type is required.");
      return errors;
    },
    expiringDateErrors() {
      const errors = [];
      !this.$v.expiringDate.required && errors.push("Document Expiration Date is required.");
      return errors;
    },
    documentUploadErrors(){
      const errors = [];
      !this.$v.document.required && errors.push("Upload Document.");
      return errors;
    },
    documentExpirationErrors(){
       const errors = [];
      !this.$v.documentExpirationInterval.required && errors.push("Document Expiration Interval is required.");
      return errors;
    }
  },
  methods: {
    submit() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.showLoadingIcon = true;
        this.dialog = true;
        let vmObjectInstance = this;
          
        this.objectToSend.userID = this.userData.id;
        this.objectToSend.file = this.document;
        this.objectToSend.uploadedBy = this.userData.email;
        this.objectToSend.description = this.description;
        this.objectToSend.documentTypeID = this.documentType;
        this.objectToSend.expiringDate = this.expiringDate;
        this.objectToSend.expirationInterval = this.documentExpirationInterval;

        let formData = new FormData();
        formData.append("file", this.document);
        formData.append("userID", this.userData.id);
        formData.append("uploadedBy", this.userData.email);
        formData.append("description", this.description);
        formData.append("documentTypeID", this.documentType);
        formData.append("expiringDate", this.expiringDate);
        formData.append("expirationInterval",this.documentExpirationInterval);
        
        let config = {
          headers: {
            Authorization: `Bearer ${localStorage.getItem(
              store.state.setTokenLocalStorageKey
            )}`
          }
        };

        axios
        .post(this.endpointUploadDocumentUrl, formData, config)
        .then(function(response) {
          if (response.data.status) {
            vmObjectInstance.showLoadingIcon = false;
            vmObjectInstance.dialog = false;
            vmObjectInstance.sendingRequest = false;
            vmObjectInstance.showErrorIcon = false;
            vmObjectInstance.successMessage = "Document Uploaded";
            vmObjectInstance.showSuccessAlert = true;
            vmObjectInstance.showSuccessIcon = true;

          } else {
            vmObjectInstance.showLoadingIcon = false;
            vmObjectInstance.showErrorIcon = true;
            vmObjectInstance.sendingRequest = false;
            vmObjectInstance.dialog = false;
          }
        })
        .catch(function(error) {
          console.error(error);
          vmObjectInstance.showLoadingIcon = false;
          vmObjectInstance.showSuccessIcon = false;
          vmObjectInstance.sendingRequest = false;
          vmObjectInstance.showErrorIcon = true;
          vmObjectInstance.dialog = false;
        });

        // let headers = this.objectToSend;
        // this.uploadDocument(vmObjectInstance, headers);
      }
    },
    clear() {
      this.$v.$reset();
      this.$router.go(); // reloads the page
    },
  }
};
</script>

<style scoped>

</style>
