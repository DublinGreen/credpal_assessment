<template>
  <section style="width: 100%">
    <NavMain />
      <v-content style="clear: both;padding: 2%;margin:0;width: 100%">
            <section class="breadCrumbsBox">
              <v-breadcrumbs :items="breadCrumbsData" large></v-breadcrumbs>
            </section>
            <section class="clearfix"></section>
            <br /><br />

            <v-card-title>
              <!-- Manage Documents -->
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
            </v-card-title>

            <v-alert type="success" v-show="showSuccessAlert">
              Record updated!
            </v-alert>

            <sweetalert-icon icon="loading" v-show="showLoadingIcon" />

            <v-alert type="error" v-show="showErrorAlert">
              Unable to update record.
            </v-alert>

            <v-data-table
              :headers="headers"
              :items="recordsToDisplay"
              sort-by="name"
              class="elevation-1"
              :search="search"
              :loading="loadingPage"
              loading-text="Loading... Please wait"
            >
              <template v-slot:top>
                <v-toolbar flat color="white">
                  <v-toolbar-title>

                    <div class="d-flex grow flex-wrap">
                      <div class="text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 success pa-7" 
                      style="max-height: 90px; width: auto;">
                      <i aria-hidden="true" class="v-icon notranslate mdi mdi-folder theme--dark" 
                      style="font-size: 32px;"></i><!----></div><!----><div class="ml-4">
                        <div class="card-title font-weight-light" style="margin-top:50px;">{{userData.name}} Documents</div>
                      </div>
                    </div>
                    <br>
                    <v-spacer></v-spacer>
                  </v-toolbar-title>
                  <v-divider class="mx-4" inset vertical></v-divider>
                  <v-spacer></v-spacer>
                  <v-spacer></v-spacer>
                  <v-dialog v-model="dialog" max-width="500px">
                    <v-card>
                      <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                      </v-card-title>

                      <v-card-text>
                        <v-container>
                          <v-row>
                            <v-col cols="12" sm="12" md="12">
                              <v-select
                                v-model="editedItem.status"
                                :items="statusOptions"
                                label="Document Status"
                              ></v-select>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-textarea
                              name="input-7-1"
                              filled
                              v-model="editedItem.description"
                              :counter="500"
                              label="Document Description"
                              required
                              auto-grow
                            ></v-textarea>
                            </v-col>
                          </v-row>
                        </v-container>
                      </v-card-text>

                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                        <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-toolbar>
              </template>
              <template v-slot:item.actions="{ item }">
      
                <v-row justify="center">
                                    
                  <v-icon small class="mr-2" @click="forceFileDownload(item)">
                  mdi-eye
                </v-icon>

                <button type="button" @click="editItem(item)" 
                class="actionButton px-1 ml-1 v-btn v-btn--contained v-btn--fab v-btn--round theme--light v-size--x-small warning">
                <span class="v-btn__content">
                  <i aria-hidden="true" class="v-icon notranslate mdi mdi-pencil theme--light"></i>
                  </span>
                </button>

                  <v-dialog v-model="shareDocumentDialog" persistent max-width="490">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        color="success"
                        dark
                        v-bind="attrs"
                        v-on="on"
                      >
                      <v-icon small class="mr-1">
                        mdi-share
                      </v-icon> Share Document
                      </v-btn>
                      
                    </template>
                    <v-card>
                      <v-card-title class="headline">Add valid emails separated by commas</v-card-title>
                      <v-card-text>A secure link will be sent to email for download.</v-card-text>

                      <v-container class="grey lighten-5">
                        <v-row>
                          <v-col
                            cols="12"
                          >
                            <v-card
                              class="pa-2"
                              outlined
                              tile
                            >
                              <v-text-field
                                v-model="emailList"
                                title="Emails separated by commas"
                                label="Email"
                                required
                              ></v-text-field>
                            </v-card>
                          </v-col>
                        </v-row>
                      </v-container>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="warning darken-1" text @click="shareDocumentDialog = false">Cancel</v-btn>
                        <v-btn color="green darken-1" text @click="sendDocument = true; currentItem = item">
                          <v-icon small class="mr-1">
                            mdi-share
                          </v-icon>Send Link
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-row>
              </template>
              <template v-slot:no-data> </template>
            </v-data-table>
      </v-content>
    <Footer />
  </section>
