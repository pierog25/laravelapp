<template>
  <div class="form-bg">
    <div class="form-center-box" v-if="!loading && !alreadySubmitted && !success">
      <h1>Formulario de experiencia</h1>

      <div class="form-group">
        <label>¿Cómo le pareció la calidad del producto?</label>
        <div class="pill-group">
          <button
              v-for="(opt, idx) in calidadOpts"
              :key="opt"
              type="button"
              :class="['pill-btn', {selected: form.calidad === idx+1}]"
              @click="form.calidad = idx+1"
          >{{ opt }}</button>
        </div>
      </div>

      <div class="form-group">
        <label>¿El producto cumple con los requisitos solicitados?</label>
        <div class="pill-group no-wrap">
          <button
              v-for="(opt, idx) in cumpleOpts"
              :key="opt"
              type="button"
              :class="['pill-btn', {selected: form.cumple === idx+1}]"
              @click="form.cumple = idx+1"
          >{{ opt }}</button>
        </div>
      </div>

      <div class="form-group">
        <label>¿Qué te pareció la atención recibida por la empresa?</label>
        <div class="pill-group">
          <button
              v-for="(opt, idx) in atencionOpts"
              :key="opt"
              type="button"
              :class="['pill-btn', {selected: form.atencion === idx+1}]"
              @click="form.atencion = idx+1"
          >{{ opt }}</button>
        </div>
      </div>

      <button class="submit-btn" @click="handleSubmit" :disabled="!formIsValid || submitting">
        <span v-if="!submitting">Enviar</span>
        <span v-else>Enviando...</span>
      </button>
      <div v-if="error" class="error-msg">{{ error }}</div>
    </div>
    <div class="form-center-box" v-if="alreadySubmitted">
      <h2>¡Ya has enviado el formulario para este pedido!</h2>
    </div>
    <div class="form-center-box" v-if="success">
      <h2>¡Gracias por tu feedback!</h2>
    </div>
    <div class="form-center-box" v-if="loading">
      <h2>Cargando...</h2>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'FormularioExperiencia',
  data() {
    return {
      calidadOpts: ['Mala', 'Buena', 'Excelente'],
      cumpleOpts: ['No cumple', 'Cumple parcialmente', 'Cumple completamente'],
      atencionOpts: ['deficiente', 'aceptable', 'excelente'],
      form: {
        calidad: null,
        cumple: null,
        atencion: null,
      },
      loading: true,
      submitting: false,
      alreadySubmitted: false,
      success: false,
      error: "",
    }
  },
  computed: {
    formIsValid() {
      return this.form.calidad && this.form.cumple && this.form.atencion;
    },
    pedidoId() {
      // Obtener id de la URL
      return window.location.pathname.split('/').pop();
    }
  },
  mounted() {
    // Valida vía API si ya hay feedback para este pedido
    axios.get(`/api/order-feedback`,{
      params: { order_id: this.pedidoId }
    })
        .then(res => {
          if (res.data.exists) {
            this.alreadySubmitted = true;
          }
        })
        .catch(() => {})
        .finally(() => this.loading = false);
  },
  methods: {
    handleSubmit() {
      if (!this.formIsValid || this.submitting) return;
      this.submitting = true;
      this.error = "";

      axios.post('/api/order-feedback', {
        order_id: this.pedidoId,
        quality_score: this.form.calidad,
        compliance_score: this.form.cumple,
        attention_score: this.form.atencion,
      })
          .then(res => {
            if (res.data.success) {
              this.success = true;
            } else {
              this.error = "Ocurrió un error al enviar el feedback.";
            }
          })
          .catch(err => {
            if (err.response && err.response.data && err.response.data.errors) {
              this.error = Object.values(err.response.data.errors).join(" ");
            } else {
              this.error = "Ocurrió un error de conexión o validación.";
            }
          })
          .finally(() => this.submitting = false);
    }
  }
}
</script>

<style scoped>

.pill-group.no-wrap {
  flex-wrap: nowrap;
  justify-content: center;
  gap: 14px;
}

@media (max-width: 600px) {
  .pill-group.no-wrap {
    flex-wrap: wrap;
    justify-content: center;
  }
}

.form-bg {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 10px;
  overflow: hidden; /* importante para el pseudo-elemento */
}

.form-bg::before {
  content: "";
  position: absolute;
  inset: 0;
  background: url('/images/fondo.jpg') center/cover;
  filter: blur(2px); /* ajusta el nivel de difuminado */
  z-index: 0;
}

.form-bg > * {
  position: relative;
  z-index: 1;
}
.form-center-box {
  background: rgba(115, 138, 49, 0.80);
  border-radius: 24px;
  padding: 40px 32px;
  width: 100%;
  max-width: 600px;
  text-align: center;
  box-sizing: border-box;
  color: #fff;
}
h1 {
  margin-bottom: 30px;
  font-size: 2.1em;
  font-weight: bold;
}
.form-group {
  margin-bottom: 32px;
  text-align: center;
}
.form-group label {
  display: block;
  font-size: 1.15em;
  margin-bottom: 10px;
  font-weight: 500;
}
.pill-group {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 14px;
}
.pill-btn {
  padding: 0.7em 1.7em;
  border-radius: 22px;
  border: none;
  background: #fff;
  color: #63712c;
  font-weight: bold;
  font-size: 1em;
  margin: 0 5px 5px 0;
  transition: background 0.15s, color 0.15s, box-shadow 0.15s;
  box-shadow: 0 2px 8px #0001;
  cursor: pointer;
  outline: none;
}
.pill-btn.selected,
.pill-btn:focus {
  background: #ffd700;
  color: #444;
  box-shadow: 0 4px 20px #ffd70044;
}
.pill-btn:hover:not(.selected) {
  background: #d7f0a4;
  color: #444;
}
.submit-btn {
  background: #fff;
  color: #63712c;
  font-weight: bold;
  font-size: 1.18em;
  border: none;
  border-radius: 24px;
  padding: 0.8em 2.6em;
  margin-top: 18px;
  margin-bottom: 0;
  transition: background 0.15s, color 0.15s, box-shadow 0.15s;
  box-shadow: 0 2px 8px #0001;
  cursor: pointer;
  outline: none;
  display: inline-block;
}
.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
.submit-btn:hover:enabled,
.submit-btn:focus:enabled {
  background: #ffd700;
  color: #444;
  box-shadow: 0 4px 20px #ffd70044;
}
.error-msg {
  margin-top: 12px;
  color: #ffd700;
  font-weight: bold;
}
@media (max-width: 600px) {
  .form-center-box {
    padding: 24px 6px;
    border-radius: 14px;
    max-width: 98vw;
  }
  h1 {
    font-size: 1.3em;
  }
  .form-group label {
    font-size: 1em;
  }
  .pill-btn, .submit-btn {
    font-size: 0.97em;
    padding: 0.6em 1.1em;
    border-radius: 15px;
  }
  .pill-group {
    gap: 7px;
  }
}
</style>