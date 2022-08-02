<template>
  <div class="lg:w-3/4 mx-auto">
    <div v-if="linkNotFound">
      <UserInvitationCode :games="mappedGames" />
    </div>
    <div v-else class="flex mt-4 lg:w-3/4 mx-auto">
      <div
        class="
          w-full
          p-0
          rounded
          cursor-pointer
          transition
          duration-500
        "
      >
        <div
          class="
            p-4
            text-xl
            text-center
          "
        >
          <h1 class="font-bold mb-4 text-2xl">Earn 3 <span class="font-bold"> FREE</span> credits by inviting a Friend!</h1>
          <p class="mb-2">You must enter a competition 5 times to activate your unique code. Remember, the more friends that play, the more free credits you will earn.</p>
          <p>Why leave things to chance?!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import UserInvitationCode from '~/components/User/UserInvitationCode.vue'
export default {
  name: 'UserInvitation',
  scrollToTop: false,
  layout: 'User',
  components: { UserInvitationCode },
  async asyncData({ store }) {
    await store.dispatch('UserGameStore/getInvitationGames')
    await store.dispatch('UserPaymentsStore/getUserWallets')
  },
  data: () => ({
    copyMsg: '',
    playersPresent: false,
  }),
  computed: {
    ...mapGetters('UserGameStore', ['userInvitationGames', 'getInvitationUserId']),
    mappedGames() {
      if (!this.userInvitationGames && !this.userInvitationGames.length)
        return []

      return this.userInvitationGames
        .filter(
          (game) =>
            game.players &&
            game.players.length &&
            game.status.id.toLowerCase() === 'live'
        )
        .map((game) => ({
          gameName: game.name,
          gameId: game.id,
          players: game.players.filter(
            (userId) => userId.user.id === this.getInvitationUserId
          ),
        }))
    },
    linkNotFound() {
      if (!this.userInvitationGames && !this.userInvitationGames.length)
        return []

      const payload = this.userInvitationGames
        .filter(
          (game) =>
            game.players &&
            game.players.length &&
            game.status.id.toLowerCase() === 'live'
        )
        .map((game) => ({
          check: game.players.filter(
            (userId) => userId.user.id === this.getInvitationUserId
          ).length,
        }))
      for (let a = 0; a < payload.length; a++) {
        if (payload[a].check !== 0) {
          return true
        }
      }

      return false
    },
  },
}
</script>
