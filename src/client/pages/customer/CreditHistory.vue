<template>
  <div class="container px-8 mx-auto">
    <div>
      <base-table
        :header-items="headerItems"
        :records="customerCredit"
        table-title="Credit History"
        @on-row-click="rowClicked"
      >
        <template slot="status" slot-scope="{ item }">
          <div>
            {{ item.status.name }}
          </div>
        </template>
        <template slot="paymentMethod" slot-scope="{ item }">
          <div>
            {{ item.paymentMethod.name }}
          </div>
        </template>
        <template slot="no-items"> Nothing Found </template>
      </base-table>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'CreditHistory',
  layout: 'Customer',
  components: {
    // HistoryCard: () => import('@/components/Customer/HistoryCard.vue'),
  },
  data: () => ({
    headerItems: [
      {
        id: 'amount',
        name: 'Amount',
      },
      {
        id: 'status',
        name: 'Status',
      },
      {
        id: 'paymentMethod',
        name: 'Payment Method',
      },
      {
        id: 'completed_at',
        name: 'Completed at',
      },
    ],
  }),
  computed: {
    ...mapGetters({
      customerCredit: 'CustomerPaymentsStore/allCustomerCredits',
    }),
  },
  async created() {
    await this.$store.dispatch('CustomerPaymentsStore/getCustomerCredit')
  },
  methods: {
    rowClicked(payload) {
      // console.log(payload)
    },
  },
}
</script>

<style></style>
