<template>
  <div>
    <h6 v-if="false" class="h2 text-white d-inline-block mb-0">{{ titulo }}</h6>
    <nav aria-label="breadcrumb" class="d-inline-block">
      <ol class="breadcrumb ">
        <li class="breadcrumb-item">
          <router-link :to="{ name: 'homelayout' }"><i class="fas fa-home"></i></router-link>
        </li>
        <li v-for="(item, index) in breadcrumbs" :key="index + '_breadcrumbs'" class="breadcrumb-item">
          <router-link :to="{ name: item.router }">{{ item.name }}</router-link>
        </li>
      </ol>
    </nav>
  </div>
</template>
<script>
export default {
  props: {
    titulo: {
      type: String,
      default: 'Titulo'
    },
    breadcrumbs: {
      type: Array,
      default: []
    }
  }
}
</script>
<style scoped>
</style>