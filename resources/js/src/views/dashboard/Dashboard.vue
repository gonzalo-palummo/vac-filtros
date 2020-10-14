<template>
  <div id="dashboard-analytics">
    <h2 class="mb-5 mx-6">Últimos 30 días</h2>
    <div class="vx-row">
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="DollarSignIcon"
          :statistic="'$' + revenue"
          statisticTitle="Ingresos"
          color="success"
        ></statistics-card-line>
      </div>
      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="DollarSignIcon"
          :statistic="'$' + expenses"
          statisticTitle="Egresos"
          color="danger"
        ></statistics-card-line>
      </div>

      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line
          icon="ShoppingCartIcon"
          :statistic="purchases"
          statisticTitle="Compras"
        ></statistics-card-line>
      </div>

      <div class="vx-col w-full sm:w-1/2 md:w-1/2 lg:w-1/4 xl:w-1/4 mb-base">
        <statistics-card-line icon="FileTextIcon" :statistic="sales" statisticTitle="Ventas"></statistics-card-line>
      </div>
    </div>

    <calendar
      :events="events"
      :displayedEventsData="displayedEventsData"
      :calendarLabels="calendarLabels"
    />
  </div>
</template>

<script>
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue';
import calendar from '../../layouts/components/calendar/Calendar.vue';
import { RepositoryFactory } from './../../repositories/RepositoryFactory';
const SalesRepository = RepositoryFactory.get('sales');
const PurchasesRepository = RepositoryFactory.get('purchases');

export default {
  data() {
    return {
      revenue: 0,
      expenses: 0,
      purchases: 0,
      sales: 0,
      events: [],
      calendarLabels: [
        {
          text: 'Ingresos',
          color: 'success',
        },
        {
          text: 'Egresos',
          color: 'danger',
        },
      ],
      displayedEventsData: [
        { label: 'N° de factura', key: 'invoiceNumber' },
        { label: 'Razón social', key: 'name' },
        { label: 'Método de pago', key: 'paymentMethodName' },
        { label: 'Fecha de facturación', key: 'invoiceDate' },
        { label: 'Estado', key: 'statusName' },
        { label: 'Monto', key: 'totalPrice' },
      ],
    };
  },
  components: {
    StatisticsCardLine,
    calendar,
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
        name: event.clientName ? event.clientName : event.providerName,
        paymentMethodName: event.paymentMethodName,
        invoiceDate: event.invoiceDate,
        statusName: event.statusText,
        totalPrice: event.totalPrice,
      });
    },
  },
  async created() {
    this.$vs.loading();
    let lastSales = [];
    let lastPurchases = [];
    try {
      [lastSales, lastPurchases] = await Promise.all([
        SalesRepository.getLast(),
        PurchasesRepository.getLast(),
      ]);
    } catch (err) {}

    lastSales.forEach(sale => {
      this.revenue += sale.totalPrice;
      this.addEvent(sale, 'success');
    });
    lastPurchases.forEach(purchase => {
      this.expenses += purchase.totalPrice;
      this.addEvent(purchase, 'danger');
    });
    this.$vs.loading.close();
    this.purchases = lastPurchases.length;
    this.sales = lastSales.length;
  },
};
</script>

<style lang="scss">
/*! rtl:begin:ignore */
#dashboard-analytics {
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
}
/*! rtl:end:ignore */
</style>
