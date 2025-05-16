import Vue from 'vue'
import { ValidationProvider, ValidationObserver  } from 'vee-validate'
import * as VeeValidate from 'vee-validate'

Vue.use(VeeValidate);

Vue.component(
    'validation-provider',
    ValidationProvider
)

Vue.component(
    'validation-observer',
    ValidationObserver
)

