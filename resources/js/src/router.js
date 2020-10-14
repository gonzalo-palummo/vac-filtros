import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  base: '/',
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
  routes: [
    {
      path: '',
      component: () => import('./layouts/main/Main.vue'),
      children: [
        {
          path: '/',
          redirect: '/dashboard',
        },
        {
          path: '/dashboard',
          name: 'dashboard',
          component: () => import('@/views/dashboard/Dashboard.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [{ title: 'Home', url: '/', active: true }],
            pageTitle: 'Dashboard',
          },
        },
        {
          path: '/my-products',
          name: 'my-products',
          component: () => import('@/views/my-products/MyProductsList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Mis Productos', active: true },
            ],
            pageTitle: 'Mis Productos',
          },
        },
        {
          path: '/manufacturing',
          name: 'manufacturing',
          component: () =>
            import('@/views/manufacturing/ManufacturingList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Fabricación', active: true },
            ],
            pageTitle: 'Fabricación',
          },
        },
        {
          path: '/settings',
          name: 'settings',
          component: () => import('@/views/settings/Settings.vue'),
          meta: {
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Configuración', active: true },
            ],
            pageTitle: 'Configuración',
            rule: 'isEditor',
          },
        },
        {
          path: '/providers',
          name: 'providers',
          component: () => import('@/views/providers/ProvidersList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Proveedores', active: true },
            ],
            pageTitle: 'Proveedores',
          },
        },
        {
          path: '/sales',
          name: 'sales',
          component: () => import('@/views/sales/SalesList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Ventas', active: true },
            ],
            pageTitle: 'Ventas',
          },
        },
        {
          path: '/supplies',
          name: 'supplies',
          component: () => import('@/views/supplies/SuppliesList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Mis Insumos', active: true },
            ],
            pageTitle: 'Mis Insumos',
          },
        },
        {
          path: '/purchases',
          name: 'purchases',
          component: () => import('@/views/purchases/PurchasesList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Compras', active: true },
            ],
            pageTitle: 'Compras',
          },
        },
        {
          path: '/revenue',
          name: 'revenue',
          component: () => import('@/views/revenue/RevenueList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Ingresos', active: true },
            ],
            pageTitle: 'Ingresos',
          },
        },
        {
          path: '/expenses',
          name: 'expenses',
          component: () => import('@/views/expenses/ExpensesList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Egresos', active: true },
            ],
            pageTitle: 'Egresos',
          },
        },
        {
          path: '/clients',
          name: 'clients',
          component: () => import('@/views/clients/ClientsList.vue'),
          meta: {
            rule: 'isEditor',
            breadcrumb: [
              { title: 'Home', url: '/' },
              { title: 'Clientes', active: true },
            ],
            pageTitle: 'Clientes',
          },
        },
      ],
    },
    {
      path: '',
      component: () => import('@/layouts/full-page/FullPage.vue'),
      children: [
        // =============================================================================
        // FULL PAGES
        // =============================================================================

        {
          path: '/login',
          name: 'login',
          component: () => import('@/views/login/Login.vue'),
          meta: {
            rule: 'isPublic',
          },
        },
        {
          path: '/forgot-password',
          name: 'page-forgot-password',
          component: () => import('@/views/forgot-password/ForgotPassword.vue'),
          meta: {
            rule: 'isAnonymous',
          },
        },
        {
          path: '/pages/404',
          name: 'page-404',
          component: () => import('@/views/404/404.vue'),
          meta: {
            rule: 'isPublic',
          },
        },
        {
          path: '/pages/500',
          name: 'page-500',
          component: () => import('@/views/500/500.vue'),
          meta: {
            rule: 'isPublic',
          },
        },
        {
          path: '/pages/unauthorized',
          name: 'page-not-authorized',
          component: () => import('@/views/unauthorized/Unauthorized.vue'),
          meta: {
            rule: 'isPublic',
          },
        },
        /*{
          path: '/register',
          name: 'register',
          component: () => import('@/views/register/Register.vue'),
          meta: {
            rule: 'isAnonymous',
          },
        },*/
        /*{
          path: '/pages/reset-password',
          name: 'page-reset-password',
          component: () => import('@/views/pages/ResetPassword.vue'),
          meta: {
            rule: 'isAnonymous',
          },
        },*/
        /*{
          path: '/pages/lock-screen',
          name: 'page-lock-screen',
          component: () => import('@/views/pages/LockScreen.vue'),
          meta: {
            rule: 'isPublic',
          },
        },*/
        /*{
          path: '/pages/comingsoon',
          name: 'page-coming-soon',
          component: () => import('@/views/pages/ComingSoon.vue'),
          meta: {
            rule: 'isPublic',
          },
        },*/
        /*{
          path: '/pages/maintenance',
          name: 'page-maintenance',
          component: () => import('@/views/pages/Maintenance.vue'),
          meta: {
            rule: 'isPublic',
          },
        }*/
      ],
    },
    // Redirect to 404 page, if no match found
    {
      path: '*',
      redirect: '/pages/404',
    },
  ],
});

/*
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg');
  if (appLoading) {
    appLoading.style.display = 'none';
  }
});
*/

/*
router.beforeEach((to, from, next) => {
  // If auth required, check login. If login fails redirect to login page
  if (to.meta.authRequired) {
    if (!auth.isAuthenticated()) {
      router.push({ path: '/pages/login', query: { to: to.path } });
    }
  }

  return next();
});
*/

export default router;
