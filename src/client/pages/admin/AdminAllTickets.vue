<template>
  <div class="container lg:px-8 mx-auto">
    <div>
      <base-table :header-items="headerItems" :records="getAllAdminSupportTickets" table-title="All Support Requests">
        <template slot="name" slot-scope="{ item }">
          <div>
            {{ item.user.first_name + ' ' + item.user.last_name }}
          </div>
        </template>
        <template slot="status" slot-scope="{ item }">
          <div>
            {{ item.status.name }}
          </div>
        </template>
        <template slot="date" slot-scope="{ item }">
          <div>
            {{ $moment(new Date(item.date)).format('DD/MM/YYYY HH:mm:ss') }}
          </div>
        </template>
        <template slot="description" slot-scope="{ item }">
          <div>
            {{ item.description.substring(0, 70) + '....' }}
          </div>
        </template>
        <template slot="actions" slot-scope="{ item }">
          <div class="flex gap-4">
            <button
              class="inline bg-yellow-500 h-8 m-2 text-md bg-opacity-75 text-black rounded px-4 py-1 focus:outline-none"
              type="button"
              @click.stop="showDetails(item)"
            >
              Details
            </button>
            <span class="text-xl flex"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                width="25px"
                height="20"
                class="my-auto"
                @click="editSupportTicket(item)"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="{2}"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                /></svg
            ></span>
          </div>
        </template>
        <template slot="no-items"> Nothing Found </template>
      </base-table>
    </div>
    <base-modal :showing="modalVisible" @close="modalVisible = false">
      <template slot="title">
        <h1 class="text-white text-2xl mb-4">{{ modalTitle }}</h1>
      </template>
      <template slot="content">
        <div class="max-w-xl flex flex-col w-full" @keyup.enter="toggleModal">
          <div>
            <div class="group">
              <label class="text-white text-xs group-focus:text-red-600 transition-colors duration-300"> Status </label>
              <select id="editTicket" v-model="editTicket" name="editTicket" class="w-full p-4 text-black">
                <option value="">Select Status</option>
                <option value="inprogress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
              </select>
            </div>
          </div>
          <button class="btn mt-5 w-full" type="button" @click="toggleModal()">Submit</button>
        </div>
      </template>
    </base-modal>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import swal from 'sweetalert2'
import BaseModal from '~/components/Shared/BaseModal.vue'
export default {
  name: 'AdminSupport',
  layout: 'Admin',
  components: { BaseModal },
  async asyncData({ store }) {
    await store.dispatch('AdminSupportStore/getAdminSupportTickets')
  },
  data: () => ({
    headerItems: [
      {
        id: 'name',
        name: 'User Name',
      },
      {
        id: 'title',
        name: 'Subject',
      },
      {
        id: 'description',
        name: 'Description',
      },
      {
        id: 'status',
        name: 'Status',
      },
      {
        id: 'date',
        name: 'Date',
      },
      {
        id: 'actions',
        name: 'Actions',
      },
    ],
    modalVisible: false,
    modalTitle: 'Edit Support Request',
    editTicket: '',
    ticketId: '',
    ticketTitle: '',
    ticketDescription: '',
  }),
  computed: {
    ...mapGetters('AdminSupportStore', ['getAllAdminSupportTickets']),
  },
  methods: {
    ...mapActions('AdminSupportStore', ['getAdminSupportTickets', 'getAdminTicketDetails', 'updateAdminTicketDetails']),
    showDetails(payload) {
      this.$router.push({
        name: 'admin-support-details',
        params: { id: payload.id },
      })
    },
    editSupportTicket(payload) {
      this.modalTitle = 'Edit Support Request'
      this.ticketId = payload.id
      this.modalVisible = true
    },
    async onEditTicket() {
      if (this.editTicket === '') {
        const text = 'Please Select Status'
        swal.fire({
          text,
          type: 'error',
          title: 'Select Status',
          confirmButtonText: 'Ok',
          cancelButtonText: 'Cancel',
        })
        return false
      }
      const payload = {
        ticketId: this.ticketId,
        status: this.editTicket,
      }
      try {
        await this.updateAdminTicketDetails(payload)
        await this.getAdminSupportTickets()
        this.modalVisible = false
      } catch (e) {}
    },
    toggleModal() {
      this.onEditTicket()
    },
  },
}
</script>
