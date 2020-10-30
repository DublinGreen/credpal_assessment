<template>
  <div class="create-vendor container">
    <NavMain />
    <section class="breadCrumbsBox">
      <v-breadcrumbs :items="breadCrumbsData" large></v-breadcrumbs>
    </section>
    <div style="clear:both;"></div>
    <br />
    <br />

    <section style="width: 100%">
      <section
        style="float: left;width: 30%;background-color: #333333;color: #ffffff;border-radius: 15px; box-shadow: 3px 3px 3px #000000"
      >
        <h1 style="font-size: 3em;color: #ffffff">
          Balance
          <br />
          <span style="color: #00ff00;text-shadow: 1px 1px 1px #000000;">&#8358;{{balance}}</span>
        </h1>
      </section>

      <section v-if="validAccount" style="float: left; width: 55%;padding-left: 15%">
        <h3 style="font-weight: bolder;text-align:center;">Sending Funds To...</h3>
        <br />
        <div style="width: 30%;float:left">
          <img
            style="border-radius: 50%"
            :src="require('../assets/male.jpeg')"
            :alt="receiverUserData.first_name"
            :title="receiverUserData.first_name"
            width="150px"
          />
        </div>
        <div style="width: 70%;float:left;display: block;">
          <p v-if="isReceivingUserActive" style="color:#00ff00;font-weight: bold">
            <i class="fa fa-power-on" style="margin-right: 10px;"></i>
            ACCOUNT : {{receiverUserData.status}}
          </p>
          <p v-else style="color:#ff0000;font-weight: bold">
            <i class="fa fa-power-on" style="margin-right: 10px;"></i>
            ACCOUNT : {{receiverUserData.status}}
          </p>
          <p>
            <i class="fas fa-user" style="margin-right: 10px;"></i>
            {{receiverUserData.first_name}} {{receiverUserData.middle_name}} {{receiverUserData.last_name}}
          </p>
          <p>
            <i class="fa fa-envelope" style="margin-right: 10px;"></i>
            {{receiverUserData.email}}
          </p>
          <p>
            <i class="fa fa-phone" style="margin-right: 10px;"></i>
            {{receiverUserData.mobile}}
          </p>
        </div>
        <div style="clear:both;"></div>
      </section>
    </section>

    <section class="clearfix"></section>
    <hr />

    <h1>Wallet Transfer</h1>
    <br />
    <div class="clearfix"></div>

    <form>
      <v-row justify="center">
        <v-dialog v-model="dialogCallResponse" persistent max-width="290">
          <v-card>
            <v-alert v-show="showSuccessIcon" dense text type="success">
              Vendor Created
              <strong>Success</strong>
            </v-alert>
            <sweetalert-icon icon="success" v-show="showSuccessIcon" />
            <v-alert v-show="showErrorIcon" dense text type="error">
              Error While Creating Vendor
              <strong>Failed</strong>
            </v-alert>
            <sweetalert-icon icon="error" v-show="showErrorIcon" />
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green darken-1" text @click="dialogCallResponse = false">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>

      <v-dialog v-model="dialog" hide-overlay persistent width="300">
        <v-card color="green" dark>
          <v-card-text>
            Sending Request Please Stand By
            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
          </v-card-text>
        </v-card>
      </v-dialog>
      <sweetalert-icon icon="loading" v-show="showLoadingIcon" />
      <sweetalert-icon icon="success" v-show="showSuccessIcon" />
      <v-alert v-show="showSuccessIcon" dense text type="success">{{successMessage}}</v-alert>
      <sweetalert-icon icon="error" v-show="showErrorIcon" />
      <v-alert v-show="showErrorIcon" dense text type="error">{{errorMessage}}</v-alert>

      <v-container class="grey lighten-5">
        <v-row>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                v-model="accountNumber"
                :error-messages="accountNumberErrors"
                :counter="11"
                label="Receiver Account Number"
                required
                type="number"
                :readonly="this.validAccount"
                @input="$v.accountNumber.$touch()"
                @blur="$v.accountNumber.$touch()"
              ></v-text-field>
            </v-card>
            <v-btn v-if="accountNotValidated" @click="validateAccount">Validate Account</v-btn>
          </v-col>
          <v-col cols="6">
            <v-card class="pa-2" outlined tile>
              <v-select
                v-model="sendLaterOption"
                :items="scheduleTransferTypeOptions"
                label="Schedule Transfer"
              ></v-select>
              <v-menu
                v-if="showTransferScheduleDatePicker"
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
                    label="Pick date to send funds"
                    prepend-icon
                    readonly
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="expiringDate" scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="expiringDateMenu = false">Cancel</v-btn>
                  <v-btn text color="primary" @click="$refs.expiringDateMenu.save(date)">OK</v-btn>
                </v-date-picker>
              </v-menu>
            </v-card>
          </v-col>
        </v-row>

        <v-row v-if="validAccount">
          <v-col cols="6">
            <v-textarea
              name="input-7-1"
              filled
              v-model="description"
              :counter="500"
              label="Description"
              auto-grow
            ></v-textarea>
          </v-col>
          <v-col cols="6" v-if="validAccount">
            <v-card class="pa-2" outlined tile>
              <v-text-field
                label="Amount"
                v-model="amount"
                :error-messages="amountErrors"
                :counter="11"
                required
                type="number"
                @input="$v.amount.$touch()"
                @blur="$v.amount.$touch()"
              ></v-text-field>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
      <div style="clear:both;"></div>
      <br />

      <v-btn color="green" v-if="validAccount" style="color: #ffffff" class="mr-4" @click="submit">
        <i class="fa fa-folder"></i>&nbsp; Transfer Funds
      </v-btn>
      <v-btn v-if="validAccount" @click="clear">clear</v-btn>
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
  name: "Wallet",
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

    vmObjectInstance.getWalletBalanceDataCall(
      vmObjectInstance,
      {
        userID: store.state.userID
      },
      config
    );
  },
  mixins: [validationMixin],
  validations: {
    accountNumber: { required, maxLength: maxLength(11) },
    amount: { required, maxLength: maxLength(5) }
  },
  data: () => ({
    showLoadingIcon: false,
    showSuccessIcon: false,
    showErrorIcon: false,
    dialog: false,
    validAccount: false,
    accountNotValidated: true,
    amountToSendValid: false,
    dialogCallResponse: false,
    balance: "",
    balanceNumber: 0.0,
    errorMessage: "",
    successMessage: "",
    isReceivingUserActive: false,
    showTransferScheduleDatePicker: false,
    sendLaterOption: null,
    appGetWallertBalanceEndpoint:
      store.state.urlStore.baseUrl + store.state.urlStore.getWalletBalanceUrl,
    endpointGetAccountByAccountNumberUrl:
      store.state.urlStore.baseUrl +
      store.state.urlStore.getAccountByAccountNumberUrl,
    endpointGetUserByIDUrl:
      store.state.urlStore.baseUrl + store.state.urlStore.getUserByIDUrl,
    endpointTransferFundsUrl:
      store.state.urlStore.baseUrl + store.state.urlStore.transferFundsUrl,
    rules: [
      value =>
        !value ||
        value.size < store.state.allowedUploadLimit ||
        "Document or image size should be less than 2 MB!"
    ],
    accountNumber: "",
    amount: 0,
    receiverUserData: null,
    receiverUserAccountData: null,
    scheduleTransferTypeOptions: [
      {
        text: "NO",
        value: "no"
      },
      {
        text: "Yes, Schedule transfer",
        value: "yes"
      }
    ],
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
        text: "Wallet",
        disabled: true,
        href: ""
      }
    ],

    objectToSend: {
      from_userID: 0,
      to_userID: "",
      amount: 0,
      description: "",
      schedule_transfer: 0,
      schedule_date: ""
    }
  }),
  watch: {
    sendLaterOption: function(val) {
      if (val == "yes") {
        this.showTransferScheduleDatePicker = true;
      } else {
        this.showTransferScheduleDatePicker = false;
      }
    },
    balance: function(val) {
      this.balanceNumber = val.replace(",", "");
    },
    amount: function(val) {
      if (val > this.balanceNumber) {
        this.amountToSendValid = false;
      } else if (val > store.state.transferLimit) {
        this.amountToSendValid = false;
      } else {
        this.amountToSendValid = true;
      }
    }
  },
  computed: {
    accountNumberErrors() {
      const errors = [];
      if (!this.$v.accountNumber.$dirty) return errors;
      !this.$v.accountNumber.maxLength &&
        errors.push("Account Number must be no more than 11 characters long");
      !this.$v.accountNumber.required &&
        errors.push("Account Number is required.");
      return errors;
    },
    amountErrors() {
      const errors = [];
      if (!this.$v.amount.$dirty) return errors;
      !this.$v.amount.maxLength &&
        errors.push("Amount must be no more than 50,000 characters long");
      !this.$v.amount.required && errors.push("Amount is required.");
      return errors;
    }
  },
  methods: {
    getWalletBalanceDataCall(vmObjectInstance, headers, config) {
      axios
        .post(vmObjectInstance.appGetWallertBalanceEndpoint, headers, config)
        .then(function(response) {
          console.log(response);
          if (response.status === 200) {
            if (response.data.status) {
              store.commit("setWalletBalance", response.data.data.balance);
              vmObjectInstance.balance = response.data.data.balance;
            }
          }
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    getUserByID() {
      let headers = {
        headers: {
          Authorization: `Bearer ${localStorage.getItem(
            store.state.setTokenLocalStorageKey
          )}`
        }
      };

      let vmObjectInstance = this;
      this.endpointGetUserByIDUrl =
        this.endpointGetUserByIDUrl + this.receiverUserAccountData.user_id;

      axios
        .get(this.endpointGetUserByIDUrl, headers)
        .then(function(response) {
          if (response.data.status) {
            vmObjectInstance.receiverUserData = response.data.data;
            if (response.data.data.status) {
              vmObjectInstance.isReceivingUserActive = true;
            } else {
              vmObjectInstance.isReceivingUserActive = false;
            }

            vmObjectInstance.validAccount = true;
            vmObjectInstance.accountNotValidated = false;
          }
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    validateAccount() {
      let headers = {
        headers: {
          Authorization: `Bearer ${localStorage.getItem(
            store.state.setTokenLocalStorageKey
          )}`
        }
      };
      let vmObjectInstance = this;
      this.endpointGetAccountByAccountNumberUrl =
        this.endpointGetAccountByAccountNumberUrl + this.accountNumber;
      axios
        .get(this.endpointGetAccountByAccountNumberUrl, headers)
        .then(function(response) {
          if (response.data.status) {
            vmObjectInstance.receiverUserAccountData = response.data.data;

            vmObjectInstance.getUserByID();
          }
        })
        .catch(function(error) {
          console.error(error);
        });
    },
    submit() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        let answer = confirm(
          "Are you sure you want to transfer funds " + this.receiverUserData.first_name + " " + this.receiverUserData.last_name
        );
        if (answer) {
          this.showLoadingIcon = true;
          this.dialog = true;

          this.objectToSend.from_userID = store.state.userID;
          this.objectToSend.to_userID = this.receiverUserData.id;
          this.objectToSend.amount = this.amount;
          this.objectToSend.description = this.description;
          this.objectToSend.schedule_transfer = this.sendLaterOption;
          this.objectToSend.schedule_date = this.expiringDate;

          let config = {
            headers: {
              Authorization: `Bearer ${localStorage.getItem(
                store.state.setTokenLocalStorageKey
              )}`
            }
          };

          let vmObjectInstance = this;

          axios
            .post(
              vmObjectInstance.endpointTransferFundsUrl,
              vmObjectInstance.objectToSend,
              config
            )
            .then(function(response) {
              console.log(response);
              if (response.data.status) {
                vmObjectInstance.showLoadingIcon = false;
                vmObjectInstance.dialog = false;
                vmObjectInstance.sendingRequest = false;
                vmObjectInstance.showErrorIcon = false;
                vmObjectInstance.successMessage = response.data.message;
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

          setTimeout(() => {
            vmObjectInstance.clear();
            vmObjectInstance.$v.$reset();
            vmObjectInstance.$router.go(); // reloads the page
          }, store.state.alertTimeout);
        }
      }
    },
    clear() {
      this.$v.$reset();
      this.$router.go(); // reloads the page
    }
  }
};
</script>

<style scoped>
</style>
