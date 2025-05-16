<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Registro del nuevo cliente</h3>
    </div>
    <div class="card-body">
      <validation-observer ref="validation-observer" v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-12 mb-2">
              <label class="form-control-label">Cliente</label>
              <search-entity ref="search-entity" :title="'CLIENTE'" :validate="true" @entity="getEntity"
                @deleteEntity="deleteEntity"></search-entity>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="APELLIDO PATERNO" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="apellido_paterno">Apellido Paterno</label>
                <input type="text" v-model="form.paternal_surname" class="form-control" id="apellido_paterno"
                  placeholder="Apellido Paterno">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-4 mb-2">
              <validation-provider name="APELLIDO MATERNO" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="apellido_materno">Apellido Materno</label>
                <input type="text" v-model="form.maternal_surname" class="form-control" id="apellido_materno"
                  placeholder="Apellido Materno">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="form-control-label">Tipo de Documento</label>
              <validation-provider name="Tipo Documento" rules="required" v-slot="{ errors }">
                <multiselect v-model="form.type_document" :options="typeDocuments" @select="selectedTypeDocument"
                  placeholder="Seleccione un Tipo Documento" :show-labels="false" style="font-size: 13px"
                  track-by="abbreviation" label="abbreviation">
                  <template slot="singleLabel" slot-scope="{ option }"><span class="badge badge-pill badge-success">{{
                    option.abbreviation }}</span>
                  </template>
                </multiselect>
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
            <div class="col-md-4 mb-2">
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
import SearchEntity from "../../../components/SearchEntity.vue";

export default {

  components: {
    'app-file-displayer': AppFileDisplayer,
    'key-validator': KeyValidator,
    'multiselect': Multiselect,
    'search-entity': SearchEntity,
  },
  data() {
    return {
      form: {
        names: '',
        maternal_surname: '',
        paternal_surname: '',
        type_document: "",
        document: "",
        email: "",
        address: ""
      },
      text_button: 'Crear',
      is_send_data: false,
      myFile: undefined,
      fileManager: new FileManager(),
      files: [],
      person: {},
      myKey: [],
      typeDocuments: [
        {
          "id": 1,
          "name": "Documento Nacional de Identidad",
          "abbreviation": "DNI"
        }
      ]

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
    deleteEntity(entity){

    },
    getEntity(entity){

    },
    selectedTypeDocument(typeDocument) {
      this.form.type_document = typeDocument.id;
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
        value1: '',
        value2: '',
      }
      this.$refs['validation-observer'].reset();
    },
    async sendEditData() {
      this.is_send_data = true
      try {
        const body = { ...this.form }

        const result = await axios.put(`/url-module/${body.id}`, { ...body }).then(async (result) => {
          if (result.status === 200) {
            Alerts.showUpdatedMessage()
            this.resetForm()

            this.$router.push({ name: 'listmodule' })
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

        const result = await axios.post('url-module', { ...body });
        if (result.status === 200) {
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
        this.form = { ...this.item }
        this.text_button = 'Actualizar'
      } else {
        if (this.$route.name === 'updatemodule' && this.item === undefined) {
          this.$router.push({ name: 'newmodule' })
        }
      }
    }
  },
  mounted() {
    this.validateStatus()
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