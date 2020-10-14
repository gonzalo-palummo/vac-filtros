<template>
  <vs-sidebar
    click-not-close
    position-right
    parent="body"
    default-index="1"
    color="primary"
    class="add-new-data-sidebar items-no-padding"
    spacer
    v-model="isSidebarActiveLocal"
  >
    <div class="mt-6 flex items-center justify-between px-6">
      <h4>
        {{
        Object.entries(this.data.fields).length === 0 ? 'Agregar nueva' : 'Editar'
        }}
        empresa
      </h4>
      <feather-icon icon="XIcon" @click.stop="isSidebarActiveLocal = false" class="cursor-pointer"></feather-icon>
    </div>
    <vs-divider class="mb-0"></vs-divider>

    <component
      :is="scrollbarTag"
      class="scroll-area--data-list-add-new"
      :settings="settings"
      :key="$vs.rtl"
    >
      <div class="p-6">
        <vs-input
          label="Razón social"
          v-model="name"
          class="mt-5 w-full"
          name="Razón social"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('Razón social')">
          {{
          errors.first('Razón social')
          }}
        </span>

        <vs-input
          label="CUIT"
          v-model="cuit"
          class="mt-5 w-full"
          name="CUIT"
          v-validate="'required|numeric'"
          type="number"
        />
        <span class="text-danger text-sm" v-show="errors.has('CUIT')">
          {{
          errors.first('CUIT')
          }}
        </span>

        <vs-input
          label="Email"
          v-model="email"
          class="mt-5 w-full"
          name="Email"
          v-validate="'required' && 'email'"
          type="email"
        />
        <span class="text-danger text-sm" v-show="errors.has('Email')">
          {{
          errors.first('Email')
          }}
        </span>

        <vs-input
          label="Dirección"
          v-model="address"
          class="mt-5 w-full"
          name="Dirección"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('Dirección')">
          {{
          errors.first('Dirección')
          }}
        </span>

        <vs-input
          label="Teléfono"
          v-model="phone"
          class="mt-5 w-full"
          name="Teléfono"
          v-validate="'required|numeric'"
          type="number"
        />
        <span class="text-danger text-sm" v-show="errors.has('Teléfono')">
          {{
          errors.first('Teléfono')
          }}
        </span>

        <div class="mt-5">
          <label class="w-full label-select">Lista de precios</label>
          <select
            v-model="priceList"
            label="Lista de precios"
            class="select"
            name="Lista de precios"
          >
            <option :key="1" :value="1">Lista 1</option>
            <option :key="2" :value="2">Lista 2</option>
            <option :key="3" :value="3">Lista 3</option>
          </select>
        </div>
      </div>
    </component>

    <div class="flex flex-wrap items-center p-6" slot="footer">
      <vs-button class="mr-6" @click="submitData" :disabled="!isFormValid">
        {{
        Object.entries(this.data.fields).length === 0 ? 'Agregar' : 'Guardar'
        }}
      </vs-button>
      <vs-button type="border" color="danger" @click="isSidebarActiveLocal = false">Cancelar</vs-button>
    </div>
  </vs-sidebar>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar';

export default {
  props: {
    isSidebarActive: {
      type: Boolean,
      required: true,
    },
    data: {
      type: Object,
      default: () => {},
    },
  },
  components: {
    VuePerfectScrollbar,
  },
  data() {
    return {
      name: '',
      cuit: '',
      address: '',
      phone: '',
      email: '',
      priceList: null,
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
    };
  },
  watch: {
    isSidebarActive(val) {
      if (!val) return;
      if (Object.entries(this.data.fields).length === 0) {
        this.initValues();
        this.$validator.reset();
      } else {
        const { name, cuit, address, phone, email, priceList } = JSON.parse(
          JSON.stringify(this.data.fields)
        );
        this.name = name;
        this.cuit = cuit.replace(/\s+/g, '');
        this.address = address;
        this.phone = phone.replace(/\s+/g, '');
        this.email = email;
        this.priceList = priceList;
        this.initValues();
      }
    },
  },
  computed: {
    isSidebarActiveLocal: {
      get() {
        return this.isSidebarActive;
      },
      set(val) {
        if (!val) {
          this.$emit('closeSidebar');
        }
      },
    },
    isFormValid() {
      return (
        !this.errors.any() &&
        this.name &&
        this.cuit &&
        this.address &&
        this.phone &&
        this.email &&
        this.priceList
      );
    },
    scrollbarTag() {
      return 'div';
    },
  },
  methods: {
    initValues() {
      if (this.data.fields.id) return;
      this.name = '';
      this.cuit = '';
      this.address = '';
      this.phone = '';
      this.email = '';
      this.priceList = null;
    },
    submitData() {
      this.$validator.validateAll().then(result => {
        if (result) {
          const DTO = {
            name: this.name,
            cuit: this.cuit,
            address: this.address,
            telephone: this.phone,
            email: this.email,
            price_list: this.priceList,
          };

          if (this.data.fields.id) {
            this.$emit('submitData', DTO, this.data.fields.id);
          } else {
            this.$emit('submitData', DTO, false);
          }
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.add-new-data-sidebar {
  ::v-deep .vs-sidebar--background {
    z-index: 52010;
  }

  ::v-deep .vs-sidebar {
    z-index: 52010;
    width: 500px;
    max-width: 90vw;
  }
}

.scroll-area--data-list-add-new {
  // height: calc(var(--vh, 1vh) * 100 - 4.3rem);
  height: calc(var(--vh, 1vh) * 100 - 16px - 45px - 82px);

  &:not(.ps) {
    overflow-y: auto;
  }
}
</style>
