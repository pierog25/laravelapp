<template>
  <button :class="[`btn btn-${color}`,{'btn-block':is_block},classname,btn_size]" :type="type" @click="onClick" :disabled="disabled">
    <div v-if="disabled" class="spinner-border spinner-border-sm" role="status">
      <span class="sr-only">Loading...</span>
      {{ text_loading }}
    </div>
    <div v-else>
      <i v-if="icon !== ''" :class="icon"></i> <slot></slot>
    </div>
  </button>
</template>
<script>
export default {
  data() {
    return {
    }
  },
  props: {
    color:{
      type: String,
      default: 'primary',
    },
    is_block:{
      type: Boolean,
      default: false,
    },
    size:{
      type: String,
      default: '',
    },
    classname: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: 'button',
    },
    text_loading: {
      type: String,
      default: '',
    },
    icon: {
      type: String,
      default: '',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    is_block_size: function(){
      return this.is_block && this.size
    },
    btn_size: function(){
      return this.size?`btn-${this.size}`:''
    },
  },
  methods: {
    onClick(event) {
      this.$emit('click',event);
    }
  },
  mounted() {

  }
}
</script>
<style scoped>

.btn-sm-block{

}

@media (max-width: 768px){
  .btn-sm-block{
    display: block;
    width: 100%;
  }
}
</style>
