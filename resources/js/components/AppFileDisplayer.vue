<template>

  <div id="app-file-displayer" class="d-inline-block mr-3 mt-1" style="position: relative">
    <div class="deleteButton bg-danger" @click="$emit('delete')"><i class="fas fa-minus"></i></div>
    <div v-if="value.type === 'jpg' || value.type === 'jpeg' || value.type === 'png'" class="image-target">
      <a :href="value.url" target="_blank">
        <img :src="value.url" alt="image" class="img-displayer">
      </a>
    </div>
    <div v-if="value.type === 'docx' || value.type === 'pdf'">
      <a :href="value.url" target="_blank">
        <div class="file-target text-center">
                    <span style="font-size: 40px">
                        <i class="fas fa-file"></i>
                    </span>
        </div>
      </a>
    </div>
    <div v-if=" value.type === 'xls' || value.type === 'xlsx'">
      <a :href="value.url" target="_blank">
        <div class="file-target text-center">
                    <span style="font-size: 40px">
                       <i class="far fa-file-excel"></i>
                    </span>
        </div>
      </a>
    </div>
  </div>

</template>

<script>

export default {

  props: {
    value: {
      type: Object
    },
  },

  data(){
    return {
      currentFileManager: this.value
    }
  },
  mounted(){
  },
  watch: {
    value:function () {
      //console.log( this.value )
    }
  }
}

</script>

<style>

.img-displayer {
  max-width: 100%;
  max-height: 100%;
  display: block;
  margin: 0px auto;
}

.image-target {
  width: 100px;
  height: 100px;
  background-color: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(0, 0, 0, 0.3);
}

.file-target {
  width: 100px;
  height: 100px;
  background-color: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(0, 0, 0, 0.3);
  color: white;
  padding: 20px 0px;
}
.deleteButton {
  cursor: pointer;
  position: absolute;
  right: -10px;
  top: -10px;
  color: white;
  display: flex;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  justify-content: center;
  align-items: center;
}

</style>
