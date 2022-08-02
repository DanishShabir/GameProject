<template>
  <div class="lg:py-20 lg:px-20 w-full mt-10">
    <div class="gap-6 items-center" @keyup.enter="createGame">
      <div class="group">
        <label class="text-white text-md transition-colors duration-300">Email</label>
        <input
          v-model="email"
          type="text"
          class="
            bg-brand
            text-white
            px-2
            h-8
            mt-4
            mb-4
            w-full
            border-white
            transition-colors
            duration-300
            border-b
            bg-transparent
            group-focus:border-red
            focus:outline-none focus:border-red
          "
        />
      </div>
      <div class="group">
        <label class="text-white text-md transition-colors duration-300">Credits</label>
        <input
          v-model.number="credits"
          type="number"
          min="1"
          onkeypress="return event.charCode >= 48"
          class="
            bg-brand
            text-white
            px-2
            h-8
            mt-4
            mb-4
            w-full
            border-white
            transition-colors
            duration-300
            border-b
            bg-transparent
            group-focus:border-red
            focus:outline-none focus:border-red
          "
        />
      </div>
    </div>
    <button class="btn mt-5 w-full" type="button" :disabled="loading" @click="save()">
      {{ loading ? 'Adding Credits ...' : 'Add Credits' }}
    </button>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import swal from 'sweetalert2'
export default {
  name: 'AdminAddCredits',
  layout: 'Admin',
  data: () => ({
    email: '',
    credits: '',
    loading: false,
  }),
  methods: {
    ...mapActions('AdminPaymentStore', ['addCredits']),
    validateForm() {
      if (!this.email || this.credits === '') {
        const text = this.$t('required_fields_error')
        swal.fire({
          text,
          type: 'error',
          title: 'Fill all Fields',
          confirmButtonText: 'Ok',
          cancelButtonText: 'Cancel',
        })
        return false
      }
      return true
    },
    async save() {
      if (!this.validateForm()) {
        return
      }
      try {
        this.loading = true
        const payload = {
          email: this.email,
          credits: this.credits,
        }
        await this.addCredits(payload)
        this.email = ''
        this.credits = ''
        this.loading = false
        swal.fire({
          text: 'Credits added successfully',
          type: 'success',
          title: 'Credits Added',
          confirmButtonText: 'Ok',
          cancelButtonText: 'Cancel',
        })
      } catch (e) {
        this.loading = false
        if (e.response.data.errors && e.response.data.errors.errors) {
          const errors = e.response.data.errors.errors
          if (Object.keys(errors).length) {
            swal.fire({
              html: Object.values(errors).join('<br>'),
              type: 'error',
              title: 'Error',
              confirmButtonText: 'Ok',
              cancelButtonText: 'Cancel',
            })
          }
        } else if (e.response.data && e.response.data.message) {
          const message = e.response.data.message
          if (message) {
            swal.fire({
              text: message,
              type: 'error',
              title: 'Error',
              confirmButtonText: 'Ok',
              cancelButtonText: 'Cancel',
            })
          }
        }
      }
    },
  },
}
</script>

<style></style>
