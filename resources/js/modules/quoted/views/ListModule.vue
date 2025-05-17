<template>
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0">Lista de pedidos</h3>
    </div>
    <div class="card-body">
      <FiltersForm :is_search="is_search" @search="getSearch"></FiltersForm>
      <TableList :listAll="list" @deleteItem="deleteItem" ></TableList>
      <hr>
    </div>
  </div>
</template>
<script>

import FiltersForm from '../components/FiltersModule.vue'
import TableList from '../components/TableModule.vue'

export default {
  components: {
    FiltersForm,
    TableList,
  },
  data() {
    return {
      list: [],
      listFiltered: [],
      cantPages: [],
      paginate: 0,
      count: 0,
      is_search: false
    }
  },
  methods: {
    async getSearch(search) {
      this.is_search = true;
      let list = [];
      let body = {...search}
      /** USAR EN CASO SE HAGA LA PAGINACION EN BACKEND*/
      //body.start = this.paginate

      let url = '/api/pre-sale-report'

      try {
        const result = await axios.get(url,{
          params: body
        });
        if (result.status === 200) {
          const resultData = result.data;
          console.log(resultData, "resultData");
          if (resultData.success) {
            Alerts.showToastMessage(resultData.msg, 'center');
            console.log(resultData, "RESULT-DATA")
            list = resultData.data;

            /** USAR EN CASO SE HAGA LA PAGINACION EN BACKEND*/
            //this.count = resultData.count
          }else{
            Alerts.showToastErrorMessage(resultData.msg,'center')
          }
          this.is_search = false;
        }
      } catch (e) {
        Alerts.showErrorMessage();
        this.is_search = false;
      }
      this.list = list;

    },
    deleteItem(item){
      this.list = this.list.filter((myitem) => myitem !== item)
    }
  }
}
</script>