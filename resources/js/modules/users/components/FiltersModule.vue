<template>
  <div class="row">
    <div class="col-sm-12">
      <validation-observer v-slot="{ handleSubmit }">
        <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="correo">Correo</label>
              <input type="email" v-model="email" class="form-control" id="correo" placeholder="Correo">
            </div>
            <div class="col-md-4 mb-2">
              <label class="form-control-label" for="nombre">Nombre</label>
              <input type="text" v-model="name" class="form-control" id="nombre" placeholder="Nombre">
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
      name: "",
      email: ""
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
      if (this.name) {
        this.search.name = this.name;
      }
      if (this.email) {
        this.search.email = this.email;
      }
      this.$emit('search', this.search)
    }
  }
}
</script>