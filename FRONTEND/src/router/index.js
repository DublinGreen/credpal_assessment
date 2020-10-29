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
    path: "/EditProfile/:c",
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
    path: "/UploadDocument",
    name: "UploadDocument",
    component: () => import("../views/UploadDocument.vue")
  },
  {
    path: "/ManageUserDocumentsCrud",
    name: "ManageUserDocumentsCrud",
    component: () => import("../views/ManageUserDocumentsCrud.vue")
  },
  {
    path: "/ConfirmEmail/:companyName/:key",
    name: "ConfirmEmail",
    component: () => import("../views/ConfirmEmail.vue")
  },
  {
    path: "/Training",
    name: "Training",
    component: () => import("../views/Training.vue")
  }
];

const router = new VueRouter({
  routes
});

export default router;
