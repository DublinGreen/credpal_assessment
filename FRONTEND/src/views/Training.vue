<template>
  <section style="width: 100%;background-color: #f5f8fb;border-radius: 10px;">
    <NavMain />
      <v-content style="clear: both;padding: 2%;margin:0;width: 96%;margin:1% 1%;background-color:#fff;">
            <section class="breadCrumbsBox">
              <v-breadcrumbs :items="breadCrumbsData" large></v-breadcrumbs>
            </section>
            <section class="clearfix"></section>
            <br />



            <section class="container container--fluid">
              <section class="row">
                <div class="col col-12">
                  <div class="d-flex grow flex-wrap"><div class="text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 success pa-7" style="max-height: 90px; width: auto;"><i aria-hidden="true" class="v-icon notranslate fas fa-graduation-cap theme--dark" style="font-size: 32px;"></i><!----></div><!----><div class="ml-4"><div class="card-title font-weight-light">Trainings</div></div></div>
                </div>
                  <div class="col-sm-12 col-md-4 col" v-for="item in activeTrainings" :key="item['training'].id">
                    <div class="col-sm-12 col-md-12 col"
                    
                                    v-bind:style="{color:'#4caf50',fontWeight: '900',fontSize:'1.2em',textTransform: 'capitalize',float:'left'}">
                      <div class="v-card--material pa-3 v-card v-sheet theme--light v-card--material--hover-reveal">
                        <div class="d-flex grow flex-wrap">
                          <div class="text-start v-card--material__heading mb-n6 v-sheet theme--dark elevation-6 transparent" style="width: 100%;">
                                  
                            <!-- <div class="v-responsive v-image">
                              <div class="v-responsive__sizer" style="padding-bottom: 66.1667%;"></div>
                              <div class="v-image__image v-image__image--cover"
                              @mouseover="isHovering = true;hoverTraining = hoverTrainImageHoverValueMax" 
                              @mouseout="hoverTraining = hoverTrainImageHoverValueMin" 
                              v-if="isHovering"
                              v-bind:style="{zIndex: 99,marginTop: hoverTraining + 'px',backgroundImage: 'url(' + serverUrl + item['training'].training_image_path + ')', backgroundPosition: 'center center' }">
                                
                              </div> -->

                              <div class="v-responsive v-image">
                              <div class="v-responsive__sizer" style="padding-bottom: 66.1667%;"></div>
                              <div class="v-image__image v-image__image--cover"
                              v-bind:style="{zIndex: 99,marginTop: hoverTraining + 'px',backgroundImage: 'url(' + serverUrl + item['training'].training_image_path + ')', backgroundPosition: 'center center' }">
                                
                              </div>
                              <div class="v-responsive__content" style="width: 1200px;"></div>
                              </div>
                            </div>
                          <div class="text-center py-0 mt-n12 col col-12">
                            
                            <span class="v-tooltip v-tooltip--bottom">
                              <div class="v-tooltip__content" style="left: 0px; opacity: 0; top: 12px; z-index: 0; display: none;"></div></span>
                              <span class="v-tooltip v-tooltip--bottom"></span>
                              
                              <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                                <template v-slot:activator="{ on,attrs}">
                                  <v-btn type="button" 
                                    v-bind="attrs"
                                    v-on="on"
                                    class="mx-1 v-btn v-btn--flat v-btn--icon v-btn--round theme--light v-size--default success--text" role="button" 
                                    aria-haspopup="true" aria-expanded="false">
                                    <span class="v-btn__content"><i aria-hidden="true" style="font-size:70px" 
                                    class="v-icon notranslate success--text mdi mdi-eye theme--light"></i></span>
                                  </v-btn>
                                </template>
                                <v-card>
                                  <v-toolbar dark color="success">
                                    <v-btn icon dark @click="dialog = false">
                                      <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                    <v-toolbar-title>{{item['training'].name | capitalize }}</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-toolbar-items>
                                      <v-btn dark text @click="dialog = false">Close</v-btn>
                                    </v-toolbar-items>
                                  </v-toolbar>
                                  <v-list three-line subheader>
                                    <!-- <v-subheader>{{item['training'].name | capitalize}}</v-subheader> -->
                                    
                                    <video width="720" height="540" controls oncontextmenu="return false;">
                                      <source :src="serverUrl + item['files'][0].path" type="video/mp4">
                                    Your browser does not support the video tag.
                                    </video>
                                    <v-list-item>
                                      <v-list-item-content>
                                        <v-list-item-title>{{item['training'].name | capitalize}}</v-list-item-title>
                                        <v-list-item-subtitle>{{item['training'].description}}</v-list-item-subtitle>
                                      </v-list-item-content>
                                    </v-list-item>
                                  </v-list>
                                  <v-divider></v-divider>
                                  
                                </v-card>
                              </v-dialog>
                              
                              </div>
                            </div>
                            <br>
                            <div class="v-card__title justify-center font-weight-light" :class="{hovering: isHovering}"> {{item['training'].name | capitalize }} </div>
                              <div class="v-card__text body-1 text-center mb-3 font-weight-light grey--text"> {{item['training'].description}} </div>
                              <hr role="separator" aria-orientation="horizontal" class="mt-2 v-divider theme--light">
                              <div class="v-card__actions pb-0"><div class="display-2 font-weight-light grey--text" v-html="combineCurrencyAndPrice(item['training'].currency,item['training'].price)">  </div>
                              <div class="display-1 font-weight-light grey--text"></div>
                              <div class="spacer"></div><span class="caption grey--text font-weight-light"><i aria-hidden="true" class="v-icon notranslate mdi mdi-map-marker theme--light" style="font-size: 16px;"></i> {{item['training'].location}} </span></div>
                            </div>
                          </div>

                  </div>

              </section>              
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
  name: "Training",
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

    // let dataToSend = {
    //   email: localStorage.getItem(store.state.setEmailLocalStorageKey)
    // };
    console.log(localStorage.getItem(
          store.state.setTokenLocalStorageKey
        ));

    axios
      .get(this.endpoint, config)
      .then(function(response) {
        if (response.data.status) {
            vmObjectInstance.activeTrainings = response.data.data;
            
        }else{
          vmObjectInstance.logout();
        }
      })
      .catch(function(error) {
        console.error(error);
      });
  },
  data: function() {
    return {
      dialog: false,
      notifications: false,
      sound: true,
      widgets: false,
      loadingPage: true,
      isLogin: store.state.isLogin,
      hoverTrainImageHoverValueMin:0,
      hoverTrainImageHoverValueMax:-75,
      hoverTraining: 0,
      isHovering: true,
      activeTrainings: null,
      serverUrl: store.state.urlStore.serverUrl,
      endpoint:
        store.state.urlStore.baseUrl + store.state.urlStore.getAllActiveTrainingWithFilesUrl,
      endpointSingleTraining:
        store.state.urlStore.baseUrl + store.state.urlStore.getTrainingByIDUrl,
      breadCrumbsData: []
    };
  },
  filters: {
    capitalize: function (value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    }
  },
  methods: {
    editProfile() {
      // this.$router.push(this.breadCrumbsData[0].href);
      // window.open(this.breadCrumbsData[0].href); // open in new tab
      location.replace(this.breadCrumbsData[0].href);
    },
    combineCurrencyAndPrice (currency,price) {
      return currency + price;
    }
  }
};
</script>


<style scoped>
  #profileBox{
    clear: both;
  }


  #profileBox p{
    float: left;
    width: 100%;
    text-align: left;
  }

  .hovering{
    color:#4caf50;
    font-weight: 900;
    font-size:1.2em;
    text-transform: capitalize;
  }
</style>