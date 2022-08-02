<template>
  <div class="px-8">
    <div class="grid md:grid-cols-2 gap-6">
      <CurrentGames />
      <base-table
        class="w-full"
        table-title="Game history"
        :header-items="headerItems"
        :records="customerCurrentGames"
        @on-row-click="rowClicked"
      >
        <template slot="status" slot-scope="{ item }">
          <div>
            {{ item.status.name }}
          </div>
        </template>
        <template slot="no-items"> Nothing Found </template>
      </base-table>
    </div>
    <div class="mt-4 grid md:grid-cols-2 gap-8">
      <CustomerLeaderBoard />
      <CustomerLeaderBoard />
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import CustomerLeaderBoard from '~/components/Customer/CustomerLeaderboard.vue'
// import PlayCredit from '~/components/Customer/PlayCredit.vue'
import CurrentGames from '~/components/Customer/CurrentGames.vue'
export default {
  name: 'CustomerDashboard',
  layout: 'Customer',
  components: {
    CustomerLeaderBoard,
    // PlayCredit,
    CurrentGames,
  },
  data: () => ({
    headerItems: [
      {
        id: 'name',
        name: 'Name',
      },
      {
        id: 'status',
        name: 'Status',
      },
      {
        id: 'entry_fee',
        name: 'Entry Fee',
      },
      {
        id: 'days_remaining',
        name: 'Days left',
      },
    ],
  }),
  computed: {
    ...mapGetters({
      customerCurrentGames: 'CustomerGameStore/customerAllCurrentGames',
      customerCurrentGameScore: 'CustomerGameStore/customerCurrentGameScore',
    }),
  },
  async created() {
    await this.getCustomerCurrentGames()
  },
  methods: {
    rowClicked() {
      // TODO: Do something...
    },
    ...mapActions('CustomerGameStore', ['getCustomerCurrentGames']),
  },
}
</script>

<style></style>
