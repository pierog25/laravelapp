<template>
  <label :for="`input-file-${index}`"  :class="[classname,{disabled:disabled}]">
    <div v-if="disabled">
      <div class="spinner-border spinner-border-sm" role="status">
        <span class="sr-only">Loading...</span>
      </div>
      {{ disabledText }}
    </div>
    <div v-else>
      <i v-if="icon !== ''" :class="`${icon} mr-2`"></i> {{ text }}
      <i v-if="iconEnd !== ''" :class="`${iconEnd} ml-2`"></i>
    </div>
    <input type="file" @change="handleFileSelect" :disabled="disabled" class="inputfile" :id="`input-file-${index}`" multiple>
  </label>
</template>
<script>
export default {
  data() {
    return {
      files: this.value,
      reader: undefined,
      progress: '000',
      progressBar: 0
    }
  },
  props: {
    value: {
      type: Array,
      default: () => []
    },
    filesAllowed: {
      type: Array,
      default: () => []
    },
    classname: {
      type: String,
      default: 'primary'
    },
    text: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      default: ''
    },
    iconEnd: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    },
    disabledText: {
      type: String,
      default: ''
    },
    index: {
      type: Number,
      default: 0
    },
  },
  methods: {
    resetButtonFile() {
      this.reader = undefined
      this.progress = '000'
      this.progressBar = 0
    },
    onClick(event) {
      this.$emit('click',event);
    },
    abortHandler(e) {
      Alerts.showToastErrorMessage('Se canceló la lectura del archivo');
    },
    errorHandler(e) {
      switch (e.target.error.code) {
        case e.target.error.NOT_FOUND_ERR:
          Alerts.showToastErrorMessage('No se encontró el archivo!');
          break;
        case e.target.error.NOT_READABLE_ERR:
          Alerts.showToastErrorMessage('No se puede leer el archivo');
          break;
        case e.target.error.ABORT_ERR:
          break; // noop
        default:
          Alerts.showToastErrorMessage('Ocurrió un error leyendo el archivo.');
      }
    },
    progressHandler(e) {
      if (e.lengthComputable) {
        const loaded = Math.round((e.loaded / e.total) * 100);

        this.progressBar = loaded
        // Display progress in 3-digit and increase the bar length.
        this.progress = loaded.toString().padStart(3,'0');
      }
    },
    loadHandler(theFile) {
      const _this = this;

      return function (e) {
        setTimeout(function () {
          // _this.$emit('onComplete', _this.progress)
          _this.resetButtonFile()
        }, 1000)
      }
    },
    appendThumbnail(f) {
      this.reader = new FileReader();
      this.reader.onerror = this.errorHandler;
      this.reader.onabort = this.abortHandler;
      this.reader.onprogress = this.progressHandler;
      this.reader.onload = this.loadHandler(f);
      this.reader.readAsDataURL(f);
    },
    handleFileSelect(e) {
      event.stopPropagation();
      event.preventDefault();

      if(this.disabled) return;

      this.$emit('initLoad');

      this.progress = '000';
      this.progressBar = 0;

      const files = event.target.files;

      for (let i = 0, f; f = files[i]; i++) {
        const file = FileManager.fromFile(f);

        let filesMatch = 0
        for (let typeFile of this.filesAllowed) {
          if (!file.type.match(typeFile)) filesMatch++
        }
        if (filesMatch === this.filesAllowed.length) {
          Alerts.showToastErrorMessage(`El tipo de archivo de ${file.name} no está permitido`);
          continue;
        }
        this.files.push(file)
        this.appendThumbnail(f);

      }

      if (this.files) {
        this.$emit('input', this.files)
        this.$emit('complete', this.files)
      }
    },
  },
  mounted() {

  },
  watch: {
    value(v) {
      this.files = v;
    }
  },
}
</script>
<style scoped>

@media (max-width: 768px){
  .btn-sm-block{
    display: block;
    width: 100%;
  }
}
</style>