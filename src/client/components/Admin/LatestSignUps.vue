<template>
  <base-table
    :table-title="tableTitle"
    :header-items="headerItems"
    :records="newUsers"
  >
    <template slot="name" slot-scope="{ item }">
      <span>{{ item.first_name + ' ' + item.last_name }}</span>
    </template>
    <template slot="country" slot-scope="{ item }">
      <div>{{ item.country.name }}</div>
    </template>
    <template slot="status" slot-scope="{ item }">
      <div>{{ item.status.name }}</div>
    </template>
    <template slot="action" slot-scope="{ item }">
      <div>
        <button
          type="button"
          class="h-8 m-2 text-sm bg-red bg-opacity-75 text-gray-200 rounded px-4 py-2 focus:outline-none"
          :disabled="item.status.id === 'disabled'"
          :class="{
            'transition-colors duration-150 hover:bg-opacity-100':
              item.status.id === 'active',
          }"
          @click="disableUser(item)"
        >
          Disable
        </button>
      </div>
    </template>
    <template slot="no-items"> Nothing Found </template>
  </base-table>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'LatestSignUps',
  props: {
    tableTitle: {
      type: String,
      default: 'Latest Sign Ups',
    },
  },
  data: () => ({
    headerItems: [
      {
        id: 'name',
        name: 'Name',
      },
      {
        id: 'country',
        name: 'Country',
      },
      {
        id: 'email',
        name: 'Email',
      },
      {
        id: 'status',
        name: 'Status',
      },
      {
        id: 'action',
        name: 'Action',
      },
    ],
  }),
  computed: {
    ...mapGetters('AdminUsersStore', ['allUsers']),
    newUsers() {
      if (!this.allUsers.length) return []
      return this.allUsers.filter((user) => !user.is_viewed)
    },
  },
  created() {},
  methods: {
    ...mapActions('AdminUsersStore', ['disableUserAccount', 'getAllUsers']),
    async disableUser(payload) {
      // console.log(payload, 'User to be disabled')
      if (payload.status.id !== 'active') {
      }
      await this.disableUserAccount({
        user_id: payload.id,
      })
      await this.getAllUsers()
    },
  },
}
</script>

<style></style>
