<template>
  <div class="row" v-if="records>per_page">
    <div class="col-12 col-md-5">
      <div class="dataTables_info d-flex justify-content-md-start justify-content-center" id="datatable-basic_info" role="status" aria-live="polite">
        Mostrando {{ records_init_page }} a {{ records_end_page }} de {{ records }} resultados
      </div>
    </div>
    <div class="col-12 col-md-7">
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-md-end justify-content-center mt-3 mt-md-0">
          <li class="page-item">
            <button class="page-link" aria-label="Previous" @click="sendPage(page--)" :disabled="disabled">
              <i class="fa fa-angle-left"></i>
              <span class="sr-only">Previous</span>
            </button>
          </li>
          <li class="page-item" v-if="pages_per_records > 1 && !pages_show.includes(1)">
            <button class="page-link" @click="sendPage(page=1)" :disabled="disabled">{{ 1 }}</button>
          </li>
          <li class="page-item" v-for="pageNumber in pages_show" :class="[pageNumber === page ? 'active' : '']">
            <button @click="sendPage(page=pageNumber)" class="page-link" :disabled="disabled">{{ pageNumber }}</button>
          </li>
          <li class="page-item" v-if="pages_per_records > 1 && !pages_show.includes(last_page)">
            <button class="page-link" @click="sendPage(page=last_page)" :disabled="disabled">{{ last_page }}</button>
          </li>
          <li class="page-item">
            <button class="page-link" aria-label="Next" @click="sendPage(page++)" :disabled="disabled">
              <i class="fa fa-angle-right"></i>
              <span class="sr-only">Next</span>
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      page: this.value,
      pages_per_records: 0,
      last_page: 0,
      max_pages: 3,
      pages: [],
    }
  },
  props: {
    value: {
      type: Number,
      default: 1
    },
    records: {
      type: Number,
      default: 0
    },
    per_page: {
      type: Number,
      default: 25
    },
    disabled: {
      type: Boolean,
      default: false
    },
  },
  methods: {
    sendPage(value) {
      this.$emit('input', this.page)
      this.$emit('paginate', this.page)
    },
    setPages(){
      this.pages = [];
      this.pages_per_records = Math.ceil(this.records / this.per_page)
      for (let i = 1; i <= this.pages_per_records; i++){
        this.pages.push(i);
      }
      this.last_page = this.pages.length
    }
  },
  computed: {
    records_init_page() {
      return ((this.page - 1) * this.per_page) + 1
    },
    records_end_page() {
      return ((this.page * this.per_page) > this.records) ? this.records : (this.page * this.per_page)
    },
    pages_show() {
      const first_index =  Math.max(this.page-3,0)
      const last_index =  Math.min(this.page+2,this.last_page)
      return this.pages.slice(first_index,last_index)
    },
  },
  mount(){
    this.setPages()
  },
  watch: {
    records: function (value){
      this.setPages();
    }
  }
}
</script>

<style scoped>

</style>