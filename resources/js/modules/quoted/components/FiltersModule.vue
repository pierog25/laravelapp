<template>
  <div class="row">
    <div class="col-sm-12">
      <validation-observer v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-3 mb-2">
              <label class="form-control-label" for="documento">N° Documento</label>
              <input type="text" v-model="search.document_number" class="form-control" id="documento" placeholder="Documento">
            </div>
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label class="form-control-label">Fecha Inicio</label>
                <datepicker :value="search.desde" @input="getDateStart"></datepicker>
              </div>
            </div>
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label class="form-control-label">Fecha Fin</label>
                <datepicker :value="search.hasta" @input="getDateEnd"></datepicker>
              </div>
            </div>
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label class="form-control-label text-white d-block">Buscar</label>
                <button class="btn btn-default" type="submit" :disabled="is_search">
                  <div v-if="is_search" class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <i v-else class="fa fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </form>
      </validation-observer>
    </div>
  </div>
</template>
<script>
import Datepicker from '../../../components/AppDatepicker.vue';
import { Multiselect } from 'vue-multiselect';
export default {
  components: {
    Datepicker,
    multiselect: Multiselect
  },
  data() {
    return {
      search: {
        desde: "",
        hasta: "",
        document_number: "",
        status:['Por Cotizar', 'Cotizado']
      },
      document: "",
    }
  },
  props: {
    is_search: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    checkForm() {
      this.$emit('search', this.search)
    },
    getDateStart(date) {
      this.search.desde = date;
    },
    getDateEnd(date) {
      this.search.hasta = date;
    }
  }
}
</script>