<template>
  <div style="height: 400px; width: 100%; z-index: 1;">
    <l-map 
      ref="map" 
      v-model:zoom="zoom" 
      :center="center" 
      :use-global-leaflet="false"
    >
      <l-tile-layer
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        layer-type="base"
        name="OpenStreetMap"
        attribution="&copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a>"
      />

      <l-circle-marker
        v-for="(item, index) in mapData"
        :key="index"
        :lat-lng="[item.geo.lat, item.geo.lng]"
        :radius="calculateRadius(item.total)"
        :color="'#21308f'" 
        :fill-color="'#21308f'"
        :fill-opacity="0.6"
        :weight="1"
      >
        <l-tooltip>
          {{ item.provinsi }}: {{ item.total }}
        </l-tooltip>
      </l-circle-marker>
    </l-map>
  </div>
</template>

<script>
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LCircleMarker, LTooltip } from "@vue-leaflet/vue-leaflet";

export default {
  name: "BaseMap",
  components: {
    LMap,
    LTileLayer,
    LCircleMarker,
    LTooltip
  },
  props: {
    mapData: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    return {
      zoom: 5,
      center: [-2.5489, 118.0149],
    };
  },
  methods: {
    calculateRadius(total) {
      const baseRadius = 5;
      const scaleFactor = 0.5; 
      return baseRadius + Math.sqrt(total) * scaleFactor;
    }
  }
};
</script>

<style scoped>
.popup-content {
  font-family: 'Poppins', sans-serif;
  font-size: 0.9rem;
  text-align: center;
}
</style>