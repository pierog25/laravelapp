<template>
  <div v-if="validate">
    <validation-provider :name="'title'" rules="required" v-slot="{ errors }">
      <vue-timepicker
          input-width="100%"
          v-model="time"
          :input-class="'form-control'"
          :language="es"
          :bootstrap-styling="true"
          :readonly="false"
          :placeholder="placeholder"
          :required="true"
          :format="'HH:mm'"
      ></vue-timepicker>
      <span class="is-invalid">{{ errors[0] }}</span><!--:format="customFormatter"-->
    </validation-provider>
  </div>
  <div v-else>
    <vue-timepicker
        input-width="100%"
        v-model="time"
        :input-class="'form-control'"
        :language="es"
        :bootstrap-styling="true"
        :readonly="false"
        :placeholder="placeholder"
        :required="true"
        :format="'HH:mm'"
    ></vue-timepicker>
  </div>
</template>
<script>
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import {en, es} from 'vuejs-datepicker/dist/locale';

export default {
  components: {
    VueTimepicker,
  },
  props: {
    placeholder: {
      type: String,
      default: "Seleccione una Hora"
    },
    validate: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'HORA'
    }
  },
  data() {
    return {
      es: es,
      time: {
        HH: Vue.moment().format('HH'),
        mm: Vue.moment().format('mm')
      }
    }
  },
  methods: {
  },
  watch:{
    time: function (time) {

      this.$emit('input', time.HH+':'+time.mm);
    }
  }
}

</script>