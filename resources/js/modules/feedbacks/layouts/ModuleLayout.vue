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
        <div class="chart-wrapper">
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
            Rango de fechas
          </div>
          <div class="card-body">
            <label>Desde:</label>
            <input
                type="date"
                v-model="startDate"
                class="form-control mb-2"
            />
            <label>Hasta:</label>
            <input
                type="date"
                v-model="endDate"
                class="form-control"
            />
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
    const today = new Date().toISOString().slice(0, 10); // YYYY-MM-DD
    return {
      scoreTypes: [
        { label: 'calidad del producto', value: 'quality_score' },
        { label: 'cumplimiento de requisitos', value: 'compliance_score' },
        { label: 'atención brindada', value: 'attention_score' },
        { label: 'análisis general', value: 'general_analysis' }
      ],
      selectedScoreType: 'quality_score',
      startDate: null,
      endDate: null,
      scores: {},             // contadores por question_n
      generalAnalysis: {},    // promedios generales
    }
  },
  components: {
    'bar-chart': chartComponent,
  },
  computed: {
    chartData() {
      const isGeneral = this.selectedScoreType === 'general_analysis';

      let data = [];
      let labels = [];

      if (isGeneral) {
        // Gráfico de análisis general: promedio de cada tipo
        const general = this.generalAnalysis;
        const mapping = {
          quality_score: 'Calidad del producto',
          compliance_score: 'Cumplimiento de requisitos',
          attention_score: 'Atención brindada'
        };

        labels = Object.keys(general).map(k => mapping[k] || k);
        data = Object.values(general).map(v => parseFloat(v.toFixed(2)));
      } else {
        // Etiquetas específicas según el tipo de puntaje
        const questionLabels = {
          quality_score: ['Mala', 'Buena', 'Excelente'],
          compliance_score: ['No cumple', 'Cumple parcialmente', 'Cumple completamente'],
          attention_score: ['Deficiente', 'Aceptable', 'Excelente']
        };

        const typeData = this.scores[this.selectedScoreType] || {};
        labels = questionLabels[this.selectedScoreType] || ['Pregunta 1', 'Pregunta 2', 'Pregunta 3'];
        data = ['question_1', 'question_2', 'question_3'].map(q => typeData[q] || 0);
      }

      const baseColors = ['#e74c3c', '#3498db', '#2ecc71']; // rojo, azul, verde
      const backgroundColors = data.map((_, index) => baseColors[index % baseColors.length]);

      return {
        labels,
        datasets: [{
          label: this.scoreTypes.find(t => t.value === this.selectedScoreType)?.label || '',
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
          yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }],
          xAxes: [{ ticks: {
              fontSize: 20
            } }]
        }
      };
    }
  },
  methods: {
    selectScoreType(type) {
      this.selectedScoreType = type;
    },
    async fetchScores() {
      try {
        let url = '/api/report-quality';
        const params = [];
        if (this.startDate) params.push(`start=${this.startDate}`);
        if (this.endDate) params.push(`end=${this.endDate}`);
        if (params.length > 0) url += '?' + params.join('&');

        const result = await axios.get(url);
        if (result.status === 200) {
          this.scores = result.data.scores;
          this.generalAnalysis = result.data.general_analysis;
        }
      } catch (e) {
        this.scores = {};
        this.generalAnalysis = {};
      }
    }
  },
  mounted() {
    this.fetchScores();
  },
  watch:{
    startDate() {
      this.fetchScores();
    },
    endDate() {
      this.fetchScores();
    }
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
  height: 100%;
  width: 100%;
}
</style>