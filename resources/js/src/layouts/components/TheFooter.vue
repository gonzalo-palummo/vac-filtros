<!-- =========================================================================================
    File Name: TheFooter.vue
    Description: Footer component
    Component Name: TheFooter
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->

<template functional>
  <footer class="the-footer flex-wrap justify-between" :class="classes">
    <p>
      <span>COPYRIGHT &copy;</span>
      <span>{{ new Date().getFullYear() }} </span>
      VAC Filtros<span class="hidden sm:inline-block"
        >, Todos los derechos reservados</span
      >
    </p>
  </footer>
</template>

<script>
export default {
  name: 'the-footer',
  props: {
    classes: {
      type: String,
    },
  },
};
</script>
