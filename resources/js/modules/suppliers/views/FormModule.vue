<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Registro de Distribuidor</h3>
    </div>
    <div class="card-body">
      <validation-observer ref="validation-observer" v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-6 mb-2">
              <validation-provider name="Nombre" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="nombre">Nombre</label>
                <input type="text" v-model="form.name" class="form-control" id="nombre" placeholder="Nombre">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-6 mb-2">
              <validation-provider name="RUC" rules="required|numeric" v-slot="{ errors }">
                <label class="form-control-label" for="ruc">RUC</label>
                <input type="text" v-model="form.ruc" class="form-control" id="ruc" placeholder="RUC">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-6 mb-2">
              <validation-provider name="Correo" rules="required|email" v-slot="{ errors }">
                <label class="form-control-label" for="email">Correo</label>
                <input type="email" v-model="form.email" class="form-control" id="email" placeholder="Correo">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-6 mb-2">
              <validation-provider name="Dirección" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="address">Dirección</label>
                <input type="text" v-model="form.address" class="form-control" id="address" placeholder="Dirección">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="cellphone_1">Teléfono 1</label>
              <input type="text" v-model="form.first_phone" class="form-control" id="cellphone_1" placeholder="Teléfono 1">
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="cellphone_2">Teléfono 2</label>
              <input type="text" v-model="form.second_phone" class="form-control" id="cellphone_2" placeholder="Teléfono 2">
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="cellphone_3">Teléfono 3</label>
              <input type="text" v-model="form.third_phone" class="form-control" id="cellphone_3" placeholder="Teléfono 3">
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-6 mb-4">
              <validation-provider name="Tipo de Proveedor" rules="required" v-slot="{ errors }">
                <label class="form-control-label">Tipo de Distribuidor</label>
                <multiselect
                    v-model="type_selected"
                    :options="type_options"
                    placeholder="Seleccione un tipo"
                    :show-labels="false"
                    track-by="value"
                    label="name"
                    @input="updateSelectedType"
                    style="font-size: 13px"
                >
                  <template slot="singleLabel" slot-scope="{ option }">
                    <span class="badge badge-pill badge-info">{{ option.name }}</span>
                  </template>
                </multiselect>
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>
          <button class="btn btn-primary" type="submit" :disabled="is_send_data">{{ text_button }}</button>
        </form>
      </validation-observer>
    </div>
  </div>
</template>

<script>
import AppFileDisplayer from '../../../components/AppFileDisplayer';
import KeyValidator from '../../../components/KeyValidator';
import { Multiselect } from 'vue-multiselect';

export default {
  components: {
    'app-file-displayer': AppFileDisplayer,
    'key-validator': KeyValidator,
    'multiselect': Multiselect
  },
  data() {
    return {
      form: {
        name: '',
        ruc: '',
        email: '',
        address: '',
        first_phone: '',
        second_phone: '',
        third_phone: '',
        type: ''
      },
      type_selected: null,
      text_button: 'Crear',
      type_options: [
        { name: 'Materiales', value: 'Materiales' },
        { name: 'Servicios', value: 'Servicios' }
      ],
      is_send_data: false,
    };
  },
  props: {
    status: {
      type: String,
      default: 'CREATE'
    },
    item: {
      type: Object
    }
  },
  methods: {
    updateSelectedType(option) {
      this.form.type = option ? option.value : '';
    },
    checkForm() {
      if (this.status === 'EDIT') {
        this.sendEditData();
      } else {
        this.sendCreateData();
      }
    },
    resetForm() {
      this.form = {
        name: '',
        ruc: '',
        email: '',
        address: '',
        first_phone: '',
        second_phone: '',
        third_phone: '',
        type: ''
      };
      this.$refs['validation-observer'].reset();
    },
    async sendCreateData() {
      this.is_send_data = true;
      try {
        const result = await axios.post('/api/supplier', { ...this.form });
        if (result.status === 201) {
          Alerts.showCreatedMessage();
          this.resetForm();
          this.$emit('sendForm');
        }
      } catch (e) {
        Alerts.showErrorMessage();
      }
      this.is_send_data = false;
    },
    async sendEditData() {
      this.is_send_data = true;
      try {
        const result = await axios.put(`/api/supplier/${this.form.id}`, { ...this.form });
        if (result.status === 200) {
          Alerts.showUpdatedMessage();
          this.resetForm();
          this.$router.push({ name: 'listsuppliers' });
        }
      } catch (e) {
        Alerts.showErrorMessage();
      }
      this.is_send_data = false;
    },
    validateStatus() {
      if (this.status === 'EDIT') {
        this.form = { ...this.item };
        this.type_selected = this.type_options.find(opt => opt.value === this.form.type);
        this.text_button = 'Actualizar';
      } else {
        if (this.$route.name === 'updatesuppliers' && !this.item) {
          this.$router.push({ name: 'newsuppliers' });
        }
      }
    }
  },
  mounted() {
    this.validateStatus();
  }
}
</script>