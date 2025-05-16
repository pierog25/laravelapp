<template>
  <div class="spinner-border" :class="[{'spinner-border-sm':small},`text-${color}`]" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</template>
<script>
export default {
  data(){
    return {}
  },
  props: {
    color: {
      type: String,
      default: 'default',
    },
    small: {
      type: Boolean,
      default: false
    }
  }
}
</script>
<style scoped>

</style>
