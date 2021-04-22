import Vue from "vue";

import Numbers from "../vue/dist/Numbers.vue";
import { ObserveVisibility } from "vue-observe-visibility";


if ($("#numbers").length > 0) {
  Vue.directive("observe-visibility", ObserveVisibility);
  const mountEl = document.querySelector("#numbers");
  
  new Vue({
    propsData: { ...mountEl.dataset },
    props: ["numbers"],
    render: (h) => h(Numbers),
  }).$mount("#numbers");
  
}