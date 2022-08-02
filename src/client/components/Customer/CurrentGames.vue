<template>
  <div class="container mx-auto">
    <base-table
      table-title="Current Games"
      :header-items="headerItems"
      :records="customerAllGames"
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
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'CurrentGames',

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
        name: 'Entry fee',
      },
      {
        id: 'start_date',
        name: 'Start date',
      },
      {
        id: 'end_date',
        name: 'End date',
      },
      {
        id: 'days_remaining',
        name: 'Days left',
      },
    ],
  }),
  computed: {
    ...mapGetters('CustomerGameStore', ['customerAllGames']),
  },
  async created() {
    await this.$store.dispatch('CustomerGameStore/getCustomerGames')
  },
  methods: {
    rowClicked(payload) {
      // console.log('Following Row is Clicked', payload)
      // console.log('Following Row is Clicked', payload)
    },
  },
}
</script>
