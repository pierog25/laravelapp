<template>
  <div class="main-content">
    <div class="header bg-default pb-6">
      <div class="container-fluid">
        <div class="header-body"></div>
      </div>
    </div>
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
      <div class="col-7 d-flex align-items-center justify-content-center chart-wrapper">
<!--        <bar-chart-->
<!--            :chart-data="chartData"-->
<!--            :options="chartOptions"-->
<!--            :height="400"-->
<!--        />-->
        <div style="width: 100%; height: 100%;">
          <bar-chart
              :chart-data="chartData"
              :options="chartOptions"
              :height="800"
          />
        </div>
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
                :class="{active: selectedDistributors.includes(dist.id)}"
                @click="toggleDistributor(dist.id)"
            >
              {{ dist.name }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Bar } from 'vue-chartjs';

const chartComponent = {
  props: ['chartData', 'options', 'height'],
  extends: Bar,
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
  data() {
    return {
      scoreTypes: [
        {label: 'puntajes de tiempo', value: 'tiempo'},
        {label: 'puntajes de costos', value: 'costos'},
        {label: 'puntajes de calidad', value: 'calidad'},
        {label: 'puntaje general', value: 'general'}
      ],
      selectedScoreType: 'tiempo',
      distributors: [], // [{id, name}]
      selectedDistributors: [], // [1,2,3]
      scores: {}, // {distributorId: {nombre, tiempo, ...}}
    }
  },
  components: {
    'bar-chart': chartComponent,
  },
  computed: {
    chartData() {
      // Eje X: nombres de los distribuidores seleccionados
      const labels = this.selectedDistributors.map(id => this.scores[id]?.nombre || '');
      // Valores: el puntaje del tipo seleccionado por distribuidor
      const data = this.selectedDistributors.map(id => this.scores[id]?.[this.selectedScoreType] || 0);

      const baseColors = [
        "#3498db", "#f1c40f", "#e67e22", "#e74c3c", "#2ecc71", "#9b59b6", "#1abc9c",
        "#ff9ff3", "#48dbfb", "#1dd1a1", "#feca57", "#ff6b6b", "#5f27cd", "#54a0ff"
      ];

      // Generar colores dependiendo de cuántas barras hay
      const backgroundColors = data.map((_, index) => baseColors[index % baseColors.length]);

      return {
        labels,
        datasets: [{
          label: this.scoreTypes.find(t=>t.value===this.selectedScoreType)?.label || '',
          backgroundColor: backgroundColors,
          data
        }]
      };
    },
    chartOptions() {
      return {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
          yAxes: [{
            ticks: {beginAtZero: true, stepSize: 1}
          }],
          xAxes: [{
            ticks: {
              autoSkip: false,
              maxRotation: 45,
              minRotation: 45,
              fontSize: 10
            }
          }]
        }
      };
    }
  },
  methods: {
    selectScoreType(type) {
      this.selectedScoreType = type;
    },
    toggleDistributor(id) {
      const idx = this.selectedDistributors.indexOf(id);
      if (idx >= 0) {
        this.selectedDistributors.splice(idx, 1);
      } else {
        this.selectedDistributors.push(id);
      }
    },
    async fetchDistributors() {
      try {
        const result = await axios.get(`/api/supplier`);
        if (result.status == 200) {
          this.distributors = result.data;
          // Selecciona todos por defecto
          this.selectedDistributors = this.distributors.map(d => d.id);
        }
      } catch (e) {
        this.distributors = [];
        this.selectedDistributors = [];
      }
    },
    async fetchScores() {
      try {
        const result = await axios.get(`/api/report`);
        if (result.status == 200) {
          this.scores = result.data;
        }
      } catch (e) {
        this.scores = {};
      }
    }
  },
  mounted() {
    this.fetchDistributors();
    this.fetchScores();
  }
}
</script>
<style scoped>
.btn.active {
  background: #e8f0fe !important; /* fondo azul clarito */
  color: #0d47a1 !important;      /* texto azul oscuro */
  border-color: #90caf9 !important; /* opcional: borde suave */
  font-weight: 600;
}
.chart-wrapper {
  height: 500px;
  width: 100%;
}
</style>