<template>
  <div id="data-list-list-view" class="data-list-container">
    <my-products-sidebar
      :isSidebarActive="addNewDataSidebar"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
      :data="sidebarData"
    />

    <app-table
      pageName="Mis Productos"
      itemName="producto"
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
import MyProductsSidebar from './MyProductsSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const ProductsRepository = RepositoryFactory.get('products');
const SuppliesRepository = RepositoryFactory.get('supplies');
const CategoriesRepository = RepositoryFactory.get('categories');

export default {
  components: {
    MyProductsSidebar,
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
        'Nombre',
        'Stock',
        'Código',
        'Medida',
        'Categoría',
        'Mts. 2',
        'Lista 1',
        'Lista 2',
        'Lista 3',
      ],
      headerKeys: [
        'name',
        'stock',
        'code',
        'measurement',
        'categoryName',
        'squareMeters',
        'priceList1',
        'priceList2',
        'priceList3',
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
        text: '¿Está seguro que desea eliminar?',
        accept: () => this.deleteProduct(tr),
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
        this.items = await ProductsRepository.get();
        const supplies = await SuppliesRepository.get();
        this.sidebarData.supplyChoices = supplies;
        this.sidebarData.supplyData = JSON.parse(JSON.stringify(supplies));
        this.sidebarData.categoryChoices = await CategoriesRepository.get();
      } catch (err) {}

      this.$vs.loading.close();
    },
    async createProduct(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ProductsRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editProduct(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ProductsRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deleteProduct(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ProductsRepository.delete(tr.id);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
      }
      this.$vs.loading.close();
    },
    sendData(data, id) {
      if (id) {
        this.editProduct(data, id);
      } else {
        this.createProduct(data);
      }
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
