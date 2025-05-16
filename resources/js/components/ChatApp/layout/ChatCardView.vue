<template>
  <div class="card">
    <div class="card-header d-inline-block">
      <div class="row">
        <div class="col-md-12">
          <div class="media align-items-center">
            <div v-if="item.user.photo">
              <img alt="Image" :src="JSON.parse(item.user.photo)[0].url"
                   class="avatar shadow img-avatar-message">
            </div>
            <div v-else >
              <img alt="Image" src="https://comprobante.shalom.pe/img/log_icon.png"
                   class="avatar shadow img-avatar-message">
            </div>
            <div class="media-body px-2">
              <h4 class="mb-0 d-block">{{ item.user.name }}</h4>
              <span class="text-muted text-small">1:53am</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body card-messages-body" id="chat-container">
      <div v-for="(item,index) in messages">
        <div class="row mt-4">
          <div class="col-md-12 text-center">
            <span class="badge text-default">{{ dateConversation(index) }}</span>
          </div>
        </div>
        <div v-for="(itemx,indexx) in item">
          <message-item
              :message="itemx.message"
              :file="JSON.parse(itemx.files)"
              :from_me="validateFromUser(itemx.from_user)"
              :date="itemx.updated_at"
          ></message-item>
        </div>
      </div>
    </div>
    <div class="card-footer d-block">
      <form @submit.prevent="checkForm" ref="form">
        <div class="form-group">
          <div class="input-group mb-4">
            <input class="form-control" placeholder="Tu respuesta" type="text" v-model="form.message">
            <div class="input-group-append">
              <input type="file" name="file" id="file2" class="inputfile" multiple
                     @change="getNewFiles()"/>
              <label for="file2"
                     class="btn input-group-text m-0"><i
                  class="fas fa-plus ml-2"></i></label>


              <button type="submit" class="btn input-group-text">
                <i class="ni ni-send"></i>
              </button>
            </div>

          </div>
          <div class="mt-2 mb-2" style="float: right">
            <app-file-displayer v-for="(item,index) in files" :key="'images_m'+index"
                                :value="item"
                                @delete="deleteDocument(index)"/>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import MessageItem from '../components/MessageItem.vue';
import AppFileDisplayer from '../../../components/AppFileDisplayer';
var moment = require('moment');
export default {
  components:{
    MessageItem,
    'app-file-displayer': AppFileDisplayer,
  },
  data() {
    return {
      messages: [],
      form: {
        message: ""
      },
      files: [],
      fileManager: new FileManager(),
    }
  },
  methods: {
    getNewFiles() {
      const files = event.target.files;
      let arreglo = this.files;
      for (let i = 0; i < files.length; i++) {
        arreglo.push(this.fileManager.fromFile(files[i]));
      }
      console.log(files)
    },
    deleteDocument(index) {
      this.files.splice(index, 1);
    },
    dateConversation(date) {
      return moment(date).format('dddd h:mm a')
    },
    checkForm(){
      if(this.form.message==="") return;

      this.sendMessage()
    },
    async sendMessage(){

      let files = []

      for (const file of this.files) {
        const url = await FileProvider.fetchFile(file)
        if(url){
          files.push(url.url)
        }else{
          await Alerts.showSmallErrorMessage('No se pudo subir la imagen');
          return;
        }
      }

      try {
        const body = {
          message: this.form.message,
          parent_ticket_id: this.ticket,
          to_user_id: this.item.user.id,
          files: JSON.stringify(files)
        }

        const response = await axios.post('message', {...body});

        console.log(response)
        if (response.data.code === "Success") {
          const new_message = {
            files:null,
            from_user: {
              id: this.$store.state.dataPersonal.id
            },
            message:this.form.message,
            message_status:1,
            parent_ticket: {
              id: this.ticket
            },
            to_user:null,
            updated_at: moment().format('YYYY-MM-DD HH:mm:ss'),
          }
          this.files =  []

          console.log(new_message)
          await  this.getMessages()
          this.form.message = ""
          Alerts.showToastMessage(response.data.message);
          // await this.getMessages()
        }else{
          Alerts.showErrorMessageWithMessage(response.data.message);
        }
      } catch (e) {
        console.log(e)
        await Alerts.showSmallErrorMessage(e.response.data.message);
      }
    },
    async getMessages(){
      try {
        const body = {
          ticket: this.ticket,
        }
        const response = await axios.post('find-message-by-request',{...body});

        if (response.status === 200) {
          //await Alerts.showToastMessage(response.data.message);
          this.messages = response.data.data;
        }
      } catch (e) {
        //await Alerts.showSmallErrorMessage(e.response.data.message);
      }
      $('#chat-container').scrollTop( $('#chat-container').prop('scrollHeight') );
    },
    validateFromUser (user) {
      return user==null
    }
  },
  async mounted() {
    await this.getMessages()
  },
  props: {
    ticket:{
      type: String
    },
    item:{
      type: Object
    },
    userLogged:{
      type: Object
    }
  }
}
</script>
<style scoped>

.card-messages-body {
  height: 500px;
  overflow: auto;
}
.img-avatar-message{
  background-color: #223177;
}
</style>