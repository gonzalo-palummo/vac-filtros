<template>
  <vs-table
    ref="table"
    pagination
    :max-items="itemsPerPage"
    search
    :data="items"
    noDataText="No hay datos"
  >
    <div
      slot="header"
      class="flex flex-wrap-reverse items-center flex-grow justify-between"
    >
      <div class="flex flex-wrap-reverse items-center data-list-btn-container">
        <div
          class="btn-add-new p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-center text-lg font-medium text-base text-primary border border-solid border-primary"
          @click="exportToExcelData"
        >
          <feather-icon icon="FileIcon" svgClasses="h-4 w-4" />
          <span class="ml-2 text-base text-primary">Exportar en Excel</span>
        </div>
        <div
          class="btn-add-new btn-filled p-3 mb-4 mr-4 rounded-lg cursor-pointer flex items-center justify-center text-lg font-medium text-base text-white border border-solid"
          @click="$emit('addNewData')"
        >
          <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
          <span class="ml-2 text-base text-white">Agregar {{ itemName }}</span>
        </div>
      </div>

      <!-- ITEMS PER PAGE -->
      <vs-dropdown
        vs-trigger-click
        class="cursor-pointer mb-4 mr-4 items-per-page-handler"
      >
        <div
          class="p-4 border border-solid d-theme-border-grey-light rounded-full d-theme-dark-bg cursor-pointer flex items-center justify-between font-medium"
        >
          <span class="mr-2">
            {{ currentPage * itemsPerPage - (itemsPerPage - 1) }} -
            {{
              items.length - currentPage * itemsPerPage > 0
                ? currentPage * itemsPerPage
                : items.length
            }}
            de {{ queriedItems }}
          </span>
          <feather-icon icon="ChevronDownIcon" svgClasses="h-4 w-4" />
        </div>

        <vs-dropdown-menu>
          <vs-dropdown-item
            @click="itemsPerPage = x"
            :key="i"
            v-for="(x, i) in [4, 10, 15, 20]"
          >
            <span>{{ x }}</span>
          </vs-dropdown-item>
        </vs-dropdown-menu>
      </vs-dropdown>
    </div>

    <template slot="thead">
      <vs-th
        :sort-key="headerKeys[i]"
        class="column"
        :key="i"
        v-for="(headerTitle, i) in headerTitles"
        >{{ headerTitle }}</vs-th
      >
      <vs-th class="column">Acción</vs-th>
    </template>

    <template slot-scope="{ data }">
      <tbody>
        <vs-tr
          :data="tr"
          :key="indextr"
          v-for="(tr, indextr) in data"
          id="rows"
        >
          <vs-td :key="indextd" v-for="(headerKey, indextd) in headerKeys">
            <p
              class="product-name font-medium truncate"
              v-if="
                headerKey === 'lastPrice' ||
                  headerKey === 'totalPrice' ||
                  headerKey === 'priceList1' ||
                  headerKey === 'priceList2' ||
                  headerKey === 'priceList3'
              "
            >
              ${{ tr[headerKey] }}
            </p>
            <vs-chip
              :color="getOrderStatusColor(tr['statusId'])"
              class="product-order-status"
              v-else-if="headerKey === 'statusText'"
              >{{ tr[headerKey] }}</vs-chip
            >
            <p
              class="product-name font-medium truncate"
              v-else-if="
                headerKey === 'createdDate' ||
                  headerKey === 'invoiceDate' ||
                  headerKey === 'paymentDate' ||
                  headerKey === 'statusChangedDate' ||
                  headerKey === 'paymentDate' ||
                  headerKey === 'createdAt' ||
                  headerKey === 'lastPurchaseDate'
              "
            >
              {{ tr[headerKey] | formatDate }}
            </p>
            <p class="product-name font-medium truncate" v-else>
              {{ tr[headerKey] }}
            </p>
          </vs-td>

          <vs-td class="whitespace-no-wrap">
            <feather-icon
              icon="EditIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="ml-2"
              @click.stop="$emit('editData', tr)"
            />
            <feather-icon
              icon="TrashIcon"
              svgClasses="w-5 h-5 hover:text-danger stroke-current"
              class="ml-2"
              @click.stop="$emit('openConfirm', tr)"
            />
          </vs-td>
        </vs-tr>
      </tbody>
    </template>
  </vs-table>