</template>

<script>
import axios from "axios";
import { manageDocumentsCrudMixin } from "../mixins/manageDocumentsCrudMixin.js";
import store from "../store";
import NavMain from "../components/Navs/NavMain.vue";
import Footer from "../components/Footers/Footer.vue";

export default {
  name: "ManageUserDocumentsCrud",
  mixins: [manageDocumentsCrudMixin],
  components: {
    NavMain,
    Footer
  },
  created: function() {
    let tempToken = localStorage.getItem(store.state.setTokenLocalStorageKey);
    if (tempToken === "") {
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
      email: localStorage.getItem(store.state.setEmailLocalStorageKey)
    };

    axios
      .post(this.endpointGetUserUrl, dataToSend, config)
      .then(function(response) {
        if (response.data.status) {
            vmObjectInstance.userData = response.data.data;

            axios
            .get(vmObjectInstance.endpoint + vmObjectInstance.userData.id, config)
            .then(function(response) {
              if (response.data.status) {
                vmObjectInstance.loadingPage = false;
                vmObjectInstance.dataRecords = response.data.data;
                vmObjectInstance.recordsToDisplay = vmObjectInstance.dataRecords;
              }
            })
            .catch(function(error) {
              console.error(error);
            });
        
        }
      })
      .catch(function(error) {
        console.error(error);
      });

  },
  watch:{
    sendDocument(value){
      this.shareDocumentDialog = false;
      if(value){
        this.sendDocumentlink(this.currentItem);
      }
    }
  }, 
  data: function() {
    return {
      currentItem: null,
      sendDocument: false,
      loadingPage: true,
      emailList: "",
      shareDocumentDialog: false,
      isLogin: store.state.isLogin,
      endpoint: store.state.urlStore.baseUrl + store.state.urlStore.getUserDocumentsUrl,
      endpointGetUserUrl:
        store.state.urlStore.baseUrl + store.state.urlStore.getUserByEmailUrl,  
      endpointUpdate: store.state.urlStore.baseUrl + store.state.urlStore.updateUserDocumentUrl,
      userData: {"name": ""},
      statusOptions: [
            { text: store.state.documentStatusOptions[0], value: store.state.documentStatusOptions[0] },
            { text: store.state.documentStatusOptions[1], value: store.state.documentStatusOptions[1] },
      ],
      breadCrumbsData: [
        {
          text: "New Document",
          disabled: false,
          href: "#/UploadDocument"
        },
        {
          text: "Manage Documents",
          disabled: true,
          href: "#/"
        }
      ]
    };
  },
  methods: {
    sendDocumentlink(item ) {
        let vmObjectInstance = this;

        let dataToSend = {
            email: localStorage.getItem(store.state.setEmailLocalStorageKey)
        };

        let config = {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(
              store.state.setTokenLocalStorageKey
            )}`
            }
        };

        axios
            .post(store.state.urlStore.baseUrl + store.state.urlStore.getUserByEmailUrl, dataToSend, config)
            .then(function(response) {
                if (response.data.status) {
                    vmObjectInstance.userData = response.data.data;
                }
            })
            .catch(function(error) {
                console.error(error);
            });

        let link = `https://mail.corporate-setup.com/index.php/Sendingemail_Controller/send_document_link/` +
            `${this.userData.name}/${this.userData.email}/${item.name}`;
        alert(link);

        axios.get(link)
        .then(function(response) {
          console.log(response);
          alert("SENT");
          // if (response.data.status) {
          //     vmObjectInstance.documentExpirationData = response.data.data;

          //     vmObjectInstance.documentExpirationData.map((documentExpiration) => {
          //       if(documentExpiration !== null && documentExpiration.status === "ACTIVE"){
          //           vmObjectInstance.documentExpirationOptions.push({ text: `${documentExpiration.name}`, value: documentExpiration.value_in_months	 });
          //       }
          //     });
          // }
        })
        .catch(function(error) {
          console.error(error);
        });

    }
  }
};
</script>
