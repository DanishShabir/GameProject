<template>
  <nav v-if="isAuthenticated" class="h-full">
    <nuxt-link :to="{ name: 'customer-dashboard' }" class="w-32 lg:w-64 block">
      <IconLogo />
    </nuxt-link>
    <span class="ml-6 p-2 font-bold">{{ userName }}</span>
    <div>
      <ul class="text-grey pt-8 mx-1">
        <li v-for="(navItem, index) in navItems" :key="index" class="mb-6">
          <nuxt-link
            :to="{ name: navItem.name }"
            class="ml-6 py-2 border-b lg:hover:border-red lg:hover:text-white transition duration-500 cursor-pointer"
            :class="{
              'border-red text-white': $nuxt.$route.name.includes(navItem.name),
            }"
          >
            {{ $t(navItem.title) }}
          </nuxt-link>
        </li>
        <li
          class="mb-6 py-2 transition duration-500 cursor-pointer hover:bg-red hover:bg-opacity-75 hover:text-white"
        >
          <div class="inline-block align-middle"></div>
          <a href="#" class="ml-6" @click="logout">
            {{ $t('logout') }}
          </a>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import IconLogo from '~/components/svg/IconLogo'
export default {
  name: 'CustomerSideBar',
  layout: 'Customer',
  components: {
    IconLogo,
  },
  data: () => ({
    navItems: [
      {
        name: 'customer-dashboard',
        title: 'dashboard',
      },
      {
        name: 'profileSettings.profile',
        title: 'profile-setting',
      },
      {
        name: 'credit-history',
        title: 'credit-history',
      },
      {
        name: 'withdraw',
        title: 'withdraw',
      },
      {
        name: 'games-history',
        title: 'games-history',
      },
      {
        name: 'current-games',
        title: 'current-games',
      },
    ],
  }),
  computed: {
    ...mapGetters('auth', ['userName', 'isAuthenticated']),
  },
  methods: {
    async logout() {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'welcome' })
    },
  },
}
</script>

<style></style>
