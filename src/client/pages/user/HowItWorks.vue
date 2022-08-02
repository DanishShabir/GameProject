<template>
  <div class="px-4 pb-4">
    <div class="max-w-5xl mx-auto">
      <div class="mb-4">
        <h1 class="text-white text-2xl mb-4 text-center mt-4 font-bold">Use your skills to win cash prizes!</h1>
        <h2 class="text-white text-1xl mb-4 text-center mt-4 font-bold">Why leave things to chance?</h2>
      </div>
      <h3 class="mb-2 font-bold">How it works</h3>
      <ol class="mb-4">
        <li>1. Register an account.</li>
        <li>2. Buy credits.</li>
        <li>3. Play competitions.</li>
        <li>4. Win cash.</li>
      </ol>
      <div class="mb-4">
        <h3 class="mb-2 font-bold">FREE CREDITS WHEN YOU INVITE A FRIEND</h3>
        <p>
          Once you’ve entered and played, you will receive a unique link within the “invite a friend" tab, which you can share directly with your friends or via
          your social media platform(s). Not only can you show off your epic score, but we will also reward you with 3 FREE CREDITS for every friend that enters
          a competition. This incentive is only limited to the number of people you know! The more successful invites, the more free credits you will receive.
        </p>
      </div>

      <div class="mb-4">
        <h3 class="mb-2 font-bold">WINNERS</h3>
        <p class="mb-2">
          If you are skillful enough to win a cash prize, congratulations! If you’re skillful enough to top the leaderboard, not only will you win an epic cash
          prize and bragging rights, but we’d also like to offer you something special. But more on that later….
        </p>
        <p>
          Winners will be contacted by Red6Six Ltd to go through the verification process and to organise the payment of your cash prize. Winners will also be
          announced on our winner’s page and social media platforms.
        </p>
      </div>

      <div class="mb-4">
        <h4 class="mb-2 font-bold">SOMETHING SPECIAL</h4>
        <p class="mb-2">
          Something special. We want our winners to enjoy their hard-earned cash prizes. After all, you earned it! We at Red6Six Ltd would like a take some of
          the burden that can be carried after winning a substantial amount of money. The player that tops the leaderboard will be given the opportunity to
          choose a charity, organization, or good cause that they support, or would like to support, and Red6Six Ltd will make a donation on the winner’s
          behalf. Win win!
        </p>
        <p class="mb-2">
          We will of course check the winner’s choice before making any donations. We understand your mate Dangerous Dave needs new wheels on his Nova, but
          there are plenty more worthy cases out there!
        </p>
        <p>
          Red6Six Ltd are dedicated to helping change people’s lives for the better. As the prize fund grows, so will the number of winners. With more winners,
          comes more opportunity to help good causes on their behalf. Thank you for joining us on this venture, we’re looking forward to seeing you on the
          leaderboard. Why leave things to chance?
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import swal from 'sweetalert2'
// import { mapActions, mapGetters } from 'vuex'
import BaseModal from '~/components/Shared/BaseModal.vue'
export default {
  name: 'WithDraw',
  scrollToTop: false,
  layout: 'User',
  components: { BaseModal },
  async asyncData({ store }) {
    await store.dispatch('UserPaymentsStore/getUserWallets')
  },
  data: () => ({}),
  mounted() {
    window.addEventListener('keyup', this.escEvent)
  },
  destroyed() {
    window.removeEventListener('keyup', this.escEvent)
  },
  /* computed: {
    ...mapGetters('UserPaymentsStore', [
      'errorMessage',
      'getCurrentUserWithdrawals',
    ]),
  }, */
  methods: {
    /* ...mapActions('UserPaymentsStore', [
      'createUserWithdraw',
      'getUserWithdrawals',
    ]), */
  },
  head: {
    bodyAttrs: {
      class: 'footer-margin',
    },
  },
}
</script>
