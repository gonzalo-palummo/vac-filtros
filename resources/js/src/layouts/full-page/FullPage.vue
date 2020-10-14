<template>
  <div class="layout--full-page">
    <app-notifications />
    <router-view></router-view>
  </div>
</template>
<script>
import AppNotifications from '@/layouts/components/app-notifications/AppNotifications.vue';

export default {
  components: {
    AppNotifications,
  },
};
</script>