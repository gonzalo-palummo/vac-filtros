<template>
  <div id="data-list-list-view" class="data-list-container">
    <supplies-sidebar
      :isSidebarActive="addNewDataSidebar"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
      :data="sidebarData"
    />

    <app-table
      pageName="Mis Insumos"
      itemName="insumo"
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
import SuppliesSidebar from './SuppliesSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const SuppliesRepository = RepositoryFactory.get('supplies');
const MeasuresRepository = RepositoryFactory.get('measures');

export default {
  components: {
    SuppliesSidebar,
    AppTable,
  },
  data() {
    return {
      items: [],
      addNewDataSidebar: false,
      sidebarData: { fields: [] },
      headerTitles: [
        'Código',
        'Nombre',
        'Stock',
        'Medida',
        'Fecha de última compra',
        'Último precio',
      ],
      headerKeys: [
        'code',
        'name',
        'stock',
        'measureName',
        'lastPurchaseDate',
        'lastPrice',
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
        this.editSupplie(data, id);
      } else {
        this.createSupplie(data);
      }
    },
    toggleDataSidebar(val = false) {
      this.addNewDataSidebar = val;
    },
    initSidebarData() {
      this.sidebarData.fields = [];
    },
    openConfirm(tr) {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Eliminar elemento',
        text: '¿Está seguro que desea eliminar?',
        accept: () => this.deleteSupplie(tr),
        acceptText: 'Aceptar',
        cancelText: 'Cancelar',
      });
    },
    async fetch() {
      this.$vs.loading();
      try {
        [this.items, this.sidebarData.measureChoices] = await Promise.all([
          SuppliesRepository.get(),
          MeasuresRepository.get(),
        ]);
      } catch (err) {}
      this.$vs.loading.close();
    },
    async createSupplie(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await SuppliesRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editSupplie(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await SuppliesRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deleteSupplie(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await SuppliesRepository.delete(tr.id);
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

<style lang="scss"></style>
