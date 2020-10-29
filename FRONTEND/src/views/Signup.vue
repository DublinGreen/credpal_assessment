<template>
  <v-row justify="center">
    <section class="dt-login--container" style="margin-top: 1%">
      <section class="dt-login__content-wrapper">
        <section class="dt-login__bg-section">
          <section class="dt-login__bg-content">
            <a
              title="Login"
              href="#/Login"
              style="float: left;margin-left: -40px;margin-top: -40px;color: #fff"
            >
              <i title="Login" class="fa fa-arrow-left"></i>
            </a>
            <h1 class="dt-login__title" style="margin-left: -15%;font-weight: 900">{{appName}}</h1>
            <p
              class="f-16"
              style="font-weight: 700;color: #ffffff;"
            >Signup for a {{appName}} Account.</p>
          </section>
        </section>

        <section class="dt-login__content">
          <section class="dt-login__content-inner">
            <form>
              <v-row justify="center">
                <v-dialog v-model="dialogCallResponse" persistent max-width="290">
                  <v-card>
                    <v-alert v-show="showErrorIcon" dense text type="warning">
                      Error(s) with signup :
                      <strong>{{serverReturnedErrors}}</strong>
                    </v-alert>
                    <sweetalert-icon icon="warning" v-show="showErrorIcon" />
                  </v-card>

                  <v-card>
                    <v-alert v-show="showSuccessIcon" dense text type="success">
                      A mobile verfication code has been sent to
                      <strong>({{phone}})</strong>. Do login and verify mobile number.
                    </v-alert>
                    <sweetalert-icon icon="success" v-show="showSuccessIcon" />
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

              <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                :counter="100"
                label="First Name"
                required
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
              ></v-text-field>

              <v-text-field
                v-model="lastName"
                :error-messages="lastNameErrors"
                :counter="100"
                label="Last Name"
                required
                @input="$v.lastName.$touch()"
                @blur="$v.lastName.$touch()"
              ></v-text-field>

              <v-text-field
                v-model="email"
                :error-messages="emailErrors"
                :counter="100"
                label="Email"
                required
                @input="$v.email.$touch()"
                @blur="$v.email.$touch()"
              ></v-text-field>

              <v-text-field
                v-model="phone"
                :error-messages="phoneErrors"
                :counter="50"
                label="Mobile (e.g 07032090809)"
                required
                @input="$v.phone.$touch()"
                @blur="$v.phone.$touch()"
              ></v-text-field>

              <v-text-field
                :error-messages="passwordErrors"
                v-model="password"
                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showPassword ? 'text' : 'password'"
                label="Password"
                hint="your password"
                :counter="50"
                @input="$v.password.$touch()"
                @blur="$v.password.$touch()"
                @click:append="showPassword = !showPassword"
              ></v-text-field>

              <v-text-field
                :error-messages="confirmPasswordErrors"
                v-model="confirmPassword"
                :append-icon="confirmShowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :type="confirmShowPassword ? 'text' : 'password'"
                label="Confirm Password"
                hint="confirm your password"
                :counter="50"
                @input="$v.confirmPassword.$touch()"
                @blur="$v.confirmPassword.$touch()"
                @click:append="confirmShowPassword = !confirmShowPassword"
              ></v-text-field>

              <v-checkbox
                v-model="termsAndConditions"
                :error-messages="termsAndConditionsErrors"
                label="Do you agree to our terms and conditions?"
                required
                @change="$v.termsAndConditions.$touch()"
                @blur="$v.termsAndConditions.$touch()"
              ></v-checkbox>

              <div v-if="referralCodeValid" class="alert alert-success">
                <p style="color: #ffffff">
                  <i class="fa fa-thumbs-up"></i>
                  Valid referral Code :
                  <strong>{{referralUserObj.referral_codes}}</strong>
                </p>
                <hr />
                <p style="color: #ffffff">
                  <i class="fa fa-user"></i>
                  You signing up under
                  <br />
                  <strong>{{referralUserObj.first_name}} {{referralUserObj.last_name}}</strong>
                </p>
              </div>

              <v-btn class="mr-4" @click="submit">Signup</v-btn>
              <v-btn @click="clear">clear</v-btn>
            </form>
          </section>
        </section>
      </section>
    </section>
  </v-row>
</template>

<script>
import store from "../store";
import axios from "axios";
import { validationMixin } from "vuelidate";
import {
  required,
  maxLength,
  minLength,
  email
} from "vuelidate/lib/validators";

