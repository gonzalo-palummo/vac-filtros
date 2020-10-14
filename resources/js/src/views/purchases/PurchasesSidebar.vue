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
        factura
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
          <label class="w-full label-select">Proveedor</label>
          <select v-model="provider" class="select" name="provider" v-validate="'required'">
            <option
              :key="item.id"
              :value="item.id"
              v-for="item in data.providerChoices"
            >{{ item.name }}</option>
          </select>
        </div>

        <vs-input
          label="N° de factura"
          v-model="invoiceNumber"
          class="mt-5 w-full"
          name="N° de factura"
          v-validate="'required|numeric'"
          type="number"
        />
        <span
          class="text-danger text-sm"
          v-show="errors.has('N° de factura')"
        >{{ errors.first('N° de factura') }}</span>

        <div class="mt-5">
          <label class="w-full datePicker label-select mb-1">Fecha de emisión</label>
          <flat-pickr v-model="invoiceDate" v-validate="'required'" name="Fecha de emisión" />
        </div>

        <div class="mt-5">
          <label class="w-full label-select">Método de pago</label>
          <select
            v-model="paymentMethod"
            class="select"
            name="paymentMethod"
            v-validate="'required'"
          >
            <option
              :key="item.value"
              :value="item.value"
              v-for="item in data.methodChoices"
            >{{ item.text }}</option>
          </select>
        </div>

        <div class="mt-5">
          <label class="w-full datePicker label-select mb-1">Fecha de pago</label>
          <flat-pickr v-model="paymentDate" v-validate="'required'" name="Fecha de pago" />
        </div>

        <div class="mt-5">
          <label class="w-full label-select">Estado</label>
          <select v-model="status" class="select" name="status" v-validate="'required'">
            <option
              :key="item.value"
              :value="item.value"
              v-for="item in data.statusChoices"
            >{{ item.text }}</option>
          </select>
        </div>

        <h2 class="h5 pt-5 mt-5">Agregar insumo a la compra</h2>
        <div class="mt-5">
          <label class="label-select">Seleccionar insumo</label>
          <select v-model="supplyIdSelected" @change="onSelectSupply($event)" class="select">
            <option
              :key="item.id"
              :value="item.id"
              v-for="item in data.supplyChoices"
            >{{ item.name }} (Último precio unitario: ${{item.lastPrice}})</option>
          </select>
        </div>

        <vs-input
          :label="supplySelected ? 'Cantidad ('+ supplySelected.measureName +')' : 'Cantidad'"
          v-model="supplyQuantity"
          class="my-5"
          type="number"
        />
        <vs-input label="Precio Unitario" v-model="supplyPrice" class="my-5" type="number" />
        <vs-button
          class="mr-6"
          @click="addSupply"
          :disabled="!supplyIdSelected || !supplyQuantity || !supplyPrice"
        >Agregar</vs-button>

        <h2 class="h5 mt-5">Insumos agregados</h2>
        <vs-table
          ref="table"
          :data="suppliesAdded"
          class="my-4"
          noDataText="No hay insumos agregados"
        >
          <template slot="thead">
            <vs-th :sort-key="headerVal[i]" :key="i" v-for="(title, i) in headerTitle">{{title}}</vs-th>
            <vs-th></vs-th>
          </template>

          <template slot-scope="{data}">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :key="indextd" v-for="(headerVal, indextd) in headerVal">
                <p
                  v-if="headerVal !== 'unitPrice' && headerVal !== 'totalPrice'"
                >{{ tr[headerVal] }}</p>
                <p v-else>${{ tr[headerVal] }}</p>
              </vs-td>
              <vs-td>
                <span @click.stop="removeSupply(tr['id'])">Quitar</span>
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <p class="my-3">Total de insumos: {{suppliesAdded.length}}</p>
        <p>Precio total: ${{totalPrice}}</p>
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
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

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
    flatPickr,
  },
  data() {
    return {
      provider: null,
      invoiceNumber: null,
      invoiceDate: '',
      paymentMethod: null,
      paymentDate: '',
      status: null,

      supplyIdSelected: null,
      supplyQuantity: null,
      supplyPrice: null,
      suppliesAdded: [],
      totalPrice: 0,
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
      headerTitle: ['Nombre', 'Cantidad', 'Precio unitario', 'Total'],
      headerVal: ['name', 'quantity', 'unitPrice', 'totalPrice'],

      supplySelected: null,
    };
  },
  watch: {
    isSidebarActive(val) {
      if (!val) return;
      if (Object.entries(this.data.fields).length === 0) {
        this.initValues();
        this.$validator.reset();
      } else {
        const {
          providerId,
          statusId,
          paymentMethodId,
          invoiceNumber,
          invoiceDate,
          paymentDate,
          suppliesAdded,
          totalPrice,
        } = JSON.parse(JSON.stringify(this.data.fields));
        this.provider = providerId;
        this.status = statusId;
        this.paymentMethod = paymentMethodId;
        this.invoiceNumber = invoiceNumber;
        this.invoiceDate = invoiceDate;
        this.paymentDate = paymentDate;
        this.suppliesAdded = suppliesAdded;
        this.totalPrice = totalPrice;
        if (suppliesAdded.length) {
          suppliesAdded.forEach(supply => {
            this.data.supplyChoices.splice(
              this.searchIndexById(this.data.supplyChoices, supply.id),
              1
            );
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
        this.provider &&
        this.invoiceNumber &&
        this.invoiceDate &&
        this.paymentMethod &&
        this.paymentDate &&
        this.status &&
        this.suppliesAdded.length > 0
      );
    },
    scrollbarTag() {
      return 'div';
    },
  },
  methods: {
    initValues() {
      if (this.data.fields.id) return;
      this.supplyIdSelected = null;
      this.provider = null;
      this.invoiceNumber = null;
      this.invoiceDate = '';
      this.paymentMethod = null;
      this.paymentDate = '';
      this.status = null;
      this.suppliesAdded = [];
      this.totalPrice = 0;
    },
    submitData() {
      this.$validator.validateAll().then(result => {
        if (result) {
          const supplies = [];
          this.suppliesAdded.forEach(supply =>
            supplies.push({
              supplie_id: supply.id,
              amount: Number(supply.quantity),
              unit_price: Number(supply.unitPrice),
            })
          );
          const DTO = {
            provider_id: this.provider,
            purchase_status_id: this.status,
            payment_method_id: this.paymentMethod,
            invoice_number: this.invoiceNumber,
            price: this.totalPrice,
            invoice_date: this.invoiceDate,
            payment_date: this.paymentDate,
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
    addSupply() {
      const supply = this.data.supplyChoices.find(
        supply => supply.id === this.supplyIdSelected
      );
      this.suppliesAdded.push({
        id: supply.id,
        name: supply.name,
        quantity: this.supplyQuantity,
        unitPrice: this.supplyPrice,
        totalPrice: this.supplyPrice * this.supplyQuantity,
      });
      this.totalPrice += this.supplyPrice * this.supplyQuantity;
      this.supplyIdSelected = null;
      this.supplySelected = null;
      this.supplyQuantity = null;
      this.supplyPrice = null;

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
    onSelectSupply(event) {
      const supply = this.data.supplyChoices.find(
        supply => supply.id == event.target.value
      );
      this.supplySelected = supply;
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
