<template>
  <div id="simple-calendar-app">
    <div class="vx-card no-scroll-content">
      <calendar-view
        ref="calendar"
        :displayPeriodUom="calendarView"
        :show-date="showDate"
        :events="events"
        :eventTop="'3rem'"
        eventBorderHeight="0px"
        eventContentHeight="2rem"
        class="theme-default"
        @click-event="openEvent"
      >
        <div slot="header" class="mb-4">
          <div class="vx-row no-gutter">
            <div class="vx-col sm:w-1/2 w-full sm:my-0 my-3 flex justify-center order-last">
              <div class="flex items-center">
                <feather-icon
                  :icon="$vs.rtl ? 'ChevronRightIcon' : 'ChevronLeftIcon'"
                  @click="updateMonth(-1)"
                  svgClasses="w-5 h-5 m-1"
                  class="cursor-pointer bg-primary text-white rounded-full"
                />
                <span class="mx-3 text-xl font-medium whitespace-no-wrap">{{ showDate | month }}</span>
                <feather-icon
                  :icon="$vs.rtl ? 'ChevronLeftIcon' : 'ChevronRightIcon'"
                  @click="updateMonth(1)"
                  svgClasses="w-5 h-5 m-1"
                  class="cursor-pointer bg-primary text-white rounded-full"
                />
              </div>
            </div>

            <div class="vx-col sm:w-1/2 w-full flex justify-center">
              <template v-for="(view, index) in calendarViewTypes">
                <vs-button
                  v-if="calendarView === view.val"
                  :key="String(view.val) + 'filled'"
                  type="filled"
                  class="p-3 md:px-8 md:py-3"
                  :class="{'border-l-0 rounded-l-none': index, 'rounded-r-none': calendarViewTypes.length !== index+1}"
                  @click="calendarView = view.val"
                >{{ view.label }}</vs-button>
                <vs-button
                  v-else
                  :key="String(view.val) + 'border'"
                  type="border"
                  class="p-3 md:px-8 md:py-3"
                  :class="{'border-l-0 rounded-l-none': index, 'rounded-r-none': calendarViewTypes.length !== index+1}"
                  @click="calendarView = view.val"
                >{{ view.label }}</vs-button>
              </template>
            </div>
          </div>

          <div class="vx-row sm:flex hidden mt-4">
            <div class="vx-col w-full flex">
              <div class="flex flex-wrap sm:justify-start justify-center">
                <div
                  v-for="(label, index) in calendarLabels"
                  :key="index"
                  class="flex items-center mr-4 mb-2"
                >
                  <div class="h-3 w-3 inline-block rounded-full mr-2" :class="'bg-' + label.color"></div>
                  <span>{{ label.text }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </calendar-view>
    </div>
    <vs-prompt
      class="calendar-event-dialog"
      title="Detalle"
      accept-text="Aceptar"
      cancel-text
      :active.sync="activePromptEvent"
    >
      <vs-input
        class="w-full"
        :key="index"
        v-for="(label, index) in displayedEventsData"
        :label="label.label"
        v-model="inputsData[label.key]"
        readonly
      ></vs-input>
    </vs-prompt>
  </div>
</template>
<script>
import StatisticsCardLine from '@/components/statistics-cards/StatisticsCardLine.vue';
import { CalendarView, CalendarViewHeader } from 'vue-simple-calendar';
require('vue-simple-calendar/static/css/default.css');

export default {
  components: {
    StatisticsCardLine,
    CalendarView,
    CalendarViewHeader,
  },
  props: {
    events: {
      type: Array,
      required: true,
    },
    displayedEventsData: {
      type: Array,
      required: true,
    },
    calendarLabels: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      showDate: new Date(),
      disabledFrom: false,
      activePromptEvent: false,
      calendarView: 'week',

      calendarViewTypes: [
        {
          label: 'Mes',
          val: 'month',
        },
        {
          label: 'Semana',
          val: 'week',
        },
      ],
      inputsData: [],
    };
  },
  methods: {
    updateMonth(val) {
      this.showDate = this.$refs.calendar.getIncrementedPeriod(val);
    },
    openEvent(event) {
      if (!event.originalEvent.detail) return;
      this.displayedEventsData.forEach(label => {
        this.inputsData[label.key] = event.originalEvent[label.key];
        if (label.key === 'totalPrice')
          this.inputsData[label.key] = '$' + this.inputsData[label.key];
      });
      this.activePromptEvent = true;
    },
  },
};
</script>
<style lang="scss">
@import '@sass/vuexy/apps/simple-calendar.scss';
.vs-dialog-text {
  input[readonly='readonly'] {
    padding-left: 5px !important;
  }
}
.calendar-event-dialog footer {
  display: none !important;
}
</style>