import Vue from "vue";
import Vuex from "vuex";
//import axios from "axios";

//Load Vuex
Vue.use(Vuex);

// LeftNav store
const moduleLeftNavDrawer = {
  state: {
    count: 1500,
    leftDrawer: true
  },
  getter: {
    leftDrawer: state => {
      return state.leftDrawer;
    },
    count: state => {
      return state.count;
    }
  },
  setter: {
    leftDrawer: state => {
      !state.leftDrawer;
    }
  },
  mutations: {
    toogleLeftDrawer(state) {
      let tempState = state.leftDrawer;
      state.leftDrawer = !tempState;
    }
  }
};

// Commons store
const moduleCommons = {
  state: {
    appName: "CredPal Technical Assessment",
    currentYear: new Date(),
    copyright: ""
  },
  getter: {
    currentYear: state => {
      return state.currentYear.getFullYear();
    },
    appName: state => {
      return state.appName;
    },
    copyright: state => {
      return (state.copyright =
        state.appName + " &copy; " + state.currentYear.getFullYear());
    }
  },
  setter: {},
  mutations: {}
};

// Url store
const urlCommons = {
  state: {
    // baseUrl: "http://134.209.18.95:8000/apiv1/",
    // baseUrlAuth: "http://134.209.18.95:8000/auth/",
    // siteUrl: "http://apptest.corporate-setup.com/",
    // serverUrl: "http://134.209.18.95:8000/",
    baseUrl: "http://localhost:9000/apiv1/",
    baseUrlAuth: "http://localhost:9000/auth/",
    siteUrl: "http://localhost:8080/",
    serverUrl: "http://localhost:9000/",
    adLoginUrl: "login",
    signupUrl: "createUser",
    getUserByEmailUrl: "getUserByMobile/",
    getUserByMobileUrl: "getUserByMobile/",
    updateUserUrl: "updateUser/",
    getIDByReferralCodesUrl: "getIDByReferralCodes/",
    getWalletBalanceUrl: "getWalletBalance/",
    getAccountByAccountNumberUrl: "getAccountByAccountNumber/",
    getAccountNumberByUserIDUrl: "getAccountNumberByUserID/",
    getUserByIDUrl: "userByID/",
    //
    getUserByNameUrl: "getUserByName/",
    confirmEmailUrl: "confirmEmail/",
    getActiveDocumentTypeUrl: "getActiveDocumentType",
    uploadDocumentUrl: "uploadDocument",
    getUserDocumentsUrl: "getUserDocuments/",
    updateUserDocumentUrl: "updateUserDocument",
    getAllActiveDocumentExpirationUrl: "getAllActiveDocumentExpiration",
    updateCompanyLogo: "updateCompanyLogo/"
  },
  getter: {
    baseUrl: state => {
      return state.baseUrl;
    }
  },
  setter: {},
  mutations: {}
};

// Main store
export default new Vuex.Store({
  state: {
    token: null,
    createdBy: "dublin.green",
    redirectTimeout: 2000,
    alertTimeout: 7000,
    alertLongTimeout: 12000,
    allowedUploadLimit: 2000000,
    companyLogoUploadLimit: 5000000,
    documentPopupWindowWidth: 800,
    documentPopupWindowHeight: 500,
    setTokenLocalStorageKey: "token",
    setIsLoginLocalStorageKey: "isLogin",
    setEmailLocalStorageKey: "email",
    setMobileLocalStorageKey: "mobile",
    transferLimit: 50000,
    userFirstName: "",
    userLastName: "",
    userID: "",
    accountNumber: "",
    balance: "",
    appName: "CredPal",
    documentStatusOptions: ["ACTIVE", "NOT ACTIVE"]
  },
  getter: {
    createdBy: state => {
      return state.createdBy;
    },
    alertTimeout: state => {
      return state.alertTimeout;
    }
  },
  setter: {},
  mutations: {
    setToken(state, value) {
      console.log("setToken mutation call");
      state.token = value;
    },
    setUserFirstName(state, value) {
      state.userFirstName = value;
    },
    setUserLastName(state, value) {
      state.userLastName = value;
    },
    setUserID(state, value) {
      state.userID = value;
    },
    setAccountNumber(state, value) {
      state.accountNumber = value;
    },
    setWalletBalance(state, value) {
      state.balance = value;
    },
    setIsLogin(state, value) {
      console.log("setIsLogin mutation call");
      state.isLogin = value;
    }
  },
  actions: {},
  modules: {
    leftNavDrawerStore: moduleLeftNavDrawer,
    commonStore: moduleCommons,
    urlStore: urlCommons
  }
});
