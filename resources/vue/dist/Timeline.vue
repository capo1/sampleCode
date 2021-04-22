<template>
  <div class="col-xs-12 col-md-9 p-0">
    <div class="row items">
      <div
        class="number_container col-xs-12 col-sm-6 col-md-6 col-lg-4"
        v-for="(no, index) in numbers.numbers"
        v-bind:key="index"
      >
        <div class="item">
          <div class="number">
            <CircleEx :unit="no.unit" color="#fbd23c" :percent="parseFloat(no.number)" />
          </div>
          <div v-if="no.desc.length > 0" class="desc" v-html="no.desc"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CircleEx from "./components/CircleEx.vue";
export default {
  name: "Numbers",
  components: { CircleEx },
  data() {
    return {
      numbers: [],
      percent: 100,
    };
  },
  mounted: function () {
    this.numbers = JSON.parse(this.$parent.$props.numbers);
  },
  watch: {},
  methods: {},
};
</script>
<style></style>