<template>
  <div>
    <label :for="`input-file-${index}`">
      <img v-if="image || url_icon" :src="image?image:url_icon" :class="`${classname} avatar-img img-fluid `">
      <div v-else class="file-target avatar-img d-flex justify-content-center align-items-center">
                    <span style="font-size: 40px">
                        <i class="fas fa-images"></i>
                    </span>
      </div>
      <input type="file" @change="handleFileSelect" class="input-file" :id="`input-file-${index}`" >
    </label>
  </div>
</template>
<script>
export default {
  data() {
    return {
      file: this.value,
      image: '',
      filesAllowed: ['jpg', 'jpeg', 'JPEG', 'png', 'gif', 'JPG', 'PNG', 'GIF','jfif','JFIF']
    }
  },
  props: {
    value: null,
    classname: {
      type: String,
      default: 'rounded-circle'
    },
    url_icon: {
      type: String,
      default: ''
    },
    index: {
      type: Number,
      default: 0
    },
  },
  methods: {

    handleFileSelect(e){
      event.stopPropagation();
      event.preventDefault();

      const files = event.dataTransfer? event.dataTransfer.files : event.target.files; // FileList object.

      const file = FileManager.fromFile(files[0]);

      let filesMatch = 0
      for(let typeFile of this.filesAllowed){
        if ( !file.type.match(typeFile) ) filesMatch++
      }
      if ( filesMatch === this.filesAllowed.length ) {
        Alerts.showToastErrorMessage('El tipo de archivo no está permitido');
        return;
      }
      this.file = file
      this.image = file.url

      this.$emit('input',this.file)
      this.$emit('selectFile',this.file)
    },
  },
}
</script>
<style scoped>

.input-file{
  display: none;
}
.avatar-img {
  max-width: 154px;
  border-radius: 0.375rem;
  cursor: pointer;
}
.file-target{
  width: 154px;
  height: 154px;
}

.avatar-img:hover {
  transform: scale(1.03);
}
</style>