<template>
  <form method="post" role="form" @input.prevent="inputListener" @submit.prevent="checkForm" id="form-key-validator">
    <div class="row">
      <input form="form-key-validator" type="text" maxlength="1" required="required" v-model="keyCode[0]" @keydown="keyDownListener" @paste.prevent="pasteListener" class="input-keyCode-0 col-2 col-md-1 form-control mx-1 mx-md-3">
      <input form="form-key-validator" type="text" maxlength="1" required="required" v-model="keyCode[1]" @keydown="keyDownListener" class="input-keyCode-1 col-2 col-md-1 form-control mx-1 mx-md-3">
      <input form="form-key-validator" type="text" maxlength="1" required="required" v-model="keyCode[2]" @keydown="keyDownListener" class="input-keyCode-2 col-2 col-md-1 form-control mx-1 mx-md-3">
      <input form="form-key-validator" type="text" maxlength="1" required="required" v-model="keyCode[3]" @keydown="keyDownListener" class="input-keyCode-3 col-2 col-md-1 form-control mx-1 mx-md-3">
    </div>
  </form>
</template>
<script>
export default{
  data(){
    return {
      keyCode : this.value,
      disabled:false
    }
  },
  props: {
    value: {
      type: Array,
      default: () => ["","","",""]
    },
    charactersAllowed: {
      type: Array,
      default: () => [/[a-zA-Z0-9\s]/]
    }
  },
  methods: {
    inputListener(event){
      const input = event.target
      if(input.nextElementSibling && input.value){
        input.nextElementSibling.focus();
      }
      const index = input.classList[0].split('-')[2]
      this.keyCode[index] = input.value.toUpperCase()
      this.$forceUpdate();
      this.$emit("input",this.keyCode)
    },
    keyDownListener(event){
      if (!this.charactersAllowed[0].test(event.key)) {
        event.preventDefault();
      }
      const input = event.target
      let codigo = event.which || event.keyCode;
      if(codigo === 8 && !input.value && input.previousElementSibling){
        input.previousElementSibling.focus();
      }
    },
    pasteListener(event){
      const paste = event.clipboardData.getData('text').toUpperCase()
      this.keyCode.forEach((input,i)=>{
        if (paste[i]){
          this.focusToInput(`.input-keyCode-${i}`)
          this.keyCode[i] = paste[i] || ''
        }
      })
      this.$forceUpdate();
      this.$emit("input",this.keyCode)
    },
    focusToInput(clase){
      $(clase).focus()
    },
    checkForm(){
      this.$emit("saveKey",this.keyCode.join(""))
    }
  },
  watch: {}
}
</script>
<style scoped></style>