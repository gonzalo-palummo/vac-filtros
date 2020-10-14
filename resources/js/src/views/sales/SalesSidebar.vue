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
        venta
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
          <label class="w-full label-select">Cliente</label>
          <select v-model="client" class="select" name="client" v-validate="'required'">
            <option
              :key="item.id"
              :value="item.id"
              v-for="item in data.clientChoices"
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

        <h2 class="h5 pt-5 mt-5">Agregar producto a la venta</h2>
        <div v-if="currentClient">
          <div class="mt-5">
            <label class="label-select">Seleccionar producto</label>
            <select v-model="productIdSelected" class="select">
              <option
                :key="item.id"
                :value="item.id"
                v-for="item in data.productChoices"
              >{{ item.name }} (Precio unitario listado: ${{item['priceList'+currentClient.priceList]}})</option>
            </select>
          </div>

          <vs-input label="Cantidad" v-model="productQuantity" class="my-5" type="number" />
          <vs-input label="Precio Unitario" v-model="productPrice" class="my-5" type="number" />
          <vs-button
            class="mr-6"
            @click="addProduct"
            :disabled="!productIdSelected || !productQuantity || !productPrice"
          >Agregar</vs-button>
        </div>
        <div v-else class="mt-5">
          <p>Para seleccionar un producto necesitás seleccionar un cliente previamente</p>
        </div>

        <h2 class="h5 mt-5">Productos agregados</h2>
        <vs-table :data="productsAdded" class="my-4" noDataText="No hay productos agregados">
          <template slot="thead">
            <vs-th :sort-key="headerVal[i]" :key="i" v-for="(title, i) in headerTitle">{{title}}</vs-th>
            <vs-th></vs-th>
          </template>

          <template slot-scope="{data}">
            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
              <vs-td :data="tr[headerVal]" :key="indextd" v-for="(headerVal, indextd) in headerVal">
                <p
                  v-if="headerVal !== 'unitPrice' && headerVal !== 'totalPrice'"
                >{{ tr[headerVal] }}</p>
                <p v-else>${{ tr[headerVal] }}</p>
              </vs-td>
              <vs-td>
                <span @click.stop="removeProduct(tr['id'])">Quitar</span>
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
        <p class="my-3">Total de productos: {{productsAdded.length}}</p>
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
      client: null,
      invoiceNumber: null,
      invoiceDate: '',
      paymentMethod: null,
      paymentDate: '',
      status: null,

      productIdSelected: null,
      productQuantity: null,
      productPrice: null,
      productsAdded: [],
      totalPrice: 0,
      settings: {
        // perfectscrollbar settings
        maxScrollbarLength: 60,
        wheelSpeed: 0.6,
      },
      headerTitle: ['Nombre', 'Cantidad', 'Precio unitario', 'Total'],
      headerVal: ['name', 'quantity', 'unitPrice', 'totalPrice'],
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
          clientId,
          statusId,
          paymentMethodId,
          invoiceNumber,
          invoiceDate,
          paymentDate,
          productsAdded,
          totalPrice,
        } = JSON.parse(JSON.stringify(this.data.fields));
        this.client = clientId;
        this.status = statusId;
        this.paymentMethod = paymentMethodId;
        this.invoiceNumber = invoiceNumber;
        this.invoiceDate = invoiceDate;
        this.paymentDate = paymentDate;
        this.productsAdded = productsAdded;
        this.totalPrice = totalPrice;
        if (productsAdded.length) {
          productsAdded.forEach(supply => {
            this.data.productChoices.splice(
              this.searchIndexById(this.data.productChoices, supply.id),
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
        this.client &&
        this.invoiceNumber &&
        this.invoiceDate &&
        this.paymentMethod &&
        this.paymentDate &&
        this.status &&
        this.productsAdded.length > 0
      );
    },
    scrollbarTag() {
      return 'div';
    },
    currentClient() {
      if (this.client) {
        return this.data.clientChoices.find(
          client => client.id === this.client
        );
      } else {
        return null;
      }
    },
  },
  methods: {
    initValues() {
      if (this.data.fields.id) return;
      this.client = null;
      this.invoiceNumber = null;
      this.invoiceDate = '';
      this.paymentMethod = null;
      this.paymentDate = '';
      this.status = null;
      this.productsAdded = [];
      this.totalPrice = 0;
    },
    submitData() {
      this.$validator.validateAll().then(result => {
        if (result) {
          const products = [];
          this.productsAdded.forEach(product =>
            products.push({
              product_id: product.id,
              amount: Number(product.quantity),
              unit_price: Number(product.unitPrice),
            })
          );
          const DTO = {
            client_id: this.client,
            sale_status_id: this.status,
            payment_method_id: this.paymentMethod,
            invoice_number: this.invoiceNumber,
            total_price: this.totalPrice,
            invoice_date: this.invoiceDate,
            payment_date: this.paymentDate,
            products,
          };

          if (this.data.fields.id) {
            this.$emit('submitData', DTO, this.data.fields.id);
          } else {
            this.$emit('submitData', DTO, false);
          }
        }
      });
    },
    addProduct() {
      const product = this.data.productChoices.find(
        prod => prod.id === this.productIdSelected
      );
      this.productsAdded.push({
        id: product.id,
        name: product.name,
        quantity: this.productQuantity,
        unitPrice: this.productPrice,
        totalPrice: this.productPrice * this.productQuantity,
      });
      this.totalPrice += this.productPrice * this.productQuantity;
      this.productIdSelected = null;
      this.productQuantity = null;
      this.productPrice = null;

      this.data.productChoices.splice(
        this.searchIndexById(this.data.productChoices, product.id),
        1
      );
    },
    removeProduct(id) {
      const productAdded = this.productsAdded.find(
        product => product.id === id
      );
      this.totalPrice -= productAdded.totalPrice;
      this.productsAdded.splice(
        this.searchIndexById(this.productsAdded, id),
        1
      );
      const product = this.data.productData.find(product => product.id === id);
      this.data.productChoices.push(product);
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
