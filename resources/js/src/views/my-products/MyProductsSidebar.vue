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
          Object.entries(this.data.fields).length === 0
            ? 'Agregar nuevo'
            : 'Editar'
        }}
        Producto
      </h4>
      <feather-icon
        icon="XIcon"
        @click.stop="isSidebarActiveLocal = false"
        class="cursor-pointer"
      ></feather-icon>
    </div>
    <vs-divider class="mb-0"></vs-divider>

    <component
      :is="scrollbarTag"
      class="scroll-area--data-list-add-new"
      :settings="settings"
      :key="$vs.rtl"
    >
      <div class="p-6">
        <h2 class="h5 mt-5">Detalles del producto</h2>
        <vs-input
          label="Nombre"
          v-model="name"
          class="mt-5 w-full"
          name="Nombre"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('Nombre')">{{
          errors.first('Nombre')
        }}</span>

        <vs-input
          label="Stock"
          v-model="stock"
          class="mt-5 w-full"
          name="Stock"
          v-validate="'required|numeric'"
          type="number"
        />
        <span class="text-danger text-sm" v-show="errors.has('Stock')">{{
          errors.first('Stock')
        }}</span>

        <div class="mt-5">
          <label class="w-full label-select">Categoría</label>
          <select
            v-model="category"
            label="Categoría"
            class="select"
            name="Categoría"
            v-validate="'required'"
          >
            <option
              :key="item.value"
              :value="item.value"
              v-for="item in data.categoryChoices"
              >{{ item.text }}</option
            >
          </select>
        </div>
        <span class="text-danger text-sm" v-show="errors.has('Categoría')">{{
          errors.first('Categoría')
        }}</span>

        <vs-input
          label="Código"
          v-model="code"
          class="mt-5 w-full"
          name="Código"
          v-validate="'required|numeric'"
          type="number"
        />
        <span class="text-danger text-sm" v-show="errors.has('Código')">{{
          errors.first('Código')
        }}</span>

        <vs-input
          label="Medida"
          v-model="measurement"
          class="mt-5 w-full"
          name="Medida"
          v-validate="'required'"
        />
        <span class="text-danger text-sm" v-show="errors.has('Medida')">{{
          errors.first('Medida')
        }}</span>

        <vs-input
          label="Mts. 2"
          v-model="squareMeters"
          class="mt-5 w-full"
          name="Mts. 2"
          type="number"
          v-validate="'required|decimal'"
        />
        <span class="text-danger text-sm" v-show="errors.has('Mts. 2')">{{
          errors.first('Mts. 2')
        }}</span>

        <h2 class="h5 pt-5 mt-5">Agregar insumo</h2>

        <div class="mt-5">
          <label class="w-full label-select">Insumo</label>
          <select
            v-model="supplyIdSelected"
            label="Insumo"
            class="select"
            name="Insumo"
          >
            <option
              :key="item.id"
              :value="item.id"
              v-for="item in data.supplyChoices"
              >{{ item.name }} (Último precio unitario: ${{
                item.lastPrice
              }})</option
            >
          </select>
        </div>

        <vs-input
          :label="'Cantidad ' + getMeasureSupplySelected"
          v-model="supplyAmount"
          class="my-5"
          type="number"
        />

        <vs-button
          class="mr-6"
          @click="addSupply()"
          :disabled="!supplyIdSelected || !supplyAmount"
          >Agregar</vs-button
        >

        <h2 class="h5 mt-5">Insumos agregados</h2>
        <vs-table
          ref="table"
          :data="suppliesAdded"
          class="my-4"
          noDataText="No hay insumos agregados"
        >
          <template slot="thead">
            <vs-th
              :sort-key="headerVal[i]"
              :key="i"
              v-for="(title, i) in headerTitle"
              >{{ title }}</vs-th
            >
            <vs-th></vs-th>
          </template>

          <template slot-scope="{ data }">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :key="indextd" v-for="(headerVal, indextd) in headerVal">
                <p
                  v-if="headerVal !== 'lastPrice' && headerVal !== 'totalPrice'"
                >
                  {{ tr[headerVal] }}
                </p>
                <p v-else>${{ tr[headerVal] }}</p>
              </vs-td>
              <vs-td>
                <span @click.stop="removeSupply(tr['id'])">Quitar</span>
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <p class="my-3">Total de insumos: {{ suppliesAdded.length }}</p>
        <p>Costo estimado: ${{ totalPrice }}</p>

        <h2 class="h5 mt-5">Precio del producto</h2>
        <!--<vs-input
          label="Porcentaje de ganancia"
          v-model="profitPercent"
          class="my-5"
          type="number"
        />-->
        <vs-input
          label="Precio de lista 1"
          v-model="salePrice1"
          class="my-5"
          type="number"
        />
        <vs-input
          label="Precio de lista 2"
          v-model="salePrice2"
          class="my-5"
          type="number"
        />
        <vs-input
          label="Precio de lista 3"
          v-model="salePrice3"
          class="my-5"
          type="number"
        />
        <!--<p class="my-3">Precio de venta sugerido: ${{ suggestedSalePrice }}</p>
        <p class="my-3">Utilidad sugerida: ${{ utility }}</p>-->
      </div>
    </component>

    <div class="flex flex-wrap items-center p-6" slot="footer">
      <vs-button class="mr-6" @click="submitData" :disabled="!isFormValid"
        >Agregar</vs-button
      >
      <vs-button
        type="border"
        color="danger"
        @click="isSidebarActiveLocal = false"
        >Cancelar</vs-button
      >
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
      stock: '',
      category: null,
      code: null,
      measurement: null,
      squareMeters: null,

      supplyIdSelected: null,
      supplyAmount: null,
      suppliesAdded: [],
      totalPrice: 0,
      profitPercent: null,
      salePrice1: null,
      salePrice2: null,
      salePrice3: null,
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
      headerTitle: ['Nombre', 'Cantidad', 'Último precio unitario', 'Total'],
      headerVal: ['name', 'amount', 'lastPrice', 'totalPrice'],
    };
  },
  watch: {
    isSidebarActive(val) {
      if (!val) return;
      this.initValues();
      if (Object.entries(this.data.fields).length === 0) {
        this.$validator.reset();
      } else {
        const {
          name,
          stock,
          id,
          code,
          measurement,
          squareMeters,
          categoryId,
          priceList1,
          priceList2,
          priceList3,
          supplies,
        } = JSON.parse(JSON.stringify(this.data.fields));
        this.name = name;
        this.stock = stock;
        this.productId = id;
        this.code = code;
        this.measurement = measurement;
        this.squareMeters = squareMeters;

        this.category = categoryId;
        this.salePrice1 = priceList1;
        this.salePrice2 = priceList2;
        this.salePrice3 = priceList3;
        this.suppliesAdded = [];
        if (supplies.length) {
          supplies.forEach(supply => {
            this.addSupply(supply.id, supply.amount);
          });
        }
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
        this.stock &&
        this.code &&
        this.measurement &&
        this.squareMeters &&
        this.category &&
        this.suppliesAdded.length &&
        this.salePrice1 &&
        this.salePrice2 &&
        this.salePrice3
      );
    },
    scrollbarTag() {
      return 'div';
    },
    suggestedSalePrice() {
      return this.totalPrice + this.utility;
    },
    utility() {
      return (this.totalPrice * this.profitPercent) / 100;
    },
    getMeasureSupplySelected() {
      if (this.supplyIdSelected) {
        const index = this.searchIndexById(
          this.data.supplyChoices,
          this.supplyIdSelected
        );
        return '(' + this.data.supplyChoices[index].measureName + ')';
      } else {
        return '';
      }
    },
  },
  methods: {
    initValues() {
      this.name = '';
      this.stock = null;
      this.productId = '';
      this.code = null;
      this.measurement = '';
      this.squareMeters = null;
      this.category = null;
      this.suppliesAdded = [];
      this.totalPrice = 0;
      this.supplyAmount = null;
      this.profitPercent = null;
      this.salePrice1 = null;
      this.salePrice2 = null;
      this.salePrice3 = null;
    },
    submitData() {
      this.$validator.validateAll().then(result => {
        if (result) {
          const supplies = [];
          this.suppliesAdded.forEach(supply =>
            supplies.push({
              supplie_id: Number(supply.id),
              supplie_amount: Number(supply.amount),
            })
          );

          const DTO = {
            name: this.name,
            stock: this.stock,
            category_id: this.category,
            code: this.code,
            measurement: this.measurement,
            mts: this.squareMeters,
            price1: this.salePrice1,
            price2: this.salePrice2,
            price3: this.salePrice3,
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
    addSupply(id = null, amount = null) {
      const supplyId = id ? id : this.supplyIdSelected;
      const supply = this.data.supplyChoices.find(
        supply => supply.id === supplyId
      );
      if (!amount) {
        amount = this.supplyAmount;
      }
      this.suppliesAdded.push({
        id: supply.id,
        name: supply.name,
        amount,
        lastPrice: supply.lastPrice,
        totalPrice: supply.lastPrice * amount,
      });
      this.totalPrice += supply.lastPrice * amount;
      this.supplyIdSelected = null;
      this.supplyAmount = null;

      this.data.supplyChoices.splice(
        this.searchIndexById(this.data.supplyChoices, supply.id),
        1
      );
    },
    removeSupply(id) {
      const supplyAdded = this.suppliesAdded.find(supply => supply.id === id);
      this.totalPrice -= supplyAdded.totalPrice;
      this.suppliesAdded.splice(
        this.searchIndexById(this.suppliesAdded, id),
        1
      );
      const supply = this.data.supplyData.find(supply => supply.id === id);
      this.data.supplyChoices.push(supply);
    },
    searchIndexById(array, id) {
      const index = array.findIndex(x => x.id === id);
      return index;
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
