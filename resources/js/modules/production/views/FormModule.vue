<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Registro del nuevo pedido</h3>
    </div>
    <div class="card-body">
      <validation-observer ref="validation-observer" v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-12 mb-2">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tr>
                    <td><strong>Cliente:</strong></td>
                    <td>
                      {{ item && item.client ? item.client.first_name + " " + item.client.last_name : '' }}
                    </td>
                  </tr>
                  <tr>
                    <td><strong>Correo:</strong></td>
                    <td>{{ item && item.client ? item.client.email : '' }}</td>
                  </tr>
                  <tr>
                    <td><strong>Teléfono:</strong></td>
                    <td>{{ item && item.client ? item.client.phone : '' }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <!--          <div v-for="(data, index) in item.details" :key="index">-->
          <div v-for="index in [currentPage]" :key="index">
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
                         {{ productsGroup[item.details[index].product_id]?.[0]?.name || 'No disponible' }}
                        </span>
                    </td>
                    <td>{{ item.details[index].description }}</td>
                    <td>{{ item.details[index].quantity }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-row" v-for="(itemRecurso, index2) in items[index]" :key="index2">
              <div class="col-12 col-md-4 table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th>Recurso</th>
                    <th>Distribuidor</th>
                    <th>Costo</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td class="td-resource" rowspan="3">
                          <span class="badge badge-success">
                            {{ itemRecurso.resource }}
                          </span>
                    </td>
                    <td class="td-resource">
                          <span class="badge badge-success">
                            {{ itemRecurso.supplier.name }}
                          </span>
                    </td>
                    <td>
                      <strong>{{ itemRecurso.cost }}</strong>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="1">
                      <strong>Fecha de entrega</strong>
                      <div>
                        <input type="date" class="form-control form-control-sm mt-1" v-model="itemRecurso.date_delivery" :disabled="itemRecurso.completed == 1"/>
                      </div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-12 col-md-8 table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th class="align-middle">Costo final</th>
                    <th class="text-center">Opciones comprobante</th>
                    <th class="align-middle">Comentario de costos adicionales e incidentales</th>
                    <th class="align-middle">Comentario respecto a la calidad</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>
                      <input type="number" class="form-control form-control-sm" placeholder="Costo final" v-model="itemRecurso.cost_final" :disabled="itemRecurso.completed == 1"/>
                      <div>
                        <strong>Costos Adicionales</strong>
                        <input type="number" class="form-control form-control-sm mt-1"
                               placeholder="Costos adicionales" v-model="itemRecurso.cost_additional" :disabled="itemRecurso.completed == 1"/>
                      </div>
                    </td>
                    <td class="text-center align-middle">
                      <div>
                        <button class="btn btn-link p-0" title="Subir comprobante" @click.prevent="changeImage(index, index2)" >
                          <i class="fas fa-upload"></i>
                        </button>
                        <input type="file" name="file" id="file-input" style="display: none" @change="changePhotoProfile">
                        <button class="btn btn-link p-0" title="Ver comprobante" @click.prevent="showImg(itemRecurso)">
                          <i class="fas fa-file-alt"></i>
                        </button>
                      </div>
                      <strong>Fecha de Recepcion</strong>
                      <div>
                        <input type="date" class="form-control form-control-sm mt-1" v-model="itemRecurso.date_receipt" :disabled="itemRecurso.completed == 1"/>
                      </div>
                    </td>
                    <!-- Comentario costos -->
                    <td>
                      <textarea class="form-control form-control-sm" placeholder="Comentario (Costos)" v-model="itemRecurso.comments_cost" :disabled="itemRecurso.completed == 1"></textarea>
                      <div>
                        <button class="btn btn-danger btn-block" @click.prevent="openQualityModal(itemRecurso, index2, index)" :disabled="itemRecurso.completed == 1">Evaluación de calidad</button>
                      </div>
                    </td>
                    <td>
                      <textarea class="form-control form-control-sm" placeholder="Comentario calidad" v-model="itemRecurso.comment_quality" :disabled="itemRecurso.completed == 1"></textarea>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-end my-3" style="gap: 16px;" v-if="itemRecurso.completed != 1">
                <button class="btn btn-primary" style="min-width:130px;" @click.prevent="saveRow(itemRecurso)" :disabled="itemRecurso.completed == 1">Guardar</button>
                <button class="btn btn-primary" style="min-width:130px;" @click.prevent="completedRow(index, index2, itemRecurso)" :disabled="itemRecurso.completed == 1">Completado</button>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center my-3">
            <nav>
              <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 0 }">
                  <button class="page-link" @click.prevent="prevPage">&lt;</button>
                </li>
                <li
                    v-for="n in item.details.length"
                    :key="n"
                    class="page-item"
                    :class="{ active: n - 1 === currentPage }"
                >
                  <button class="page-link" @click.prevent="goToPage(n - 1)">{{ n }}</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === item.details.length - 1 }">
                  <button class="page-link" @click.prevent="nextPage">&gt;</button>
                </li>
              </ul>
            </nav>
          </div>
          <QualityEvaluationModal
              ref="modalQualityEvaluationModal"
              @saveEvaluation="saveEvaluation"
          />
          <hr>
          <button class="btn btn-primary" type="submit" :disabled="is_send_data">Pedido Entregado</button>
        </form>
      </validation-observer>
    </div>
  </div>
</template>
<script>
import AppFileDisplayer from '../../../components/AppFileDisplayer';
import Datepicker from '../../../components/AppDatepicker.vue';
import KeyValidator from '../../../components/KeyValidator';
import {Multiselect} from 'vue-multiselect';
import SearchEntity from "../../../components/SearchEntity.vue";
import QualityEvaluationModal from '../components/QualityEvaluationModal.vue'

