import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "RootLogin",
    component: () => import("../views/Login.vue")
  },
  {
    path: "/Login",
    name: "Login",
    component: () => import("../views/Login.vue")
  },
  {
    path: "/Profile",
    name: "Profile",
    component: () => import("../views/Profile.vue")
  },
  {
    path: "/EditProfile/:name",
    name: "EditProfile",
    component: () => import("../views/EditProfile.vue")
  },
  {
    path: "/Dashboard",
    name: "Dashboard",
    component: () => import("../views/Dashboard.vue")
  },
  {
    path: "/Signup/:referral_codes?",
    name: "Signup",
    component: () => import("../views/Signup.vue")
  },
  {
    path: "/Wallet",
    name: "Wallet",
    component: () => import("../views/Wallet.vue")
  },
  {
    path: "/ConfirmEmail/:companyName/:key",
    name: "ConfirmEmail",
    component: () => import("../views/ConfirmEmail.vue")
  }
];

const router = new VueRouter({
  routes
});

export default router;
