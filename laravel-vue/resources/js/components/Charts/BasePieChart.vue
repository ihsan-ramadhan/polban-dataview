<template>
  <Pie :data="chartData" :options="chartOptions" />
</template>

<script>
import { Pie } from 'vue-chartjs'

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  CategoryScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale)

export default {
  name: 'BasePieChart',
  components: {
    Pie
  },
  props: {
    chartData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              font: {
                size: 12,
                family: 'Poppins, sans-serif'
              },
              boxWidth: 12,
            },
            onHover: (event, legendItem, legend) => {
              event.native.target.style.cursor = 'pointer';
            },
            onLeave: (event, legendItem, legend) => {
              event.native.target.style.cursor = 'default';
            }
          },
          tooltip: {
            titleFont: {
              size: 12,
              weight: '500',
              family: 'Poppins, sans-serif'
            },
            bodyFont: {
              size: 11,
              family: 'Poppins, sans-serif'
            },
            padding: 10,
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            boxPadding: 6
          },
          title: {
            display: false
          }
        }
      }
    }
  }
}
</script>