export default {

  components: {
    'app-file-displayer': AppFileDisplayer,
    'key-validator': KeyValidator,
    'multiselect': Multiselect,
    'search-entity': SearchEntity,
    Datepicker,
    QualityEvaluationModal
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
      myKey: [],
      currentPage: 0,
      showQualityModal: false,
      modalData: {},
      activeItemIndex: null,
      activeSubIndex: null
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
    async saveRow(item){
      try {
        item.client = this.item.client.first_name + " " + this.item.client.last_name;
        item.client_email = this.item.client.email;
        item.order = this.item.id;
        const response = await axios.post('/api/save-row', item);
        if(response.status !== 200) {
          Alerts.showMessage("Error","Ocurrió un error al guardar la fila","error");
          return false;
        }
        const data = response.data;
        if(data.success){
          Alerts.showMessage("Guardado",data.msg,"success");
        }else{
          Alerts.showMessage("Error",data.msg,"error");
        }
      } catch (error) {
        console.error('Error al subir el archivo:', error);
      }
    },
    async completedRow(index, index2, item){
      try {
        const response = await axios.post('/api/completed', item);
        if(response.status !== 200) {
          Alerts.showMessage("Error","Ocurrió un error al guardar la fila","error");
          return false;
        }
        const data = response.data;
        if(data.success){
          this.items[index][index2].completed = 1;
          Alerts.showMessage("Guardado",data.msg,"success");
        }else{
          Alerts.showMessage("Error",data.msg,"error");
        }
      } catch (error) {
        console.error('Error al subir el archivo:', error);
      }
    },
    saveEvaluation(object){
      this.items[object.activeItemIndex][object.activeSubIndex].material_quality = object.material_quality;
      this.items[object.activeItemIndex][object.activeSubIndex].material_relation = object.material_relation;
      this.items[object.activeItemIndex][object.activeSubIndex].service_qty_defective = object.service_qty_defective;
      this.items[object.activeItemIndex][object.activeSubIndex].service_qty_total = object.service_qty_total;
    },
    changeImage(index, index2) {
      this.activeItemIndex = index;
      this.activeSubIndex = index2;
      $('#file-input').trigger('click');
    },
    async changePhotoProfile(event){
      const files = event.target.files
      if(files[0]){
        this.files = files
        const formData = new FormData();
        formData.append('files', files[0]);
        try {
          const response = await axios.post('/file', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
          const imagePath = response.data.url;
          this.items[this.activeItemIndex][this.activeSubIndex].img = imagePath;
          this.activeItemIndex = null;
          this.activeSubIndex = null;
          console.log('Archivo subido exitosamente:', response.data);
        } catch (error) {
          console.error('Error al subir el archivo:', error);
        }
      }
    },
    showImg(item){
      if(!item.img){
        Alerts.showErrorMessageWithMessage("Espere un momento, no se ha cargado la imagen");
        return false;
      }
      window.open("view-file/"+item.img,"_blank");
    },
    openQualityModal(item, idx2, idx) {
      this.$refs['modalQualityEvaluationModal'].openModal(item, idx2, idx);
    },
    onQualityEvaluationSave(evaluation) {
      // Guarda la evaluación en tu array de items o donde corresponda
      // Ejemplo:
      if (this.currentQualityIndex !== undefined) {
        this.items[this.currentQualityIndex].evaluacionCalidad = evaluation;
      }
    },
    nextPage() {
      if (this.currentPage < this.item.details.length - 1) this.currentPage++;
    },
    prevPage() {
      if (this.currentPage > 0) this.currentPage--;
    },
    goToPage(page) {
      this.currentPage = page;
    },
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
    async checkForm() {
      console.log(this.form, "FORM");
      try {
        const response = await axios.post('/api/delivery', this.form);
        if(response.status !== 200) {
          Alerts.showMessage("Error","Ocurrió un error al entrega","error");
          return false;
        }
        const data = response.data;
        if(data.success){
          Alerts.showMessage("Guardado",data.msg,"success");
        }else{
          Alerts.showMessage("Error",data.msg,"error");
        }
      } catch (error) {
        console.error('Error al subir el archivo:', error);
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

            this.$router.push({name: 'listproduction'})
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
        const body = {...this.form}

        const result = await axios.post('/api/order', {...body});
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
        this.form = {...this.item}
        this.text_button = 'Actualizar'
      } else {
        if (this.$route.name === 'updateproduction' && this.item === undefined) {
          this.$router.push({name: 'listproduction'})
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
    this.validateStatus();
    await this.getProducts();
    await this.getSuppliers();
    if (this.item && this.item.details) {
      this.items = this.item.details.map((itemDetail) => {
        return itemDetail.pre_sale_report && itemDetail.pre_sale_report.details
            ? [...itemDetail.pre_sale_report.details] // Clona los detalles existentes
            : []; // O inicializa como un array vacío
      });
    }
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
      if (this.item && this.item.details) {
        return this.item.details.reduce((acc, detail, index) => {
          const unitPrice = this.unitPrice(index); // Usa la función de cálculo de precio unitario
          return acc + parseFloat(unitPrice || 0);
        }, 0).toFixed(2);
      }// Devuelve con 2 decimales
    },

    // Calcula la suma de todos los precios finales
    finalPrice() {
      if (this.item && this.item.details) {
        return this.item.details.reduce((acc, detail, index) => {
          const price = this.totalPrice(index); // Usa la función de cálculo de precio
          return acc + parseFloat(price || 0);
        }, 0).toFixed(2); // Devuelve con 2 decimales
      }
    }
  }
}
</script>
<style>
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  width: 100%;
}
.td-resource span {
  display: block; /* Opcional para que el badge se adapte verticalmente */
  max-width: 220px;
  word-break: break-word;
  white-space: normal;
}
</style>