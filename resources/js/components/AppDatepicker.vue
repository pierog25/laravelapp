<template>
  <div v-if="validate">
    <validation-provider :name="'title'" rules="required" v-slot="{ errors }">
      <datepicker :value="$attrs.value"
                  :input-class="'form-control'"
                  :language="es"
                  :required="true"
                  :format="customFormatter"
                  :placeholder="placeholder"
                  :bootstrap-styling="true"
                  :readonly="false"
                  @selected="send"
                  :disabledDates="disabledDates"
      />
      <span class="is-invalid">{{ errors[0] }}</span><!--:format="customFormatter"-->
    </validation-provider>
  </div>
  <div v-else>
    <datepicker :value="$attrs.value"
                :input-class="'form-control'"
                :language="es"
                :required="true"
                :format="customFormatter"
                :placeholder="placeholder"
                :bootstrap-styling="true"
                :readonly="false"
                @selected="send"
                :disabledDates="disabledDates"
    /><!--:format="customFormatter"-->
  </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale';

export default {
  components: {
    Datepicker
  },
  props: {
    placeholder: {
      type: String,
      default: "Seleccione una fecha"
    },
    useHours: {
      type: Boolean,
      default: false
    },
    customFormat: {
      type: String
    },
    disabledDates: {
      type: Object
    },
    validate: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'FECHA'
    }
  },
  data() {
    return {
      es: es,
      date: ''
    }
  },
  methods: {
    customFormatter(date) {
      date.setDate(date.getDate() + 1);
      /** .tz("America/Lima")
       * AGREGAR CUANDO SE PUEDA USAR ZONA HORARIA*/
      return Vue.moment(date).format('YYYY-MM-DD');
    },
    send(date) {
      /** .tz("America/Lima")
       * AGREGAR CUANDO SE PUEDA USAR ZONA HORARIA*/
      this.$emit('input', Vue.moment(date).format('YYYY-MM-DD'));
    }
  },

}

</script>