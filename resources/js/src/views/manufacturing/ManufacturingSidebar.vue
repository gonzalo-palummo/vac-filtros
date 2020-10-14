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
        Producción
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
        <div class="mt-5">
          <label class="label-select">Producto</label>
          <select
            v-model="product"
            @change="onSelectProduct($event)"
            :disabled="data.fields.id"
            class="select"
          >
            <option
              :key="item.id"
              :value="item.id"
              v-for="item in data.productChoices"
            >{{ item.name }}</option>
          </select>
        </div>

        <vs-input
          label="Cantidad"
          v-model="amount"
          class="mt-5 w-full"
          name="Cantidad"
          v-validate="'required|numeric'"
          type="number"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('Cantidad')"
        >{{ errors.first('Cantidad') }}</span>

        <h2 class="h5 pt-5 mt-5" v-if="productSupplies.length">Insumos del producto</h2>
        <div :key="i" v-for="(supplie, i) in productSupplies">
          <vs-input class="mt-5 w-full" label="Nombre" :value="supplie.name" readonly />
          <div class="vx-row">
            <vs-input
              class="mt-5 w-full vx-col md:w-1/2"
              :label="supplie.measure"
              :value="supplie.amount"
              readonly
            />
            <vs-input
              :label="'Desperdicio ('+ supplie.measure +')'"
              v-model="productSupplies[i].wastesAmount"
              class="mt-5 w-full vx-col md:w-1/2"
              :name="'Desperdicio de '+ supplie.name"
              v-validate="'required|decimal'"
              type="number"
            />
          </div>
          <span
            class="text-danger text-sm"
            v-show="errors.has('Desperdicio de '+ supplie.name)"
          >{{ errors.first('Desperdicio de '+ supplie.name) }}</span>
          <hr v-if="i !== productSupplies.length - 1" class="my-5" />
        </div>
      </div>
    </component>

    <div class="flex flex-wrap items-center p-6" slot="footer">
      <vs-button
        class="mr-6"
        @click="submitData"
        :disabled="!isFormValid"
      >{{ Object.entries(this.data.fields).length === 0 ? 'Agregar' : 'Guardar' }}</vs-button>
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
      product: null,
      amount: null,
      productSupplies: [],
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
        const { productId, amount, wastes } = JSON.parse(
          JSON.stringify(this.data.fields)
        );
        this.product = productId;
        this.amount = amount;

        const product = this.data.productChoices.find(
          product => product.id == this.product
        );
        this.productSupplies = JSON.parse(JSON.stringify(product.supplies));
        // I think that is unnecessary
        if (this.productSupplies.length && wastes.length) {
          this.productSupplies.forEach(supply => {
            const wastesSupply = wastes.find(waste => {
              return waste.supplyId === supply.id;
            });

            supply.wastesAmount = wastesSupply.amount;
          });
        }
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
        this.product &&
        this.amount &&
        this.checkHasWastes()
      );
    },
    scrollbarTag() {
      return 'div';
    },
  },
  methods: {
    initValues() {
      if (this.data.fields.id) return;
      this.product = null;
      this.amount = null;
      this.productSupplies = [];
    },
    submitData() {
      this.$validator.validateAll().then(result => {
        if (result) {
          const supplies = [];
          this.productSupplies.forEach(productSupply =>
            supplies.push({
              supply_id: productSupply.id,
              wastes: productSupply.wastesAmount,
            })
          );
          const DTO = {
            product_id: this.product,
            amount: this.amount,
            supplies,
          };

          if (this.data.fields.id) {
            this.$emit('submitData', DTO, this.data.fields.id);
          } else {
            this.$emit('submitData', DTO, false);
          }
        }
      });
    },
    onSelectProduct(event) {
      const product = this.data.productChoices.find(
        product => product.id == event.target.value
      );
      this.productSupplies = JSON.parse(JSON.stringify(product.supplies));
    },
    checkHasWastes() {
      let hasWastesFlag = true;
      this.productSupplies.forEach(supplie => {
        if (!supplie.wastesAmount) {
          hasWastesFlag = false;
        }
      });
      return hasWastesFlag;
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
