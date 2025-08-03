<template>
  <div class="order-status-bg">
    <h2>Barra de seguimiento</h2>
    <div class="status-bar">
      <div
          v-for="(step, idx) in steps"
          :key="idx"
          :class="['status-step', { active: order.order_status === step.key }]"
      >
        <div class="circle">
          <span>{{ step.icon }}</span>
        </div>
        <div class="label" :class="{ 'active-label': order.order_status === step.key }">{{ step.label }}</div>
      </div>
    </div>
    <div class="order-details">
      <p><strong>Cliente:</strong> {{ order.client.first_name + " "+ order.client.last_name   }}</p>
      <p><strong>Dirección:</strong> {{ order.client.address }}</p>
      <p><strong>Productos:</strong></p>
      <ul>
        <li v-for="prod in order.details" :key="prod.description">
           {{ prod.description }} x {{ prod.quantity }}
        </li>
      </ul>
      <p><strong>Fecha de entrega:</strong> {{ order.delivery_date }}</p>
    </div>
    <div class="boton-derecha" v-if="order.order_status === 'Entregado'">
      <button @click="goToForm" class="completar-formulario">COMPLETAR FORMULARIO</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ['order'],
  computed: {
    steps() {
      return [
        {key: 'Por Cotizar', label: 'Pedido por cotizar', icon: '📝'},
        {key: 'Cotizado', label: 'Cotización enviada', icon: '📧'},
        {key: 'En Producción', label: 'En producción', icon: '🏭'},
        {key: 'En Marcha', label: 'En marcha', icon: '🏭'},
        {key: 'Entregado', label: 'Pedido entregado', icon: '✅'}
      ];
    }
  },
  methods: {
    goToForm() {
      window.open('/formulario/' + (this.order.id || ''), "_blank");
    }
  }
};
</script>

<style scoped>
.order-status-bg {
  background: rgba(115, 138, 49, 0.8);
  border-radius: 24px;
  margin-top: 32px;
  padding: 20px;
  color: #fff;
  width: 60%;
  max-width: 700px;
  min-width: 280px;
  box-sizing: border-box;
}

.status-bar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 24px;
  font-size: 20px;
  gap: 12px;
  flex-wrap: wrap;
}

.status-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  min-width: 90px;
  margin-bottom: 10px;
}

.circle {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.17);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 8px;
  font-size: 1.7em;
  box-shadow: none;
  transition: background 0.2s, box-shadow 0.2s;
}

.status-step.active .circle {
  background: #ffd700;
  box-shadow: 0 0 12px 2px #ffd70044;
}

.label {
  color: #fff;
  text-align: center;
  font-size: 16px;
  font-weight: normal;
  transition: color 0.2s, font-weight 0.2s;
  word-break: break-word;
}

.active-label {
  color: #ffd700;
  font-weight: bold;
}

.order-details {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 10px;
  padding: 14px;
  margin-bottom: 12px;
  word-break: break-word;
}

.boton-derecha {
  text-align: right;
}

.completar-formulario {
  background: #fff;
  color: #63712c;
  padding: 12px 28px;
  border-radius: 20px;
  border: none;
  font-weight: bold;
  font-size: 18px;
  cursor: pointer;
  margin-top: 8px;
  max-width: 100%;
  min-width: 120px;
  word-break: break-word;
  box-shadow: 0 2px 8px #0002;
  transition: background 0.15s, color 0.15s, box-shadow 0.15s;
}

.completar-formulario:hover,
.completar-formulario:focus {
  background: #ffd700;
  color: #444;
  box-shadow: 0 4px 20px #ffd70055, 0 2px 8px #0002;
  outline: none;
}

.completar-formulario:active {
  background: #bfa100;
  color: #fff;
}

@media (max-width: 900px) {
  .order-status-bg {
    width: 90%;
    padding: 14px 4vw;
    border-radius: 18px;
  }

  .status-bar {
    font-size: 16px;
    gap: 6px;
  }

  .status-step {
    min-width: 70px;
  }

  .circle {
    width: 38px;
    height: 38px;
    font-size: 1.3em;
  }
}

@media (max-width: 600px) {
  .order-status-bg {
    width: 98vw;
    padding: 10px 2vw;
    border-radius: 12px;
  }

  .status-bar {
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }

  .status-step {
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    min-width: 0;
    margin-bottom: 0;
    margin-top: 6px;
  }

  .circle {
    margin-bottom: 0;
    margin-right: 10px;
    width: 34px;
    height: 34px;
    font-size: 1em;
  }

  .label {
    font-size: 15px;
    text-align: left;
  }

  .order-details {
    padding: 9px;
    font-size: 15px;
  }

  .completar-formulario {
    font-size: 15px;
    padding: 10px 12px;
    border-radius: 13px;
    width: 100%;
    min-width: 0;
  }

  .boton-derecha {
    text-align: center;
  }
}
</style>