<template>
  <card :title="$t('your_password')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('password_updated')" />

      <!-- Password -->
      <div class="form-group">
        <label>{{ $t('new_password') }}</label>
        <div>
          <input
            v-model="form.password"
            :class="{ 'is-invalid': form.errors.has('password') }"
            type="password"
            name="password"
            class="form-control"
          />
          <has-error :form="form" field="password" />
        </div>
      </div>

      <!-- Password Confirmation -->
      <div class="form-group row">
        <label class="text-md-right">{{ $t('confirm_password') }}</label>
        <div>
          <input
            v-model="form.password_confirmation"
            :class="{ 'is-invalid': form.errors.has('password_confirmation') }"
            type="password"
            name="password_confirmation"
            class="form-control"
          />
          <has-error :form="form" field="password_confirmation" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group">
        <div>
          <v-button :loading="form.busy" type="success">
            {{ $t('update') }}
          </v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Form from 'vform'

export default {
  scrollToTop: false,
  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: '',
    }),
  }),
  methods: {
    async update() {
      await this.form.patch('/settings/password')

      this.form.reset()
    },
  },
  head() {
    return { title: this.$t('settings') }
  },
}
</script>
