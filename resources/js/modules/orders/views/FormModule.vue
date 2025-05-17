<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Registro del nuevo pedido</h3>
    </div>
    <div class="card-body">
      <validation-observer ref="validation-observer" v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="form-control-label">Cliente</label>
              <search-entity ref="search-entity" :title="'CLIENTE'" :validate="true" @entity="getEntity"
                @deleteEntity="deleteEntity"></search-entity>
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-control-label">Fecha de Entrega</label>
              <datepicker :value="form.delivery_date" :validate="true" @input="getDeliveryDate"></datepicker>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="LUGAR DE ENTREGA" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="lugar_entrega">Lugar de Entrega</label>
                <input type="text" v-model="form.delivery_location" class="form-control" id="lugar_entrega"
                  placeholder="LUGAR DE ENTREGA">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>
          <div class="form-row">
            <div class="table-responsive mt-3">
              <table class="table">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in items" :key="index">
                    <td>
                      <multiselect v-model="item.product" :options="products" placeholder="Seleccione un Producto"
                        :show-labels="false" track-by="name" label="name" style="font-size: 13px"
                        @select="selectedProduct" append-to-body>
                        <template slot="singleLabel" slot-scope="{ option }">
                          <span class="badge badge-pill badge-success">{{ option.name }}</span>
                        </template>
                      </multiselect>
                    </td>
                    <td>
                      <input type="text" v-model="item.description" class="form-control" placeholder="Descripción">
                    </td>
                    <td>
                      <input type="number" v-model.number="item.quantity" class="form-control" placeholder="Cantidad">
                    </td>
                    <td>
                      <button class="btn btn-danger" @click.prevent="removeItem(index)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <button class="btn btn-primary d-flex align-items-center" @click.prevent="addItem">
                <i class="fas fa-plus me-2"></i> Producto
              </button>
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
      form: {
        delivery_date: '',
        delivery_location: '',
        client_id: '',
        issue_date: '',
        user_id: 1,
        order_details: []
      },
      products: [],
      productsGroup: [],
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
    selectedProduct(product) {
      console.log(product, "product");
    },
    addItem() {
      this.items.push({ product: '', product_id: "", description: '', quantity: 1 });
    },
    removeItem(index) {
      this.items.splice(index, 1);
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

        const result = await axios.put(`/api/order/${body.id}`, { ...body }).then(async (result) => {
          if (result.status === 200) {
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
        const newDetails = [];
        for (var detail of this.item.order_details) {
          detail.product = this.productsGroup[detail.product_id]
          newDetails.push(detail);
        }
        this.items = newDetails;
        // this.getEntity(this.item.client)
        this.$refs["search-entity"].setEntity(this.item.client.document_number)
        this.form = { ...this.item }
        this.text_button = 'Actualizar'
      } else {
        if (this.$route.name === 'updatemodule' && this.item === undefined) {
          this.$router.push({ name: 'neworders' })
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
        this.typeDocuments = [];
      }
    }
  },
  async mounted() {
    await this.getProducts();
    this.validateStatus();
  },
  watch: {
    status: function (status) {
      if (status === Constants.STATUS_CREATE && this.text_button === "Actualizar") {
        location.reload()
      }
    },
  }
}
</script>
<style>
.table-responsive {
  position: relative;
  overflow: visible;
}
</style>