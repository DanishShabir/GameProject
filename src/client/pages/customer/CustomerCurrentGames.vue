<template>
  <CurrentGames />
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'CustomerCurrentGames',
  layout: 'Customer',
  components: {
    CurrentGames: () => import('@/components/Customer/CurrentGames.vue'),
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
        name: 'Entry fee',
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
      // console.log('Following Row is Clicked', payload)
    },
  },
}
</script>

<style></style>
