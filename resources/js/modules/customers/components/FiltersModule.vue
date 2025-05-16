<template>
  <div class="row">
    <div class="col-sm-12">
      <validation-observer v-slot="{ handleSubmit }">
      <form class="needs-validation" @submit.prevent="handleSubmit(checkForm)">
        <div class="form-row">
          <div class="col-md-3 mb-2">
            <div class="form-group">
              <label class="form-control-label" >Select</label>
              <multiselect
                  v-model="search.select_option"
                  :options="options_select"
                  placeholder="TODOS"
                  deselect-label="Deseleccionar">
                <template slot="singleLabel" slot-scope="{ option }"><span class="badge badge-pill badge-success">{{ option }}</span></template>
              </multiselect>
            </div>
          </div>
          <div class="col-md-3 mb-2">
            <div class="form-group">
              <label class="form-control-label" >Fecha Inicio</label>
              <datepicker :value="search.date_start" @input="getDateStart"></datepicker>
            </div>
          </div>
          <div class="col-md-3 mb-2">
            <div class="form-group">
              <label class="form-control-label" >Fecha Fin</label>
              <datepicker :value="search.date_end" @input="getDateEnd"></datepicker>
            </div>
          </div>
          <div class="col-md-3 mb-2">
            <div class="form-group">
              <label class="form-control-label text-white d-block" >Buscar</label>
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
import {Multiselect} from 'vue-multiselect';
export default {
  components: {
    Datepicker,
    multiselect: Multiselect
  },
  data(){
    return {
      search:{
        select_option: 'TODOS',
        date_start: Vue.moment().format('YYYY-MM-DD'),
        date_end: Vue.moment().format('YYYY-MM-DD'),
      },
      options_select:[
          'TODOS'
      ]
    }
  },
  props:{
    is_search: {
      type: Boolean,
      default: false
    }
  },
  methods:{
    checkForm(){
      this.$emit('search',this.search)
    },
    getDateStart(date){
      this.search.date_start = date;
    },
    getDateEnd(date){
      this.search.date_end = date;
    }
  }
}
</script>