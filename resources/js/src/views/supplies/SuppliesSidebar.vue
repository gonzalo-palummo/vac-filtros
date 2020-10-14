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
        Object.entries(this.data.fields).length === 0 ? 'Agregar nuevo' : 'Editar'
        }}
        Insumo
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
          label="Nombre"
          v-model="name"
          class="mt-5 w-full"
          name="Nombre"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('Nombre')">{{ errors.first('Nombre') }}</span>

        <vs-input
          label="Código de producto"
          v-model="code"
          class="mt-5 w-full"
          name="Código de producto"
          v-validate="'required'"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('Código de producto')"
        >{{ errors.first('Código de producto') }}</span>

        <vs-input
          label="Stock"
          v-model="stock"
          class="mt-5 w-full"
          name="Stock"
          v-validate="'required|numeric'"
          type="number"
        />
        <span class="text-danger text-sm" v-show="errors.has('Stock')">{{ errors.first('Stock') }}</span>

        <div class="mt-5">
          <label class="w-full label-select">Medida</label>
          <select v-model="measure" class="select" name="measure" v-validate="'required'">
            <option
              :key="item.value"
              :value="item.value"
              v-for="item in data.measureChoices"
            >{{ item.text }}</option>
          </select>
        </div>
      </div>
    </component>

    <div class="flex flex-wrap items-center p-6" slot="footer">
      <vs-button class="mr-6" @click="submitData" :disabled="!isFormValid">Agregar</vs-button>
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
      code: null,
      stock: null,
      measure: null,
      settings: {
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
        const { name, code, stock, measureId } = JSON.parse(
          JSON.stringify(this.data.fields)
        );
        this.name = name;
        this.code = code;
        this.stock = stock;
        this.measure = measureId;
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
        this.code &&
        this.stock &&
        this.measure
      );
    },
    scrollbarTag() {
      return 'div';
    },
  },
  methods: {
    initValues() {
      this.name = '';
      this.code = null;
      this.stock = null;
      this.measure = null;
    },
    submitData() {
      this.$validator.validateAll().then(result => {
        if (result) {
          const DTO = {
            name: this.name,
            code: this.code,
            stock: this.stock,
            measure_id: this.measure,
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