</template>
<script>
import ExcelService from '../../../services/excelService';

export default {
  data() {
    return {
      itemsPerPage: 20,
      isMounted: false,
    };
  },
  props: {
    pageName: {
      type: String,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
    headerKeys: {
      Array,
      required: true,
    },
    headerTitles: {
      Array,
      required: true,
    },
    itemName: {
      String,
      required: true,
    },
  },
  methods: {
    exportToExcelData() {
      ExcelService.generateFile(
        this.pageName + ' Excel',
        this.items,
        this.headerKeys,
        this.headerTitles
      );
    },
    getOrderStatusColor(statusId) {
      if (this.pageName === 'Ventas') {
        if (statusId === 1) return 'warning';
        if (statusId === 2) return 'primary';
        if (statusId === 3) return 'success';
        return 'primary';
      } else {
        if (statusId === 1) return 'warning';
        if (statusId === 2) return 'success';
        if (statusId === 3) return 'danger';
        return 'primary';
      }
    },
  },
  computed: {
    currentPage() {
      if (this.isMounted) {
        return this.$refs.table.currentx;
      }
      return 0;
    },

    queriedItems() {
      return this.$refs.table
        ? this.$refs.table.queriedResults.length
        : this.items.length;
    },
  },
  mounted() {
    this.isMounted = true;
  },
};
</script>
<style lang="scss" scoped>
.vs-con-table::v-deep .btn-filled {
  background: rgba(var(--vs-primary), 1);
}

.vs-con-table::v-deep {
  /*
      Below media-queries is fix for responsiveness of action buttons
      Note: If you change action buttons or layout of this page, Please remove below style
    */
  @media (max-width: 689px) {
    .vs-table--search {
      margin-left: 0;
      max-width: unset;
      width: 100%;

      .vs-table--search-input {
        width: 100%;
      }
    }
  }

  @media (max-width: 461px) {
    .items-per-page-handler {
      display: none;
    }
  }

  @media (max-width: 341px) {
    .data-list-btn-container {
      width: 100%;

      .dd-actions,
      .btn-add-new {
        width: 100%;
        margin-right: 0 !important;
      }
    }
  }

  .product-name {
    max-width: 23rem;
  }

  .vs-table--header {
    display: flex;
    flex-wrap: wrap;
    margin-left: 1.5rem;
    margin-right: 1.5rem;
    > span {
      display: flex;
      flex-grow: 1;
    }

    .vs-table--search {
      padding-top: 0;

      .vs-table--search-input {
        padding: 0.9rem 2.5rem;
        font-size: 1rem;

        & + i {
          left: 1rem;
        }

        &:focus + i {
          left: 1rem;
        }
      }
    }
  }

  .vs-table {
    border-collapse: separate;
    border-spacing: 0 1.3rem;
    padding: 0 1rem;

    tr {
      box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
      td {
        padding: 20px 10px !important;
        &:first-child {
          border-top-left-radius: 0.5rem;
          border-bottom-left-radius: 0.5rem;
        }
        &:last-child {
          border-top-right-radius: 0.5rem;
          border-bottom-right-radius: 0.5rem;
        }
      }
      td.td-check {
        padding: 20px !important;
      }
    }
  }

  .vs-table--thead {
    th {
      padding-top: 0;
      padding-bottom: 0;

      .vs-table-text {
        text-transform: uppercase;
        font-weight: 600;
      }
    }
    th.td-check {
      padding: 0 15px !important;
    }
    tr {
      background: none;
      box-shadow: none;
    }
  }

  .vs-table--pagination {
    justify-content: center;
  }
}
</style>
