<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Registro del nuevo pedido</h3>
    </div>
    <div class="card-body">
      <validation-observer ref="validation-observer" v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-6 mb-2">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tr>
                    <td><strong>Cliente:</strong></td>
                    <td>
                      {{ item.client.first_name + " " + item.client.last_name }}
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Correo:</strong></td>
                    <td>{{ item.client.email }}</td>
                  </tr>
                  <tr>
                    <td><strong>Teléfono:</strong></td>
                    <td>{{ item.client.phone }}</td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Costo total</th>
                      <th>Precio Final</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ totalCost }}</td>
                      <td>{{ finalPrice }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div v-for="(data, index) in item.details" :key="index">
            <div class="form-row">
              <div class="col-md-12 table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Producto {{ index + 1 }}</th>
                      <th>Descripción</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <span class="badge badge-success">
                          {{ productsGroup[data.product_id]?.[0]?.name || 'No disponible' }}
                        </span>
                      </td>
                      <td>{{ data.description }}</td>
                      <td>{{ data.quantity }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-8 table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Recurso</th>
                      <th>Distribuidor</th>
                      <th>Costo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(itemRecurso, index2) in items[index]" :key="index2">
                      <td>
                        <input type="text" class="form-control" placeholder="Recurso" v-model="itemRecurso.resource">
                      </td>
                      <td>
                        <multiselect :options="suppliers" placeholder="Seleccione un distribuidor" :show-labels="false"
                          track-by="name" label="name" style="font-size: 13px" :value="itemRecurso.supplier"
                          @select="(option) => selectedSupplier(option, index, index2)" append-to-body>
                          <template slot="singleLabel" slot-scope="{ option }">
                            <span class="badge badge-pill badge-success">{{ option.name }}</span>
                          </template>
                        </multiselect>
                      </td>
                      <td>
                        <input type="number" class="form-control" placeholder="Costo" v-model="itemRecurso.cost" step="0.01">
                      </td>
                      <td>
                        <!-- Botón para eliminar la fila -->
                        <button class="btn btn-danger" @click.prevent="removeItem(index, index2)">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <button class="btn btn-primary d-flex align-items-center" @click.prevent="addItem(index)">
                  <i class="fas fa-plus me-2"></i>
                </button>
              </div>
              <div class="col-md-4 table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>P. Unitario</th>
                      <th>Precio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ unitPrice(index) }}</td>
                      <td>{{ totalPrice(index) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <hr>
          <button class="btn btn-primary" type="submit" :disabled="is_send_data">{{ text_button }}</button>
        </form>
      </validation-observer>
    </div>
  </div>
</template>
<script>
import AppFileDisplayer from '../../../components/AppFileDisplayer';
import Datepicker from '../../../components/AppDatepicker.vue';
import KeyValidator from '../../../components/KeyValidator';
import { Multiselect } from 'vue-multiselect';
import SearchEntity from "../../../components/SearchEntity.vue";

export default {

  components: {
    'app-file-displayer': AppFileDisplayer,
    'key-validator': KeyValidator,
    'multiselect': Multiselect,
    'search-entity': SearchEntity,
    Datepicker
  },
  data() {
    return {
      form: {},
      products: [],
      suppliers: [],
      productsGroup: [],
      suppliersGroup: [],
      items: [],
      text_button: 'Crear',
      is_send_data: false,
      myFile: undefined,
      fileManager: new FileManager(),
      files: [],
      person: {},
      myKey: []
    }
  },
  props: {
    status: {
      type: String,
      default: 'CREATE'
    },
    item: {
      type: Object
    },
  },
  methods: {
    selectedSupplier(option, index, index2) {
      console.log(option, index, index2);
      this.$set(this.items[index][index2], 'supplier', option);
      this.$set(this.items[index][index2], 'supplier_id', option.id);
    },
    addItem(index) {
      // Verifica si el índice correspondiente existe en `items`
      if (!this.items[index]) {
        this.$set(this.items, index, []); // Usa `$set` para asegurarte de que sea reactivo
      }
      // Agrega un nuevo recurso al array
      this.items[index].push({
        resource: '',
        supplier: null,
        supplier_id: null,
        cost: 0,
        pre_sale_report_id: null,
        id: null
      });
    },
    removeItem(index, resourceIndex) {
      // Verifica si el índice correspondiente existe en `items`
      if (this.items[index]) {
        // Elimina el recurso en el índice especificado
        this.items[index].splice(resourceIndex, 1);
      }
    },
    deleteEntity(entity) {
      this.form.client_id = null;
    },
    getEntity(entity) {
      this.form.client_id = entity.id;
    },
    getDeliveryDate(date) {
      this.form.delivery_date = date;
    },
    checkForm() {
      if (this.status === Constants.STATUS_EDIT) {
        this.sendEditData();
      } else {
        this.sendCreateData();
      }
    },
    resetForm() {
      this.form = {
        delivery_date: '',
        delivery_location: '',
        client_id: '',
        issue_date: '',
        user_id: 1,
        order_details: []
      }
      this.items = [];
      this.$refs['validation-observer'].reset();
    },
    async sendEditData() {

      var body = {
        "order_id": this.form.id,
        "details": []
      };

      for (const [indexForm, itemForm] of this.form.details.entries()) {
        body.details.push({
          "order_detail_id": itemForm.id,
          "details": this.items[indexForm]
        });
      }
      this.is_send_data = true
      try {
        // const body = { ...this.form }

        const result = await axios.post(`/api/pre-sale-report`, body).then(async (result) => {
          if (result.status === 201) {
            Alerts.showUpdatedMessage()
            this.resetForm()

            this.$router.push({ name: 'listorders' })
          }
        }).catch((err) => {
          if (err.response.data.code == "Error") {
            Alerts.showErrorMessageWithMessage(err.response.data.Message);
          }
          this.is_search = false;
        });
      } catch (e) {
        Alerts.showErrorMessage()
      }
      this.is_send_data = false
    },
    async sendCreateData() {
      var newItems = [];
      for (var item of this.items) {
        if (item.product_id) {
          newItems.push(item);
          continue;
        }
        item.product_id = item.product.id;
        newItems.push(item);
      }
      this.form.order_details = newItems;
      this.form.issue_date = this.form.delivery_date

      if (this.form.order_details.length == 0) {
        Alerts.showErrorMessageWithMessage("No existe ningun producto pedido");
        return false;
      }
      this.is_send_data = true
      try {
        const body = { ...this.form }

        const result = await axios.post('/api/order', { ...body });
        if (result.status === 201) {
          Alerts.showCreatedMessage()
          this.resetForm()
        }
      } catch (e) {
        Alerts.showErrorMessage()
      }
      this.is_send_data = false
    },
    validateStatus() {
      if (this.status === 'EDIT') {
        console.log(this.item.details,"DETAILS")
        this.form = { ...this.item }
        this.text_button = 'Actualizar'
      } else {
        if (this.$route.name === 'updatequoted' && this.item === undefined) {
          this.$router.push({ name: 'newquoted' })
        }
      }
    },
    async getProducts() {
      try {
        const result = await axios.get(`/api/product`);
        if (result.status == 200) {
          this.products = result.data;
          this.productsGroup = this.products.reduce((acc, product) => {
            if (!acc[product.id]) {
              acc[product.id] = []; // Crea un array vacío si aún no hay entradas para este ID
            }
            acc[product.id].push(product); // Agrega el producto al grupo correspondiente
            return acc;
          }, {});
        }
      } catch (e) {
        this.products = [];
      }
    },
    async getSuppliers() {
      try {
        const result = await axios.get(`/api/supplier`);
        if (result.status == 200) {
          this.suppliers = result.data;
          this.suppliersGroup = this.suppliers.reduce((acc, product) => {
            if (!acc[product.id]) {
              acc[product.id] = []; // Crea un array vacío si aún no hay entradas para este ID
            }
            acc[product.id].push(product); // Agrega el producto al grupo correspondiente
            return acc;
          }, {});
          console.log(this.suppliersGroup)
        }
      } catch (e) {
        this.suppliers = [];
      }
    }
  },
  async mounted() {
    await this.getProducts();
    await this.getSuppliers();
    this.validateStatus();
    this.items = this.item.details.map((itemDetail) => {
      return itemDetail.pre_sale_report && itemDetail.pre_sale_report.details
        ? [...itemDetail.pre_sale_report.details] // Clona los detalles existentes
        : []; // O inicializa como un array vacío
    });
  },
  watch: {
    status: function (status) {
      if (status === Constants.STATUS_CREATE && this.text_button === "Actualizar") {
        location.reload()
      }
    },
  },
  computed: {
    totalPrice() {
      return (index) => {
        if (!this.items[index]) return 0;

        // Suma los costos de todos los recursos
        const totalCost = this.items[index].reduce((sum, recurso) => {
          return sum + (parseFloat(recurso.cost) || 0); // Suma cada costo
        }, 0);

        // Calcula el precio con el 30% adicional
        return totalCost + (totalCost * 0.3);
      };
    },
    unitPrice() {
      return (index) => {
        const price = this.totalPrice(index); // Usa el precio calculado
        const quantity = this.item.details[index]?.quantity || 1; // Obtiene la cantidad del producto
        return (price / quantity).toFixed(2); // Devuelve el precio unitario con 2 decimales
      };
    },
    totalCost() {
      return this.item.details.reduce((acc, detail, index) => {
        const unitPrice = this.unitPrice(index); // Usa la función de cálculo de precio unitario
        return acc + parseFloat(unitPrice || 0);
      }, 0).toFixed(2); // Devuelve con 2 decimales
    },

    // Calcula la suma de todos los precios finales
    finalPrice() {
      return this.item.details.reduce((acc, detail, index) => {
        const price = this.totalPrice(index); // Usa la función de cálculo de precio
        return acc + parseFloat(price || 0);
      }, 0).toFixed(2); // Devuelve con 2 decimales
    }
  }
}
</script>
<style>
.table-responsive {
  position: relative;
  overflow: visible;
}
</style>