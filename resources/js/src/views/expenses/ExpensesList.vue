<template>
  <div id="expenses-list">
    <div class="vx-row">
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
        <statistics-card-line
          icon="DollarSignIcon"
          :statistic="'$' + expensesToday"
          statisticTitle="Monto a abonar hoy"
          color="danger"
        ></statistics-card-line>
      </div>
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
        <statistics-card-line
          icon="DollarSignIcon"
          :statistic="'$' + nextExpenses"
          statisticTitle="Próximos egresos"
          color="danger"
        ></statistics-card-line>
      </div>
    </div>

    <calendar
      :events="events"
      :displayedEventsData="displayedEventsData"
      :calendarLabels="calendarLabels"
    />

    <h2 class="mt-6">Próximos egresos</h2>
    <vs-table ref="table" search pagination :max-items="10" :data="items">
      <template slot="thead">
        <vs-th
          :sort-key="headerVal[i]"
          class="column"
          :key="i"
          v-for="(title, i) in headerTitle"
          >{{ title }}</vs-th
        >
      </template>

      <template slot-scope="{ data }">
        <tbody>
          <vs-tr
            :data="tr"
            :key="indextr"
            v-for="(tr, indextr) in data"
            id="rows"
          >
            <vs-td :key="indextd" v-for="(headerVal, indextd) in headerVal">
              <p
                class="product-name font-medium truncate"
                v-if="headerVal === 'paymentDate'"
              >
                {{ tr[headerVal] | formatDate }}
              </p>

              <p class="product-name font-medium truncate" v-else>
                {{ headerVal === 'totalPrice' ? '$' : null }}{{ tr[headerVal] }}
              </p>
            </vs-td>
          </vs-tr>
        </tbody>
      </template>
    </vs-table>
  </div>
</template>

<script>
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue';
import calendar from '../../layouts/components/calendar/Calendar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
const PurchasesRepository = RepositoryFactory.get('purchases');

export default {
  components: {
    StatisticsCardLine,
    calendar,
  },
  data() {
    return {
      nextExpenses: 0,
      expensesToday: 0,
      items: [],
      headerVal: [
        'providerName',
        'totalPrice',
        'paymentMethodName',
        'paymentDate',
      ],
      headerTitle: ['Proveedor', 'Monto', 'Tipo de pago', 'Fecha'],
      events: [],
      calendarLabels: [
        {
          text: 'Monto individual',
          color: 'danger',
        },
      ],
      displayedEventsData: [
        { label: 'N° de factura', key: 'invoiceNumber' },
        { label: 'Proveedor', key: 'providerName' },
        { label: 'Método de pago', key: 'paymentMethodName' },
        { label: 'Fecha de facturación', key: 'invoiceDate' },
        { label: 'Estado', key: 'purchaseStatusName' },
        { label: 'Monto', key: 'totalPrice' },
      ],
    };
  },
  methods: {
    addEvent(event, type) {
      this.events.push({
        id: event.id,
        title: '$' + event.totalPrice,
        startDate: event.paymentDate,
        classes: 'event-' + type,
        detail: true,
        invoiceNumber: event.invoiceNumber,
        providerName: event.providerName,
        paymentMethodName: event.paymentMethodName,
        invoiceDate: event.invoiceDate,
        purchaseStatusName: event.statusText,
        totalPrice: event.totalPrice,
      });
    },
  },
  async created() {
    this.$vs.loading();
    let nextPurchases = [];
    try {
      nextPurchases = await PurchasesRepository.getNext();
    } catch (err) {}
    this.items = nextPurchases;
    nextPurchases.forEach(purchase => {
      if (!Date.prototype.toUTCDate) {
        Date.prototype.toUTCDate = function() {
          return (
            this.getUTCFullYear() +
            '-' +
            ('0' + (this.getUTCMonth() + 1)).slice(-2) +
            '-' +
            ('0' + this.getUTCDate()).slice(-2)
          );
        };
      }

      const todayDateString = new Date().toUTCDate();

      if (purchase.paymentDate === todayDateString) {
        this.expensesToday += purchase.totalPrice;
      }

      this.nextExpenses += purchase.totalPrice;
      this.addEvent(purchase, 'danger');
    });

    this.$vs.loading.close();
  },
};
</script>

<style lang="scss">
#expenses-list {
  .greet-user {
    position: relative;

    .decore-left {
      position: absolute;
      left: 0;
      top: 0;
    }
    .decore-right {
      position: absolute;
      right: 0;
      top: 0;
    }
  }

  .line-area-chart {
    display: none;
  }

  .vx-card {
    padding-bottom: 1.5rem;
  }

  @media (max-width: 576px) {
    .decore-left,
    .decore-right {
      width: 140px;
    }
  }

  .vs-table--search {
    margin-right: auto;
    margin-left: unset;
  }

  .vs-table--header {
    margin-left: 0;
    margin-right: 0;
  }
}
</style>
