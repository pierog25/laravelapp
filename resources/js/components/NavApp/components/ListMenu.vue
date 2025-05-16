<template>
  <li class="nav-item dropdown">
    <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false">
            <span class="nav-link-inner--text small text-sm item-nav-bar nav-bar-color-text"> <i class="ni ni-app"></i>
                {{ title }}</span>
    </a>
    <!-- Dropdown header-->
    <div class="historial dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 res_dropdown-two"
         id="res_dropdown-two">

      <div class="px-lg-2 py-lg-2">
        <h6 class="text-sm text-muted m-0"></h6>
      </div>

      <div class="list-group list-group-flush">
        <router-link :to="{ name: item.nameroute }" class="list-group-item list-group-item-action"
                     v-for="(item, key) in options" :key="key + 'list'">
          <div class="row align-items-center">
            <div class="col-auto icons">
              <i :class="item.icon"></i>
            </div>
            <div class="col ml--2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="mb-0 text-sm">{{ item.name }}</h4>
                </div>
              </div>
            </div>
          </div>
        </router-link>

      </div>
      <!-- View all-->
      <a href="#" class="dropdown-item text-center text-primary font-weight-bold py-3"></a>
    </div>
  </li>
</template>
<script>
export default {
  props: {
    title: {
      type: String,
      default: 'Titulo'
    },
    options: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {}
  }
}
</script>