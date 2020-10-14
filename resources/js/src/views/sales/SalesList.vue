<template>
  <div id="data-list-list-view" class="data-list-container">
    <sales-sidebar
      :isSidebarActive="addNewDataSidebar"
      :data="sidebarData"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
    />
    <app-table
      pageName="Ventas"
      itemName="venta"
      :items="items"
      :headerKeys="headerKeys"
      :headerTitles="headerTitles"
      @addNewData="addNewData"
      @editData="editData($event)"
      @openConfirm="openConfirm($event)"
    />
  </div>
</template>

<script>
import SalesSidebar from './SalesSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const SalesRepository = RepositoryFactory.get('sales');
const ClientsRepository = RepositoryFactory.get('clients');
const PaymentMethodsRepository = RepositoryFactory.get('paymentMethods');
const ProductsRepository = RepositoryFactory.get('products');
const SalesStatusRepository = RepositoryFactory.get('salesStatus');

export default {
  components: {
    SalesSidebar,
    AppTable,
  },
  data() {
    return {
      items: [],
      addNewDataSidebar: false,
      sidebarData: {
        fields: [],
      },
      headerTitles: [
        'Cliente',
        'N° factura',
        'Importe',
        'Facturación',
        'Cobro',
        'Estado',
        'Cambio de estado',
      ],
      headerKeys: [
        'clientName',
        'invoiceNumber',
        'totalPrice',
        'invoiceDate',
        'paymentDate',
        'statusText',
        'statusChangedDate',
      ],
    };
  },
  methods: {
    addNewData() {
      this.initSidebarData();
      this.toggleDataSidebar(true);
    },
    editData(data) {
      this.sidebarData.fields = data;
      this.toggleDataSidebar(true);
    },
    sendData(data, id) {
      if (id) {
        this.editSale(data, id);
      } else {
        this.createSale(data);
      }
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
      this.sidebarData.productChoices = JSON.parse(
        JSON.stringify(this.sidebarData.productData)
      );
    },
    openConfirm(tr) {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Eliminar elemento',
        text:
          '¿Está seguro que desea eliminar esta venta? Esto agregará los productos de esta venta al stock de productos.',
        accept: () => this.deleteSale(tr),
        acceptText: 'Aceptar',
        cancelText: 'Cancelar',
      });
    },
    initSidebarData() {
      this.sidebarData.fields = [];
    },
    async fetch() {
      this.$vs.loading();
      try {
        [
          this.items,
          this.sidebarData.clientChoices,
          this.sidebarData.methodChoices,
          this.sidebarData.productChoices,
          this.sidebarData.statusChoices,
        ] = await Promise.all([
          SalesRepository.get(),
          ClientsRepository.get(),
          PaymentMethodsRepository.get(),
          ProductsRepository.get(),
          SalesStatusRepository.get(),
        ]);
        this.sidebarData.productData = JSON.parse(
          JSON.stringify(this.sidebarData.productChoices)
        );
      } catch (err) {}

      this.$vs.loading.close();
    },
    async createSale(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await SalesRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editSale(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await SalesRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deleteSale(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await SalesRepository.delete(tr.id);
      } catch {
        response = false;
      }
      if (response !== false) {
        this.fetch();
      }
      this.$vs.loading.close();
    },
  },
  created() {
    this.fetch();
  },
  mounted() {
    this.initSidebarData();
  },
};
</script>

<style lang="scss">
</style>