export default {
  name: "Signup",
  created: function() {
    let tempToken = localStorage.getItem(store.state.setTokenLocalStorageKey);
    if (tempToken !== "") {
      // GOT TOKEN, SO USER HAS LOGIN BEFORE NOW
      // this.$router.push("ManageUsersCrud");
    } else {
      // DON'T HAVE TOKEN
      localStorage.setItem(store.state.setIsLoginLocalStorageKey, false);
      localStorage.setItem(store.state.setTokenLocalStorageKey, "");
    }

    let param = this.$route.params.referral_codes;
    let vmObjectInstance = this;
    if (param != "") {
      this.referral_codes = param;

      this.objectToSendReferralCode.referral_codes = this.referral_codes;
      let headers = this.objectToSendReferralCode;
      this.checkReferralCodeCall(vmObjectInstance, headers);
    }
  },
  mixins: [validationMixin],
  validations: {
    name: { required, maxLength: maxLength(100) },
    lastName: { required, maxLength: maxLength(100) },
    email: { required, maxLength: maxLength(100), email },
    phone: { required, maxLength: maxLength(11) },
    password: { required, maxLength: maxLength(50), minLength: minLength(7) },
    confirmPassword: {
      required,
      maxLength: maxLength(50),
      minLength: minLength(7)
    },
    termsAndConditions: { required }
  },
  data: () => ({
    appName: store.state.appName,
    showPassword: false,
    confirmShowPassword: false,
    showErrorIcon: false,
    showSuccessIcon: false,
    dialog: false,
    dialogCallResponse: false,
    endpoint: store.state.urlStore.baseUrlAuth + store.state.urlStore.signupUrl,
    endpointCheckRefCode:
      store.state.urlStore.baseUrlAuth +
      store.state.urlStore.getIDByReferralCodesUrl,
    referral_codes: "",
    referralUserObj: null,
    referralCodeValid: false,
    name: "",
    lastName: "",
    email: "",
    password: "",
    phone: "",
    confirmPassword: "",
    referrerID: 0,
    termsAndConditions: null,
    serverReturnedErrors: null,
    objectToSend: {
      first_name: null,
      last_name: null,
      email: null,
      password: null,
      referrer_id: null,
      mobile: null
    },
    objectToSendReferralCode: {
      referral_codes: null
    }
  }),
  computed: {
    nameErrors() {
      const errors = [];
      if (!this.$v.name.$dirty) return errors;
      !this.$v.name.maxLength &&
        errors.push("First name must be no more than 100 characters long");
      !this.$v.name.required && errors.push("First name is required");
      return errors;
    },
    lastNameErrors() {
      const errors = [];
      if (!this.$v.lastName.$dirty) return errors;
      !this.$v.lastName.maxLength &&
        errors.push("Last name must be no more than 100 characters long");
      !this.$v.lastName.required && errors.push("Last name is required");
      return errors;
    },
    phoneErrors() {
      const errors = [];
      if (!this.$v.phone.$dirty) return errors;
      !this.$v.phone.maxLength &&
        errors.push("Mobile number must be no more than 50 characters long");
      !this.$v.phone.required && errors.push("Mobile number is required");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.maxLength &&
        errors.push("Email must be no more than 100 characters long");
      !this.$v.email.email && errors.push("Must be valid e-mail");
      !this.$v.email.required && errors.push("Email is required");
      return errors;
    },
    passwordErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.maxLength &&
        errors.push("Password must be no more than 50 characters long");
      !this.$v.password.minLength &&
        errors.push("Password must be more than 7 characters long");
      !this.$v.password.required && errors.push("Password is required.");
      return errors;
    },
    confirmPasswordErrors() {
      const errors = [];
      if (!this.$v.confirmPassword.$dirty) return errors;
      if (this.confirmPassword !== this.password) {
        errors.push("Password and confirm password must be a match");
      }
      !this.$v.confirmPassword.required &&
        errors.push("Confirm Password is required.");
      return errors;
    },
    termsAndConditionsErrors() {
      const errors = [];
      if (!this.$v.termsAndConditions.$dirty) return errors;
      !this.$v.termsAndConditions.required &&
        errors.push("Must accept terms and conditions");
      return errors;
    }
  },
  methods: {
    submit() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.dialog = true;
        let vmObjectInstance = this;
        this.objectToSend.first_name = this.name;
        this.objectToSend.last_name = this.lastName;
        this.objectToSend.password = this.password;
        this.objectToSend.email = this.email;
        this.objectToSend.mobile = this.phone;
        this.objectToSend.referrer_id = this.referrerID;

        let headers = this.objectToSend;

        this.dialogCallResponse = true;
        this.showErrorIcon = false;
        this.showSuccessIcon = false;
        this.signupUrlCall(vmObjectInstance, headers);
      }
    },
    clear() {
      this.$v.$reset();
      this.name = "";
      this.lastName = "";
      this.phone = "";
      this.email = "";
      this.password = "";
      this.confirmPassword = "";
      // this.$router.go(); // reloads the page
    },
    signupUrlCall(vmObjectInstance, headers) {
      axios
        .post(this.endpoint, headers)
        .then(function(response) {
          vmObjectInstance.dialog = false;

          if (response.data.status) {
            vmObjectInstance.dialogCallResponse = true;
            vmObjectInstance.showSuccessIcon = true;
            vmObjectInstance.showErrorIcon = false;

            // setTimeout(() => {
            //   this.$v.$reset();
            // }, store.state.alertLongTimeout);
          } else {
            vmObjectInstance.dialogCallResponse = true;
            vmObjectInstance.showErrorIcon = true;
            vmObjectInstance.showSuccessIcon = false;

            vmObjectInstance.serverReturnedErrors = `${response.data.errors.mobile}`;
          }
        })
        .catch(function(error) {
          console.error(error);
          vmObjectInstance.dialog = false;
          vmObjectInstance.dialogCallResponse = true;
        });
    },
    checkReferralCodeCall(vmObjectInstance, headers) {
      axios
        .post(this.endpointCheckRefCode, headers)
        .then(function(response) {
          vmObjectInstance.dialog = false;

          if (response.data.status) {
            vmObjectInstance.referralUserObj = response.data.data;
            vmObjectInstance.referralCodeValid = true;
            vmObjectInstance.referrerID = vmObjectInstance.referralUserObj.id;
          } else {
            vmObjectInstance.referralUserObj = null;
            vmObjectInstance.referralCodeValid = false;
          }
        })
        .catch(function(error) {
          console.error(error);
          vmObjectInstance.referralCodeValid = false;
        });
    }
  }
};
</script>


<style scoped>
.dt-login--container {
  margin-top: -5%;
}

.dt-login__bg-section {
  background-image: url(../../public/assets/images/login-background.jpg) !important;
  background-color: #28a745 !important;
}

.dt-login__bg-section:before {
  background-image: url(../../public/assets/images/login-background.jpg) !important;
  background-color: #28a745 !important;
}
</style>

