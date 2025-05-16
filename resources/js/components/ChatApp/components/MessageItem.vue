<template>
  <div>
    <div :class="class_row">
      <div class="col-auto">
        <div :class="class_card">
          <div class="card-body p-2">
            <div class="col-12 p-0" v-if="file" v-for="(item,index) in file" :key="`img-message-${index}`">
              <a :href="item.url" target="_blank">
                <img :src="item.url"
                     alt="Rounded image" class="img-fluid rounded mb-1 mw-image">
              </a>
            </div>
            <p v-if="message" class="mb-0" v-html="parsed_message"></p>
            <div>
              <small class="opacity-60">{{ parsed_date }}</small>
              <i class="ni ni-check-bold"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var moment = require('moment');
export default {
  data() {
    return {

    }
  },
  props:{
    message: {
      type: String,
      default: ''
    },
    file: {
      type: Array,
    },
    from_me:{
      type: Boolean,
      default: false
    },
    date: {
      type: String,
      default: ''
    }
  },
  computed:{
    class_card: function () {
      return this.from_me?'card bg-message text-white':'card'
    },
    class_row: function () {
      return this.from_me?'row justify-content-end text-right':'row justify-content-start'
    },
    parsed_date: function () {
      return moment(this.date).format('h:mm a');
    },
    parsed_message: function () {
      return this.message.replace('\n','<br>');
    },
    validate_date: function () {
      const date_message = moment(this.date).format('DD');
      const date_now = moment().format('DD');

      return (parseInt(date_now) > parseInt(date_message))
    }
  }
}
</script>
<style scoped>
.bg-message{
  background-color: #223177 !important;
}
.mw-image{
  max-width: 500px;
}
</style>