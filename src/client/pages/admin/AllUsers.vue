<template>
  <div class="grid grid-cols-1 gap-4">
    <div class="flex">
      <input
        v-model="query"
        type="text"
        placeholder="Search User"
        autocomplete="off"
        class="
          bg-brand
          text-white
          px-2
          h-8
          mt-4
          mb-4
          ml-auto
          border-white
          transition-colors
          duration-300
          border-b
          bg-transparent
          group-focus:border-red
          focus:outline-none focus:border-red
          right-0
          relative
        "
      />
    </div>
    <all-users-widget table-title="All Users" :users="allUsers" @row-clicked="getUserDetails"></all-users-widget>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'AllUsers',
  layout: 'Admin',
  components: {
    AllUsersWidget: () => import('~/components/Admin/AllUsersWidget.vue'),
  },
  async asyncData({ store }) {
    await store.dispatch('AdminUsersStore/getAllUsers')
  },
  data: () => ({
    query: null,
    loading: false,
  }),
  computed: { ...mapGetters('AdminUsersStore', ['allUsers']) },
  watch: {
    query(val) {
      if (!this.loading) {
        setTimeout(() => {
          this.getAllUsers(this.query)
          this.loading = false
        }, 700)
      }
      this.loading = true
    },
  },
  methods: {
    ...mapActions('AdminUsersStore', ['getAllUsers']),
    getUserDetails(payload) {
      this.$router.push({ name: 'user-details', params: { id: payload.id } })
    },
  },
}
</script>

<style></style>
