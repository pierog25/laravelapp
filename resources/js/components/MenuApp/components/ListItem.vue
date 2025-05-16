<template>
  <li class="nav-item">
    <a class="nav-link" data-toggle="collapse" :href="`#menu-nav-${title}`" role="button" aria-expanded="false"
       :aria-controls="`menu-nav-${title}`">
      <i :class="`${icon}`"></i>
      <span class="nav-link-text">{{ title }}</span>
    </a>
    <div class="collapse" :id="`menu-nav-${title}`">
      <div class="navbar-inner">
        <ul class="navbar-nav">
          <li class="nav-item" v-for="(item, key) in options" :key="key + 'list'">
            <router-link @click.native="closeView" :to="{ name: item.nameroute }" class="nav-link">
              <i :class="`over ${item.icon}`"></i>
              <span class="nav-link-text">{{ item.name }}</span>
            </router-link>
          </li>
        </ul>
      </div>
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
    icon: {
      type: String,
      default: 'fas fa-cube'
    },
    options: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {}
  },
  methods: {
    closeView() {
      this.$emit('closePanel')
    }
  }
}
</script>
<style scoped>
.over {
  color: #737d90 !important;
}

</style>