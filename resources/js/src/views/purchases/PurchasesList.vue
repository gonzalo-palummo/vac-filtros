<template>
  <div id="data-list-list-view" class="data-list-container">
    <purchases-sidebar
      :isSidebarActive="addNewDataSidebar"
      :data="sidebarData"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
    />
    <app-table
      pageName="Compras"
      itemName="compra"
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
import PurchasesSidebar from './PurchasesSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const PurchasesRepository = RepositoryFactory.get('purchases');
const ProvidersRepository = RepositoryFactory.get('providers');
const PaymentMethodsRepository = RepositoryFactory.get('paymentMethods');
const SuppliesRepository = RepositoryFactory.get('supplies');
const PurchasesStatusRepository = RepositoryFactory.get('purchasesStatus');

export default {
  components: {
    PurchasesSidebar,
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
        'Proveedor',
        'N° de Factura',
        'Importe',
        'Facturación',
        'Estado',
        'Cambio de estado',
      ],
      headerKeys: [
        'providerName',
        'invoiceNumber',
        'totalPrice',
        'invoiceDate',
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
    getOrderStatusColor(statusId) {
      if (statusId === 1) return 'warning';
      if (statusId === 2) return 'success';
      if (statusId === 3) return 'danger';
      return 'primary';
    },
    sendData(data, id) {
      if (id) {
        this.editPurchase(data, id);
      } else {
        this.createPurchase(data);
      }
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
      this.sidebarData.supplyChoices = JSON.parse(
        JSON.stringify(this.sidebarData.supplyData)
      );
    },
    openConfirm(tr) {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Eliminar elemento',
        text:
          '¿Está seguro que desea eliminar esta compra? Esto disminuirá el stock de los insumos de esta compra.',
        accept: () => this.deletePurchase(tr),
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
          this.sidebarData.providerChoices,
          this.sidebarData.methodChoices,
          this.sidebarData.supplyChoices,
          this.sidebarData.statusChoices,
        ] = await Promise.all([
          PurchasesRepository.get(),
          ProvidersRepository.get(),
          PaymentMethodsRepository.get(),
          SuppliesRepository.get(),
          PurchasesStatusRepository.get(),
        ]);
        this.sidebarData.supplyData = JSON.parse(
          JSON.stringify(this.sidebarData.supplyChoices)
        );
      } catch (err) {}

      this.$vs.loading.close();
    },
    async createPurchase(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await PurchasesRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editPurchase(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await PurchasesRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deletePurchase(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await PurchasesRepository.delete(tr.id);
      } catch (err) {
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

<style lang="scss"></style>
