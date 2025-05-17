<template>
  <div class="row">
    <div class="col-sm-12">
      <validation-observer v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="documento">N° Documento</label>
              <input type="text" v-model="document" class="form-control" id="documento" placeholder="Documento">
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="nombres">Nombres</label>
              <input type="text" v-model="names" class="form-control" id="nombres" placeholder="Nombres">
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
      search: {},
      names: "",
      document: ""
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
      this.search = {};
      if (this.names) {
        this.search.name = this.names;
      }
      if (this.document) {
        this.search.document = this.document;
      }
      this.$emit('search', this.search)
    }
  }
}
</script>