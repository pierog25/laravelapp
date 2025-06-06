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
              <validation-provider name="NOMBRES" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="nombres">Nombres</label>
                <input type="text" v-model="form.first_name" class="form-control" id="nombres" placeholder="Nombres">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="APELLIDOS" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="apellidos">Apellidos</label>
                <input type="text" v-model="form.last_name" class="form-control" id="apellidos" placeholder="Apellidos">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="TELEFONO" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="telefono">Telefono</label>
                <input type="text" v-model="form.phone" class="form-control" id="telefono" placeholder="TELEFONO">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="form-control-label">Tipo de Documento</label>
              <validation-provider name="Tipo Documento" rules="required" v-slot="{ errors }">
                <multiselect v-model="document_selected" :options="typeDocuments" @select="selectedTypeDocument"
                  placeholder="Seleccione un Tipo Documento" :show-labels="false" style="font-size: 13px"
                  track-by="name" label="name">
                  <template slot="singleLabel" slot-scope="{ option }"><span class="badge badge-pill badge-success">{{
                    option.name }}</span>
                  </template>
                </multiselect>
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="DOCUMENTO" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="documento">N° Documento</label>
                <input type="text" v-model="form.document_number" class="form-control" id="documento"
                  placeholder="Documento">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="CORREO" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="correo">Correo</label>
                <input type="text" v-model="form.email" class="form-control" id="correo" placeholder="Correo">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-12 mb-2">
              <validation-provider name="DIRECCION" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="direccion">Dirección</label>
                <input type="text" v-model="form.address" class="form-control" id="direccion" placeholder="Dirección">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
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
        first_name: '',
        last_name: '',
        id_document_type: "",
        document_number: "",
        phone: "",
        email: "",
        address: ""
      },
      document_selected: {},
      text_button: 'Crear',
      is_send_data: false,
      myFile: undefined,
      fileManager: new FileManager(),
      files: [],
      person: {},
      myKey: [],
      typeDocuments: []

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
    entitySearch(entity) {
      console.log(entity.length)
      if (entity.length === 8) {
        this.document_selected = {
          "id": 1,
          "name": "DNI"
        }
      }
      if (entity.length === 11) {
        this.document_selected = {
          "id": 3,
          "name": "RUC"
        }
      }
      if (entity.length === 12) {
        this.document_selected = {
          "id": 2,
          "name": "Pasaporte"
        }
      }
      this.selectedTypeDocument(this.document_selected)
      this.form.document = entity
    },
    selectedTypeDocument(typeDocument) {
      console.log(typeDocument, "typeDocument");
      this.form.id_document_type = typeDocument.id;
    },
    getEntity(entity) {
      this.person = entity
      this.form.name = entity.name
      this.form.email = entity.email
      this.form.person_id = entity.id
    },
    getImage(event) {
      this.myFile = event.target.files[0];
      this.fileManager = FileManager.fromFile((this.myFile));
    },
    deleteImage() {
      this.fileManager = new FileManager()
      this.files = []
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
        first_name: '',
        last_name: '',
        id_document_type: "",
        document_number: "",
        phone: "",
        email: "",
        address: ""
      }
      this.$refs['validation-observer'].reset();
    },
    async sendEditData() {
      this.is_send_data = true
      try {
        const body = { ...this.form }

        const result = await axios.put(`/api/client/${body.id}`, { ...body }).then(async (result) => {
          if (result.status === 200) {
            Alerts.showUpdatedMessage()
            this.resetForm()

            this.$router.push({ name: 'listcustomers' })
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
      this.is_send_data = true
      try {
        const body = { ...this.form }

        const result = await axios.post('/api/client', { ...body });
        console.log(result, "result");
        if (result.status === 201) {
          Alerts.showCreatedMessage()
          this.resetForm()
          this.$emit('sendForm');
        }
      } catch (e) {
        Alerts.showErrorMessage()
      }
      this.is_send_data = false
    },
    validateStatus() {
      if (this.status === 'EDIT') {
        this.form = { ...this.item }
        this.document_selected = this.form.document_type
        this.text_button = 'Actualizar'
      } else {
        if (this.$route.name === 'updatecustomers' && this.item === undefined) {
          this.$router.push({ name: 'newcustomers' })
        }
      }
    },
    async getTypeDocuments() {
      try {
        const result = await axios.get(`/api/document-type`);
        if (result.status == 200) {
          this.typeDocuments = result.data;
        }
        console.log(result, "result")
      } catch (e) {
        this.typeDocuments = [];
      }
    }
  },
  mounted() {
    this.validateStatus();
    this.getTypeDocuments();
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