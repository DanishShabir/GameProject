<template>
  <div class="container mx-auto max-w-3xl px-4 py-4 lg:pb-16">
    <div class="grid grid-cols-1 gap-4">
      <div class="flex flex-row relative">
        <div class="card-header text-2xl text-center text-white flex-1">Free Credits</div>
      </div>
      <div>
        <base-table
          :header-items="headerItems"
          :records="freeCredits"
          :paginate="false"
          class="mb-2"
        >
          <template slot="position" slot-scope="{ index }">
            <div>{{ index + 1 }}</div>
          </template>
          <template slot="no-items">You don't have any free credits yet. Your invite a friend link will be activiated once you have entered a competition 5 times. 3 FREE CREDITS will be added for every successful invite.</template>
        </base-table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'User',
  middleware: 'auth',
  async asyncData({ store }) {
    await store.dispatch('UserPaymentsStore/getUserWallets')
    await store.dispatch('UserGameStore/getUserFreeCredits')
  },
  data: () => ({
    headerItems: [
      {
        id: 'position',
        name: 'No.',
      },
      {
        id: 'name',
        name: 'Game',
      },
      {
        id: 'credit',
        name: 'Credits',
      },
    ],
  }),
  computed: {
    ...mapGetters('UserGameStore', ['freeCredits']),
  },
  methods: {},
}
</script>
