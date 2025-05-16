<template>
  <div class="card">
    <label :for="`input-file-drop-${index}`" :class="`form-control dropzone dz-clickable ${dropZoneClass}`" id="dropzone"
           @dragenter="handleDragEnter"
           @dragleave="handleDragLeave"
           @dragover="handleDragOver"
           @drop="handleFileSelect">
      <div class="dz-default dz-message">
        <!--      <input name="file" type="file" multiple />-->
        <span class="dz-button" v-if="progressBar === 0"><i v-if="icon!==''" :class="icon"></i> {{ text }}</span>
        <div class="dz-button" v-else>
          <div>{{ progress }} %</div>
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" :style="{width: progressBar+'%'}"></div>
          </div>
        </div>
        <input type="file" @change="handleFileSelect" class="input-file" :id="`input-file-drop-${index}`" multiple>
      </div>
    </label>
  </div>
</template>
<script>
export default {
  data() {
    return {
      files: this.value,
      reader: undefined,
      progress: '000',
      dropZoneClass: '',
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
    icon: {
      type: String,
      default: ''
    },
    text: {
      type: String,
      default: 'Subir archivo aqui'
    },
    index: {
      type: Number,
      default: 0
    },
  },
  methods: {
    resetDropFile(){
      this.reader= undefined
      this.progress= '000'
      this.dropZoneClass= ''
      this.progressBar= 0
    },
    abortRead() {
      this.reader.abort();
    },
    abortHandler(e) {
      Alerts.showToastErrorMessage('File read Canceled');
    },
    errorHandler (e) {
      switch(e.target.error.code) {
        case e.target.error.NOT_FOUND_ERR:
          Alerts.showToastErrorMessage('File Not Found!');
          break;
        case e.target.error.NOT_READABLE_ERR:
          Alerts.showToastErrorMessage('File is not readable');
          break;
        case e.target.error.ABORT_ERR:
          break; // noop
        default:
          Alerts.showToastErrorMessage('An error occurred reading this file.');
      }
    },
    progressHandler(e) {
      const _this = this;
      if (e.lengthComputable) {
        const loaded = Math.round((e.loaded / e.total) * 100);
        let zeros = '';

        // Percent Loaded in string
        if (loaded >= 0 && loaded < 10) zeros = '00';
        else if (loaded < 100) zeros = '0';

        // Display progress in 3-digit and increase the bar length.
        this.progress = zeros + loaded.toString();
        this.progressBar = loaded

        if(loaded === 100){
          setTimeout(function() {
            _this.$emit('onComplete', _this.progress)
            _this.resetDropFile()
          },1000)
        }
      }
    },
    loadHandler(theFile) {

      return function(e) {
        const newFile = document.createElement('div');
        const picture = document.createElement('picture');
        const img = document.createElement('div');
        img.style.backgroundImage = 'url(' + e.target.result + ')';
        img.title = escape(theFile.name);
        img.className = 'thumb';

        picture.appendChild(img);
        newFile.appendChild(picture);
        newFile.className = 'file';

        // outputTag.insertBefore(newFile, null);
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
    handleFileSelect(e){
      event.stopPropagation();
      event.preventDefault();

      //if(this.progressBar === 100) return
      this.dropZoneClass = ''
      this.progress = '000';
      this.progressBar = 0

      const files = event.dataTransfer? event.dataTransfer.files : event.target.files; // FileList object.

      for (let i=0, f; f=files[i]; i++) {
        const file = FileManager.fromFile(f);

        let filesMatch = 0
        for(let typeFile of this.filesAllowed){
          if ( !file.type.match(typeFile) ) filesMatch++
        }
        if ( filesMatch === this.filesAllowed.length ) {
          Alerts.showToastErrorMessage(`El tipo de archivo de ${file.name} no está permitido`);
          continue;
        }
        this.files.push(file)
        this.appendThumbnail(f);

      }

      if(this.files){
        this.$emit('input',this.files)
        this.$emit('dropFile',this.files)
      }
    },
    handleDragOver(e){
      e.stopPropagation();
      e.preventDefault();
      e.dataTransfer.dropEffect = 'copy';
    },
    handleDragLeave(e){
      e.stopPropagation();
      e.preventDefault();
      this.dropZoneClass = ''
    },
    handleDragEnter(e){
      e.stopPropagation();
      e.preventDefault();
      this.dropZoneClass = 'dragover'
    },
  },
  watch: {
    value(v) {
      this.files = v;
    }
  },
}
</script>
<style scoped>
.dz-message {
  padding: 0;
  background-color: transparent;
  border: 0;
  border-radius: 0;
  text-align: center;
  color: transparent;
  transition: none;
  order: 0;
  cursor: pointer;
  z-index: 1;
}
.dropzone.dz-clickable {
  cursor: pointer;
}
.dropzone {
  min-height: 150px;
  background: #fff;
  padding: 20px;
}
.dropzone, .dropzone * {
  box-sizing: border-box;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message * {
  cursor: pointer;
}
.dropzone .dz-message {
  text-align: center;
  margin: 3em 0;
}
.dropzone .dz-message .dz-button {
  background: none;
  color: #8392ab;
  border: none;
  padding: 0;
  font: inherit;
  cursor: pointer;
  outline: inherit;
}
.input-file{
  display: none;
}
</style>