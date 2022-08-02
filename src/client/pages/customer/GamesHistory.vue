<template>
  <div class="container px-8 mx-auto">
    <div>
      <base-table
        :header-items="headerItems"
        :records="customerGames"
        table-title="Game History"
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
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'GamesHistory',
  layout: 'Customer',
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
      customerGames: 'CustomerGameStore/customerAllGames',
    }),
  },
  async created() {
    await this.$store.dispatch('CustomerGameStore/getCustomerGames')
  },
  methods: {
    rowClicked(payload) {
      // console.log(payload)
    },
  },
}
</script>

<style></style>
