<template>
  <div></div>
</template>
<script>
export default {
  mounted() {
    this.$eventBus.$on('show-message', ({ color, text }) => {
      this.$vs.notify({
        text,
        iconPack: 'feather',
        icon: 'icon-alert-circle',
        time: '5000',
        color,
      });
    });
  },
  beforeDestroy() {
    this.$eventBus.$off('show-message');
  },
};
</script>