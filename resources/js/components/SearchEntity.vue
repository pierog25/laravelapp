<template>
  <div>
    <div v-if="validate">
      <validation-provider :name="title" rules="required" v-slot="{ errors }">
        <div class="input-group">
          <input id="search-entity-validate" type="text" class="form-control" v-model="form.entity">
          <div class="input-group-append">
            <button type="button" class="btn btn-danger" @click="deleteEntity" v-if="entity.exist"><i
                class="fas fa-times"></i></button>
            <button type="button" class="btn  btn-warning" @click="createEntity" v-if="not_registered"><i
                class="fas fa-plus" /></button>
            <button type="button" class="btn btn-primary" @click="checkeForm" :disabled="entity.is_search"><i
                class="fas fa-search"></i></button>
          </div>
        </div>
        <span class="is-invalid">{{ errors[0] }}</span>
      </validation-provider>
    </div>
    <div class="input-group" v-else>
      <input id="search-entity" type="text" class="form-control" v-model="form.entity">
      <div class="input-group-append">
        <button type="button" class="btn btn-danger" @click="deleteEntity" v-if="entity.exist"><i
            class="fas fa-times"></i></button>
        <button type="button" class="btn  btn-warning" @click="createEntity" v-if="not_registered"><i
            class="fas fa-plus" /></button>
        <button type="button" class="btn btn-primary" @click="checkeForm" :disabled="entity.is_search"><i
            class="fas fa-search"></i></button>
      </div>
    </div>
    <div class="card mt-3" v-if="entity.exist">
      <div class="card-body">
        <div class="form-row">
          <div class="col-md-4 col-sm-12">
            <strong>Tipo documento: </strong>{{ entity.document_type.name }}
          </div>
          <div class="col-md-4 col-sm-12">
            <strong>Nº documento: </strong>{{ entity.document_number }}
          </div>
          <div class="col-md-4 col-sm-12">
            <strong>Nombre: </strong>{{ entity.first_name+" "+entity.last_name }}
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-create-entity-customer" tabindex="-1" role="dialog"
      aria-labelledby="modal-form" aria-modal="true">
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body p-0" v-if="not_registered">
            <FormPerson
              v-if="this.form.entity.length === 8 || this.form.entity.length === 9 || this.form.entity.length === 12"
              ref="person-search" @sendForm="sendFormPerson"></FormPerson>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import FormPerson from "../modules/customers/views/FormModule.vue"

export default {
  name: "search-entity",
  components: {
    FormPerson,
  },
  data() {
    return {
      form: {
        entity: ''
      },
      entity: {
        type_document: {},
        exist: false,
        is_search: false,
      },
      not_registered: false
    }
  },
  props: {
    validate: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'ENTIDAD'
    }
  },
  methods: {
    sendFormPerson() {
      $('#modal-create-entity-customer').modal('hide');
      this.searchEntity('/api/client')
    },
    sendFormCompany() {
      $('#modal-create-entity-customer').modal('hide');
      this.searchEntity('/api/client')
    },
    setEntity(document) {
      this.form.entity = document

      if (this.form.entity.length === 8 || this.form.entity.length === 9 || this.form.entity.length === 12) {
        this.searchEntity('/api/client/')
      } else if (this.form.entity.length === 11) {
        this.searchEntity('/api/client/')
      }
    },
    async setPersonByID(id) {
      await this.searchEntity(`/api/client`)
    },
    async setCompanyByID(id) {
      await this.searchEntity(`/api/client`)

    },
    resetEntity() {
      this.entity = {
        type_document: {},
        exist: false,
        is_search: false,
      }
    },
    checkeForm() {
      let url = '';
      if (this.title === 'PERSONA') {
        if (this.form.entity.length !== 8 && this.form.entity.length !== 9 && this.form.entity.length !== 12) {
          Alerts.showToastErrorMessage('El número de documeto debe ser de 8, 9 o 12 dígitos', 'center')
          return;
        }
        url = '/api/client';
      } else if (this.title === 'EMPRESA') {
        if (this.form.entity.length !== 11) {
          Alerts.showToastErrorMessage('El número de documeto debe ser de 11 dígitos', 'center')
          return;
        }
        url = '/api/client';
      } else {
        if (this.form.entity.length === 8 || this.form.entity.length === 9 || this.form.entity.length === 12) {
          url = '/api/client';
        } else if (this.form.entity.length === 11) {
          url = '/api/client';
        } else {
          Alerts.showToastErrorMessage('El número de documeto debe ser de 8, 9, 11 o 12 dígitos', 'center')
          return;
        }
      }
      this.searchEntity(url)
    },
    async searchEntity(url) {
      this.entity.exist = false
      this.entity.is_search = true

      // if(this.form.entity!=''){
      //   url+= '/'+this.form.entity
      // }
      try {
        const response = await axios.get(`${url}`, {
          params: {
            "document": this.form.entity
          }
        }).then(response => {

          if (response.status === 200) {
            if (response.data.length > 0) {
              this.entity = response.data.length > 0 ? response.data[0] : null;
              this.$emit('entity', { ...this.entity })
              this.entity.exist = true
              this.entity.is_search = false
              this.not_registered = false
            } else {
              this.entity.is_search = false
              this.not_registered = true
            }
            // const responseData = response.data
            // if (responseData.code === 'Success') {
            //   Alerts.showToastMessage(responseData.Message, 'center')
            //   this.entity = count(responseData)

            //   this.$emit('entity', {...this.entity})

            //   this.entity.exist = true
            //   this.entity.is_search = false
            //   this.not_registered = false

            //   this.form.entity = responseData.data.document?responseData.data.document:responseData.data.number_document

            // } else if (responseData.code === 'Error') {
            //   this.not_registered = true
            // }
          }
        }).catch((err) => {
          this.entity.is_search = false
          if (err.response.data.code === 'Error') {
            this.not_registered = true
            this.resetEntity()
            Alerts.showToastErrorMessage(err.response.data.Message, 'center')
          }
        });

      } catch (e) {
      }
    },
    deleteEntity() {
      this.resetEntity()
      this.form.entity = ''
      this.not_registered = false
      this.$emit('deleteEntity', { ...this.entity })
    },
    createEntity() {
      console.log(this.form.entity);
      console.log(this.title);
      // if (this.form.entity.length == 11) {
      //   this.$refs['company-search'].entitySearch(this.form.entity)
      //   $('#modal-create-entity-' + this.title).modal('show');
      // } else {
      //   this.$refs['person-search'].entitySearch(this.form.entity)
      //   $('#modal-create-entity-' + this.title).modal('show');
      // }
      this.$refs['person-search'].entitySearch(this.form.entity)
      $('#modal-create-entity-customer').modal('show');

    }
  },
  mounted() {

  },
  watch: {
    'form.entity': function (document) {
      let regex = /[^0-9]/gi;
      this.form.entity = document.replace(regex, "");

      if (document.length === 8) {

      } else if (document.length === 9) {

      } else {
        this.not_registered = false
      }
    }
  }
}
</script>

<style scoped>
.modal-xl {
  max-width: 800px;
}

@media (min-width: 1200px) {
  .modal-xl {
    max-width: 1140px;
  }
}
</style>