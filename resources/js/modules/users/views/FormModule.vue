<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Registro de Usuario</h3>
    </div>
    <div class="card-body">
      <validation-observer ref="validation-observer" v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-6 mb-2">
              <validation-provider name="NOMBRE" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="nombre">Nombre</label>
                <input type="text" v-model="form.name" class="form-control" id="nombre" placeholder="Nombre">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-6 mb-2">
              <validation-provider name="CORREO" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="correo">Correo</label>
                <input type="text" v-model="form.email" class="form-control" id="correo" placeholder="Correo">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-6 mb-2">
              <validation-provider name="CONTRASEÑA" rules="required" v-slot="{ errors }">
                <label class="form-control-label" for="password">Contraseña</label>
                <input type="password" v-model="form.password" class="form-control" id="password" placeholder="*****">
                <span class="is-invalid">{{ errors[0] }}</span>
              </validation-provider>
            </div>
            <div class="col-md-6 mb-2">
              <label class="form-control-label">Rol</label>
              <validation-provider name="ROL" rules="required" v-slot="{ errors }">
                <multiselect v-model="role_selected" :options="roles" @select="selectedTypeDocument"
                  placeholder="Seleccione un Rol" :show-labels="false" style="font-size: 13px"
                  track-by="name" label="name">
                  <template slot="singleLabel" slot-scope="{ option }"><span class="badge badge-pill badge-success">{{
                    option.name }}</span>
                  </template>
                </multiselect>
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
        name: '',
        email: '',
        password: "",
        role: ""
      },
      role_selected: {},
      text_button: 'Crear',
      is_send_data: false,
      myFile: undefined,
      fileManager: new FileManager(),
      files: [],
      person: {},
      myKey: [],
      roles: []

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
    selectedTypeDocument(role) {
      this.form.role = role.id;
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
        name: '',
        email: '',
        password: "",
        role: "",
      }
      this.$refs['validation-observer'].reset();
    },
    async sendEditData() {
      this.is_send_data = true
      try {
        const body = { ...this.form }

        const result = await axios.put(`/api/user/${body.id}`, { ...body }).then(async (result) => {
          if (result.status === 200) {
            Alerts.showUpdatedMessage()
            this.resetForm()

            this.$router.push({ name: 'listusers' })
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

        const result = await axios.post('/api/user', { ...body });
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
        this.text_button = 'Actualizar'
      } else {
        if (this.$route.name === 'updateusers' && this.item === undefined) {
          this.$router.push({ name: 'newusers' })
        }
      }
    },
    async getTypeDocuments() {
      try {
        const result = await axios.get(`/api/role`);
        if (result.status == 200) {
          this.roles = result.data;

          if (this.status === 'EDIT' && this.item?.roles) {
            const role = Array.isArray(this.item.roles) ? this.item.roles[0] : this.item.roles;

            const matchedRole = this.roles.find(r => r.id === role.id);
            if (matchedRole) {
              this.role_selected = matchedRole;
              this.form.role = matchedRole.id;
            }
          }
        }
      } catch (e) {
        this.roles = [];
      }
    }
  },
  mounted() {
    this.getTypeDocuments();
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