<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-sm-12 table-responsive">
          <table class="table align-items-center table-flush dataTable no-footer" id="datatable-basic" role="grid"
                 aria-describedby="datatable-basic_info">
            <thead class="thead-light">
            <tr role="row">
              <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic" rowspan="1" colspan="1"
                  aria-label="Name: activate to sort column ascending">ID
              </th>
              <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic" rowspan="1" colspan="1"
                  aria-label="Email: activate to sort column ascending">Nombre
              </th>
              <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic" rowspan="1" colspan="1"
                  aria-label=": activate to sort column ascending">Correo
              </th>
              <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic" rowspan="1" colspan="1"
                  aria-label="Email: activate to sort column ascending">RUC
              </th>
              <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic" rowspan="1" colspan="1"
                  aria-label="Email: activate to sort column ascending">Telefono
              </th>
              <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic" rowspan="1" colspan="1"
                  aria-label=": activate to sort column ascending">Tipo
              </th>
            </tr>
            </thead>
            <tbody>
            <tr role="row" class="odd" v-for="(item,index) in list" :key="`row_${index}`">
              <td>{{ item.id }}</td>
              <td>{{ item.name }}</td>
              <td>
                <strong>{{ item.email }}</strong>
              </td>
              <td>
                <strong>{{ item.ruc }}</strong>
              </td>
              <td>{{ item.first_phone }}</td>
              <td>{{ item.type }}</td>
              <td class="text-right">
                <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only btn-primary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" style="">
                    <a class="dropdown-item" href="#" @click.prevent="editItem(item)">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="deleteItem(item)">
                      <i class="fas fa-trash-alt"></i> Eliminar
                    </a>
                  </div>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <pagination ref="pagination" :listAll="listAll" @paginate="setPagination"></pagination>
    </div>
  </div>
</template>
<script>
import Pagination from '../../../components/Pagination.vue'
export default {
  components: {
    'pagination': Pagination,
  },
  data(){
    return {
      list: [],
      listFiltered: [],
      cantPages: [],
      count: 0,
    }
  },
  props:{
    listAll: {
      type:Array
    }
  },
  methods: {
    setPagination(data) {
      console.log(data, "data");
      this.list = data.list
      this.cantPages = data.cantPages
      this.listFiltered = data.listFiltered
    },
    editItem(item){
      this.$router.push({name:'updatesuppliers',params:{ status: 'EDIT', item: item }})
    },
    async deleteItem(item){
      const result = await Alerts.showConfirmDeleteMessage();

      if(result.isConfirmed){
        try{
          const response = await axios.delete(`/api/supplier/${item.id}`,{...item})
          if(response.status === 200){
            const resultData = response.data;
            if(resultData.code === 'Success'){
              Alerts.showDeletedMessage();
              this.$emit('deleteItem',item)
            }
          }
        }catch (e) {
          Alerts.showErrorMessage();
        }
      }
    },
    sendPaginate(index) {
      for(let itemCant of this.cantPages){
        itemCant.class=""
      }
      item.class='active'
      this.list = this.listFiltered[item.pagination-1]
      this.count = this.list.length
    }
  },
  watch:{
    'listAll': function (list) {
      this.$refs.pagination.filterList(list);
    }
  }
}
</script>

<style scoped>

.table td, .table th {
  white-space: normal !important;
}
@media (max-width: 1200px) {
  .table td, .table th {
    white-space: nowrap !important;
  }
}
</style>