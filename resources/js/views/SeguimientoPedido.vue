<template>
  <div class="seguimiento-bg">
    <div class="center-box" v-show="statusSearch">
      <h1>JBC TEXTIL</h1>
      <label class="input-label" for="codigo-pedido">Ingrese el código de su pedido</label>
      <input
          id="codigo-pedido"
          v-model="codigo"
          placeholder="Ingrese el código de su pedido"
          @keyup.enter="buscarPedido"
      />
      <p class="info">
        Si ha realizado un pedido con nosotros, el código de pedido se le fue enviado a su correo electrónico registrado.
      </p>
      <button @click="buscarPedido">BUSCAR PEDIDO</button>
      <p v-if="error" class="error">{{ error }}</p>
    </div>
    <OrderStatus v-if="pedido" :order="pedido" />
  </div>
</template>

<script>
import OrderStatus from './OrderStatus.vue';

export default {
  components: { OrderStatus },
  data() {
    return {
      codigo: '',
      pedido: null,
      error: '',
      statusSearch: true
    }
  },
  methods: {
    async buscarPedido() {
      this.error = '';
      this.pedido = null;
      if (!this.codigo) {
        this.error = 'Debe ingresar un código de pedido';
        return;
      }
      // Demo data
      // this.pedido = {
      //   client_name: 'Juan Pérez',
      //   address: 'Sector 5, Casa 10',
      //   delivery_date: '2025-07-03',
      //   status: 'Entregado',
      //   products: [
      //     {producto: 'Camisa', cantidad: 2, descripcion: "PRUEBA"},
      //     {producto: 'Camisa2', cantidad: 2, descripcion: "PRUEBA"},
      //   ]
      // }
      this.statusSearch = false;
      try{
        const res = await axios.post('/api/get-order', {
          code: this.codigo
        });
        if(res.status != 200){
          this.error = 'Pedido no encontrado o error de conexión';
          return false;
        }

        if(!res.data.success){
          this.error = res.data.msg;
          return false;
        }

        this.pedido = res.data.data;

        if (this.pedido.order_status == "En Producción") {
          let enMarcha = false;
          this.pedido.details.forEach(detail => {
            if (
                detail.pre_sale_report &&
                detail.pre_sale_report.details &&
                detail.pre_sale_report.details.some(d => d.date_delivery)
            ) {
              enMarcha = true;
            }
          });

          if (enMarcha) {
            this.pedido.order_status = "En Marcha";
          }
        }

      }catch (e){
        this.error = 'Pedido no encontrado o error de conexión';
      }


      // Uncomment for API:
      // try {
      //   const res = await fetch(`/api/public/orders/${this.codigo}`);
      //   if (!res.ok) throw new Error('Pedido no encontrado');
      //   this.pedido = await res.json();
      // } catch {
      //   this.error = 'Pedido no encontrado o error de conexión';
      // }
    }
  }
}
</script>

<style scoped>
.seguimiento-bg {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 10px;
  overflow: hidden; /* importante para que el ::before no sobresalga */
}

.seguimiento-bg::before {
  content: "";
  position: absolute;
  inset: 0;
  background: url('/images/fondo.jpg') center/cover;
  filter: blur(2px); /* Ajusta la cantidad de difuminado aquí */
  z-index: 0;
}

.seguimiento-bg > * {
  position: relative;
  z-index: 1;
}

.center-box {
  background: rgba(115, 138, 49, 0.75);
  border-radius: 24px;
  padding: 40px 32px 32px 32px;
  width: 100%;
  max-width: 600px;
  text-align: center;
  box-sizing: border-box;
}

h1 {
  margin-bottom: 18px;
  color: #fff;
}

.input-label {
  display: block;
  font-size: 1.5em;
  color: #fff;
  margin-bottom: 10px;
  font-weight: 400;
  text-align: left;
}

input {
  width: 100%;
  padding: 1em;
  border-radius: 24px;
  border: none;
  margin-bottom: 10px;
  font-size: 20px;
  box-sizing: border-box;
  background: #fff;
  color: #444;
  outline: none;
  transition: box-shadow 0.2s;
  box-shadow: 0 0 0 1px #ffd70033;
}

input:focus {
  box-shadow: 0 0 0 2px #ffd70088;
}

.info {
  font-size: 1em;
  color: #eaeaea;
  margin-bottom: 18px;
  margin-top: -3px;
  text-align: center;
}

button {
  margin-top: 8px;
  padding: 0.8em 2em;
  border-radius: 24px;
  background: #fff;
  color: #63712c;
  font-weight: bold;
  border: none;
  font-size: 1.5em;
  width: 100%;
  max-width: 340px;
  box-shadow: 0 2px 8px #0001;
  transition: background 0.15s, color 0.15s;
}

button:hover {
  background: #ffd700;
  color: #444;
}

.error {
  color: #fff;
  margin-top: 10px;
  word-break: break-word;
  font-weight: bold;
}

/* Responsive styles */
@media (max-width: 600px) {
  .center-box {
    padding: 24px 6px 20px 6px;
    border-radius: 14px;
    max-width: 98vw;
  }

  h1 {
    font-size: 1.2em;
  }

  .input-label {
    font-size: 1.05em;
    text-align: center;
  }

  input {
    font-size: 16px;
    padding: 0.7em;
    border-radius: 14px;
  }

  .info {
    font-size: 0.93em;
    padding: 0 2px;
  }

  button {
    font-size: 1.1em;
    padding: 0.7em 1em;
    border-radius: 13px;
  }
}
</style>