<template>
  <div class="row" style="background: white; height: 100%; width: 100%">
    <!-- Botones de puntaje (izquierda) -->
    <div class="col-2 d-flex flex-column align-items-start">
      <div class="card">
        <div class="card-header text-center font-weight-bold">
          Puntajes seleccionados
        </div>
        <div class="card-body">
          <button
              v-for="(type, i) in scoreTypes"
              :key="i"
              class="btn btn-outline-secondary mb-2"
              :class="{active: selectedScoreType === type.value}"
              @click="selectScoreType(type.value)"
          >
            {{ type.label }}
          </button>
        </div>
      </div>
    </div>

    <!-- Gráfica (centro) -->
    <div class="col-7 d-flex align-items-center justify-content-center">
      <bar-chart
          :chart-data="chartData"
          :options="chartOptions"
          :height="400"
      />
    </div>

    <!-- Selección de distribuidores (derecha) -->
    <div class="col-3">
      <div class="card">
        <div class="card-header text-center font-weight-bold">
          Distribuidores seleccionados
        </div>
        <div class="card-body">
          <button
              v-for="dist in distributors"
              :key="dist.id"
              class="btn btn-outline-secondary btn-block mb-2"
              :class="{active: selectedDistributor.id === dist.id}"
              @click="selectDistributor(dist)"
          >
            {{ dist.name }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Bar, Line, mixins } from 'vue-chartjs';

const chartComponent = {
  props: ['chartData', 'options', 'height'],
  extends: Bar, // cambiar dinámicamente en renderChart
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
  watch: {
    chartData() {
      this.renderChart(this.chartData, this.options);
    }
  }
};

export default {
  components: {
    'bar-chart': chartComponent,
    'line-chart': {
      ...chartComponent,
      extends: Line
    }
  },
  data() {
    return {
      // Tipos de puntajes
      scoreTypes: [
        {label: 'puntajes de tiempo', value: 'tiempo'},
        {label: 'puntajes de costos', value: 'costos'},
        {label: 'puntajes de calidad', value: 'calidad'},
        {label: 'puntaje general', value: 'general'}
      ],
      selectedScoreType: 'tiempo',
      distributors: [], // [{id, name}]
      selectedDistributor: {},
      chartType: 'bar', // 'bar' o 'line'
      scores: {}, // {distributorId: {tiempo:[], costos:[], calidad:[], general:[]}}
    };
  },
  computed: {
    chartData() {
      // Ejemplo de estructura
      // scores: { "1": { tiempo: [2,3,5...], costos: [...], ... }, ... }
      const distId = this.selectedDistributor.id;
      const puntajes = (this.scores[distId] && this.scores[distId][this.selectedScoreType]) || [];
      return {
        labels: puntajes.map((p, i) => p.label || `P.M. ${i + 1}`),
        datasets: [{
          label: this.selectedScoreType,
          backgroundColor: '#3498db',
          data: puntajes.map(p => p.value)
        }]
      };
    },
    chartOptions() {
      return {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          yAxes: [{
            ticks: {beginAtZero: true, stepSize: 1}
          }]
        }
      };
    }
  },
  methods: {
    selectScoreType(type) {
      this.selectedScoreType = type;
    },
    selectDistributor(dist) {
      this.selectedDistributor = dist;
    },
    // Simula fetch desde API
    async fetchDistributors() {
      // Aquí deberías hacer fetch a tu API real
      this.distributors = [
        {id: 1, name: 'Distribuidor 1'},
        {id: 2, name: 'Distribuidor 2'},
      ];
      this.selectedDistributor = this.distributors[0];
    },
    async fetchScores() {
      // Aquí deberías hacer fetch a tu API real
      this.scores = {
        1: {
          tiempo: [
            { label: '34.5', value: 1 },
            { label: '44.5', value: 3 },
            { label: '54.5', value: 6 },
            { label: '64.5', value: 10 },
            { label: '74.5', value: 7 },
            { label: '84.5', value: 5 },
            { label: '94.5', value: 2 },
          ],
          costos: [
            { label: '34.5', value: 2 },
            { label: '44.5', value: 4 },
            { label: '54.5', value: 8 },
            { label: '64.5', value: 7 },
            { label: '74.5', value: 4 },
            { label: '84.5', value: 3 },
            { label: '94.5', value: 1 },
          ],
          calidad: [
            { label: '34.5', value: 1 },
            { label: '44.5', value: 2 },
            { label: '54.5', value: 5 },
            { label: '64.5', value: 8 },
            { label: '74.5', value: 8 },
            { label: '84.5', value: 6 },
            { label: '94.5', value: 3 },
          ],
          general: [
            { label: '34.5', value: 1 },
            { label: '44.5', value: 3 },
            { label: '54.5', value: 7 },
            { label: '64.5', value: 9 },
            { label: '74.5', value: 7 },
            { label: '84.5', value: 4 },
            { label: '94.5', value: 2 },
          ]
        }
      };
    }
  },
  watch: {
    chartType: {
      handler() {
        // Cambia el tipo de gráfica (opcional: podrías mostrar otro componente)
      }
    }
  },
  mounted() {
    this.fetchDistributors();
    this.fetchScores();
  }
};
</script>

<style scoped>
.active {
  background: #3498db !important;
  color: #fff !important;
}
</style>