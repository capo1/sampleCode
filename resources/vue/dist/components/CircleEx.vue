<template>
  <div>
    <figure class="circle" :id="id" v-observe-visibility="visibilityChanged">
      <svg
        class="score"
        :width="size"
        :height="size"
        :viewBox="`-25 -25 ${size} ${size}`"
      >
        <circle
          class="score-empty"
          :cx="radius"
          :cy="radius"
          :r="radius"
          :stroke-width="stroke"
          fill="none"
        ></circle>
        <circle
          class="js-circle score-circle"
          :transform="`rotate(-90 ${radius} ${radius})`"
          :cx="radius"
          :cy="radius"
          :r="radius"
          :stroke-dasharray="diameter"
          :stroke-width="stroke"
          :stroke-dashoffset="diameter"
          fill="none"
        ></circle>
      </svg>
      <div class="percent">
        <span class="percent__int">{{ val }}</span>
        <span class="label">{{ unit }}</span>
      </div>
    </figure>
  </div>
</template>

<script>
export default {
  name: "CircleEx",
  props: {
    unit: {
      type: String,
      default: "",
    },
    color: {
      type: String,
      default: "red",
    },
    percent: {
      type: Number,
      default: 10,
    },
    delay: {
      type: Number,
      default: 10,
    },
  },
  data() {
    return {
      radius: 80, //radius of circle
      padding: 25, //padding
      stroke: 25, //radiusBorderWidth
      size: 0, //counted on mount
      diameter: 0, //counted on mount
      counter: 0,
      val: 0,
      el: {},
      scoreCircle: null,
      scoreEmty: null,
      interval: 150, //animation interval for numbers and strokeWidth
      id: "circle_" + Math.random(),
      isVisible: false,
      isAnimated: false,
    };
  },
  mounted() {
    this.size = (this.radius + this.padding) * 2;
    this.diameter = Math.round(Math.PI * this.radius * 2);
    this.el = document.getElementById(this.id);
    this.scoreCircle = this.el.querySelector(".score-circle");
    this.scoreEmty = this.el.querySelector(".score-empty");
    this.scoreCircle.style.stroke = this.color;
    this.scoreEmty.style.stroke = "#ddd";
  },
  watch: {
    isVisible(newValue) {
      if (this.isAnimated == true) return; //only once Aniamted
      if (newValue == true) {
        this.increaseNumber("int");
      } else {
        this.scoreCircle.style.strokeDashoffset = this.diameter;
      }
    },
  },
  methods: {
    visibilityChanged(isVisible, entry) {
      this.isVisible = isVisible;
      isVisible ? entry.target.classList.add("isVisible") : "";
    },
    strokeTransition(interval) {
      let circumference = Math.round(Math.PI * this.radius * 2);
      // round to nerest 10, 100, 1000, 10000 etc.
      let h = Math.pow(10, Math.ceil(Math.log10(this.percent)));
      let offset = Math.round(((h - this.percent) / h) * circumference);
      this.scoreCircle.style.transition = `stroke-dashoffset ${
        interval * this.interval
      }ms ease-out`;
      this.scoreCircle.style.strokeDashoffset = offset;
    },
    increaseNumber(className) {
      let h = Math.round((this.percent / 10 + Number.EPSILON) * 10) / 10,
        counter = 0;
      this.strokeTransition(this.percent / h);
      let increaseInterval = setInterval(() => {
        this.val = Math.round((counter + Number.EPSILON) * 10) / 10;
        if (counter >= this.percent) {
          this.val = this.percent;
          window.clearInterval(increaseInterval);
          this.isAnimated = true; //only once animated
        }
        counter = counter + h;
      }, this.interval);
    },
  },
};
</script>

<style lang="scss" scoped>
$color-front: #5c6166;
$color-subtle: #ddd;
figure {
  margin: 0;
  position: relative;
  text-align: center;
}
.percent {
  width: 100%;
  top: 50%;
  left: 50%;
  position: absolute;
  font-weight: bold;
  text-align: center;
  line-height: 28px;
  display: flex;
  justify-content: center;
  flex-direction: column;
  line-height: 1.3;
  align-items: center;
  transform: translate(-50%, -50%);
}
.percent__int {
  font-size: 4rem;
}
.label {
  font-weight: normal;
  font-size: 0.55em;
}
.score-circle {
  stroke: $color-front;
}
.score-empty {
  stroke: $color-subtle;
}
</style>