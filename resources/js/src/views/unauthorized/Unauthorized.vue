<template>
  <div class="h-screen flex w-full bg-img">
    <div
      class="vx-col flex items-center justify-center flex-col sm:w-1/2 md:w-3/5 lg:w-3/4 xl:w-1/2 mx-auto text-center"
    >
      <img
        src="@assets/images/pages/not-authorized.png"
        alt="graphic-not-authorized"
        class="mx-auto mb-4"
      />
      <h1 class="sm:mx-0 mx-4 sm:mb-12 mb-8 text-5xl d-theme-heading-color">No estás autorizado!</h1>
      <p class="sm:mx-0 mx-4 sm:mb-12 mb-8">Para seguir navegando inicia sesión.</p>
      <vs-button size="large" to="/login">Ir al Login</vs-button>
    </div>
  </div>
</template>
<script>
export default {
  created() {
    this.$store.dispatch('updateUserInfo', {
      displayName: null,
      token: null,
      userRole: 'anonymous',
    });
    this.$acl.change('anonymous');
  },
};
</script>
