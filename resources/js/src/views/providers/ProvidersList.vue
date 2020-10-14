<template>
  <div id="data-list-list-view" class="data-list-container">
    <providers-sidebar
      :isSidebarActive="addNewDataSidebar"
      @closeSidebar="toggleDataSidebar"
      @submitData="sendData"
      :data="sidebarData"
    />

    <app-table
      pageName="Proveedores"
      itemName="proveedor"
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
import ProvidersSidebar from './ProvidersSidebar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
import AppTable from '../../layouts/components/table/Table.vue';
const ProvidersRepository = RepositoryFactory.get('providers');

export default {
  components: {
    ProvidersSidebar,
    AppTable,
  },
  data() {
    return {
      items: [],
      addNewDataSidebar: false,
      sidebarData: { fields: [] },
      headerTitles: ['Razón social', 'CUIT', 'Dirección', 'Teléfono', 'Email'],
      headerKeys: ['name', 'cuit', 'address', 'phone', 'email'],
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
        this.editProvider(data, id);
      } else {
        this.createProvider(data);
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
        accept: () => this.deleteProvider(tr),
        acceptText: 'Aceptar',
        cancelText: 'Cancelar',
      });
    },
    async fetch() {
      this.$vs.loading();
      try {
        this.items = await ProvidersRepository.get();
        this.items = [
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
          {
            id: 1,
            name: 'texsamar',
            cuit: '30650604748',
            address: '4 de febrero 3126 - villa zagala',
            telephone: '47535115',
            email: 'ttexsamar@gmail.com',
            created_at: '2020-06-19 16:55:52',
            updated_at: '2020-06-19 16:55:52',
          },
          {
            id: 2,
            name: 'hilos cosefil',
            cuit: '30557480907',
            address: 'cnel. apolinario figueroa 1331',
            telephone: '45822250',
            email: 'hilos_cosefil@ciudad.com.ar',
            created_at: '2020-06-19 17:03:16',
            updated_at: '2020-06-19 17:03:16',
          },
          {
            id: 3,
            name: 'jorfa',
            cuit: '20183287991',
            address: 'santa rita 8739 - loma hermosa',
            telephone: '47391981',
            email: 'jorfa89@hotmail.com',
            created_at: '2020-06-19 17:05:03',
            updated_at: '2020-06-19 17:05:03',
          },
          {
            id: 4,
            name: 'VH etiquetas',
            cuit: '30709413704',
            address: 'uruguay 467',
            telephone: '02304434884',
            email: 'ventas@vhetiquetas.com.ar',
            created_at: '2020-06-19 17:07:14',
            updated_at: '2020-06-19 17:07:14',
          },
        ];
      } catch (err) {}
      this.$vs.loading.close();
    },
    async createProvider(data) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ProvidersRepository.post(data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async editProvider(data, id) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ProvidersRepository.put(id, data);
      } catch (err) {
        response = false;
      }
      if (response !== false) {
        this.fetch();
        this.toggleDataSidebar(false);
      }
      this.$vs.loading.close();
    },
    async deleteProvider(tr) {
      this.$vs.loading();
      let response = undefined;
      try {
        response = await ProvidersRepository.delete(tr.id);
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
