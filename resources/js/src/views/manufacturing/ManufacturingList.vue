<template>
  <div id="data-list-list-view" class="data-list-container">
    <manufacturing-sidebar
      :isSidebarActive="addNewDataSidebar"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
      :data="sidebarData"
    />

    <app-table
      pageName="Fabricación"
      itemName="producción"
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
import ManufacturingSidebar from './ManufacturingSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const ProductsRepository = RepositoryFactory.get('products');
const ManufacturingRepository = RepositoryFactory.get('manufacturing');

export default {
  components: {
    ManufacturingSidebar,
    AppTable,
  },
  data() {
    return {
      items: [],
      addNewDataSidebar: false,
      sidebarData: { fields: [] },
      headerTitles: ['Producto', 'Cantidad', 'Fecha'],
      headerKeys: ['name', 'amount', 'createdAt'],
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
        this.editManufacturing(data, id);
      } else {
        this.createManufacturing(data);
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
        text:
          '¿Está seguro que desea eliminar esta producción? Esto disminuirá la cantidad de stock del producto y los insumos gastados en esta producción van a ser agregados nuevamente al stock.',
        accept: () => this.deleteManufacturing(tr),
        acceptText: 'Aceptar',
        cancelText: 'Cancelar',
      });
    },
    async fetch() {
      this.$vs.loading();
      try {
        this.items = await ManufacturingRepository.get();
        this.sidebarData.productChoices = await ProductsRepository.get();
      } catch (err) {}
      this.$vs.loading.close();
    },
    async createManufacturing(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ManufacturingRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editManufacturing(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ManufacturingRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deleteManufacturing(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ManufacturingRepository.delete(tr.id);
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
