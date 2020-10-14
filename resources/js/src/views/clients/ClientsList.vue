<template>
  <div id="data-list-list-view" class="data-list-container">
    <clients-sidebar
      :isSidebarActive="addNewDataSidebar"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
      :data="sidebarData"
    />

    <app-table
      pageName="Clientes"
      itemName="cliente"
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
import ClientsSidebar from './ClientsSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const ClientsRepository = RepositoryFactory.get('clients');

export default {
  components: {
    ClientsSidebar,
    AppTable,
  },
  data() {
    return {
      items: [],
      addNewDataSidebar: false,
      sidebarData: { fields: [] },
      headerTitles: [
        'Razón social',
        'CUIT',
        'Dirección',
        'Teléfono',
        'Email',
        'Lista',
      ],
      headerKeys: ['name', 'cuit', 'address', 'phone', 'email', 'priceList'],
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
        this.editClient(data, id);
      } else {
        this.createClient(data);
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
        accept: () => this.deleteClient(tr),
        acceptText: 'Aceptar',
        cancelText: 'Cancelar',
      });
    },
    async fetch() {
      this.$vs.loading();
      try {
        this.items = await ClientsRepository.get();
      } catch (err) {}
      this.$vs.loading.close();
    },
    async createClient(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ClientsRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editClient(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ClientsRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deleteClient(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ClientsRepository.delete(tr.id);
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
