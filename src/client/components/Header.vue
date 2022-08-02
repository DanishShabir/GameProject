<template>
  <header class="flex flex-row items-center z-50 w-full top-0 fixed">
    <div class="w-full flex">
      <div
        class="header-logo"
        @click="toggleMenu(true)"
      >
        <IconLogo />
      </div>
      <div
        v-if="isAuthenticated && $route.name !== 'game'"
        class="text-center lg:mr-6 lg:ml-auto flex items-center"
      >
        <div
          v-if="currentUser && currentUser.photo_url"
          class="rounded-full h-24 w-24 m-auto"
          :style="{ backgroundImage: 'url(' + currentUser.photo_url + ')' }"
        />
        <p v-if="userName" class="text-white text-xs">
          {{ userName }}
        </p>
      </div>
      <div
        v-if="$route.name === 'game'"
        class="playing__header flex-1 flex flex-row"
      >
        <div class="playing__shoots flex w-full justify-end"></div>
      </div>
    </div>
    <Navigation @mobileMenuVisibleToggle="toggleMenu" />
  </header>
</template>
<script>
import { mapGetters } from 'vuex'
import Navigation from '~/components/Navigation'
import IconLogo from '~/components/svg/IconLogo'

export default {
  name: 'Header',
  components: {
    IconLogo,
    Navigation,
  },
  data: () => ({
    userMenuOpen: false,
  }),
  computed: {
    ...mapGetters('auth', ['userName', 'currentUser', 'isAuthenticated', 'name']),
  },
  methods: {
    registerButton() {
      this.$router.push({
        name: 'register',
        query: { redirect: this.$router.currentRoute },
      })
    },
    toggleMenu(value) {
      if(this.currentUser) {
        if (this.currentUser.is_admin) {
          this.$router.push({ name: 'admin-dashboard', params: { isBack: true } })
        } else {
          this.$router.push({ name: 'live-games', params: { isBack: true } })
        }
      }
      this.$emit('mobileMenuVisibleToggle', value)
    },
    async logout() {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      await this.$router.push({ path: '/' })
    },
  },
}
</script>

<style scoped lang="scss">
header {
  min-height: 54px;
}
</style>
