<template>
  <div>
    <template v-if="!preview">
      <div class="w-full">
        <div class="grid grid-cols-1 gap-6 items-center">
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Total Players : </label>
            <div class="text-white text-lg">{{ currentGameDetails.players.length }}</div>
          </div>
          <hr />
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">Admin Fee (%) : </label>
            <input
              v-model.number="gameData.adminPercentage"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="commanClasses"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">P1 Payout (£) : </label>
            <input
              v-model.number="gameData.players[0]"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(1) }]"
              :disabled="isDisabled(1)"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">P2 Payout (£) : </label>
            <input
              v-model.number="gameData.players[1]"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(2) }]"
              :disabled="isDisabled(2)"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">P3 Payout (£) : </label>
            <input
              v-model.number="gameData.players[2]"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(3) }]"
              :disabled="isDisabled(3)"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">P4 Payout (£) : </label>
            <input
              v-model.number="gameData.players[3]"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(4) }]"
              :disabled="isDisabled(4)"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">P5 Payout (£) : </label>
            <input
              v-model.number="gameData.players[4]"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(5) }]"
              :disabled="isDisabled(5)"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3"
              >Number of players to pay after P5 <span v-if="!isDisabled(6)"> (max = {{ currentGameDetails.players.length - 5 }})</span> :
            </label>
            <input
              v-model.number="gameData.numberOfPlayersToPayAfter5"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(6) }]"
              :disabled="isDisabled(6)"
            />
          </div>
          <div class="group flex items-center justify-between w-full mx-auto">
            <label class="text-white text-md transition-colors duration-300 mr-3">Amount to share between remaining players : </label>
            <input
              v-model.number="gameData.amountToShareBetweenRemainingPlayers"
              type="number"
              name="game_name"
              min="0"
              onkeypress="return event.charCode >= 48"
              :class="[commanClasses, { 'opacity-50': isDisabled(7) }]"
              :disabled="isDisabled(7)"
            />
          </div>
        </div>
        <button
          class="p-3 rounded card-header text-1xl text-center text-white mt-5 w-full bg-green-700 focus:outline-none"
          type="button"
          @click="previewMode()"
        >
          Preview
        </button>
      </div>
    </template>
    <template v-else>
      <div class="p-5" style="border: 2px solid #dbba6b">
        <div class="w-full">
          <div class="grid grid-cols-1 gap-6 items-center">
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Admin Fee (%) : </label>
              <div class="text-white text-lg">
                {{ gameData.adminPercentage }}% (£ {{ Math.floor((gameData.adminPercentage * currentGameDetails.pot_value) / 100) }})
              </div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">P1 Payout (£) : </label>
              <div class="text-white text-lg">£ {{ gameData.players[0] }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">P2 Payout (£) : </label>
              <div class="text-white text-lg">£ {{ gameData.players[1] }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">P3 Payout (£) : </label>
              <div class="text-white text-lg">£ {{ gameData.players[2] }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">P4 Payout (£) : </label>
              <div class="text-white text-lg">£ {{ gameData.players[3] }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">P5 Payout (£) : </label>
              <div class="text-white text-lg">£ {{ gameData.players[4] }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Number of players to pay after P5 : </label>
              <div class="text-white text-lg">{{ gameData.numberOfPlayersToPayAfter5 }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Amount to share between remaining players : </label>
              <div class="text-white text-lg">£ {{ gameData.amountToShareBetweenRemainingPlayers }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Total amount to share between remaining players : </label>
              <div class="text-white text-lg">£ {{ gameData.amountToShareBetweenRemainingPlayers * gameData.numberOfPlayersToPayAfter5 }}</div>
            </div>
            <hr />
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Total Pot Value : </label>
              <div class="text-white text-lg">£ {{ currentGameDetails.pot_value }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Money to be Paid out : </label>
              <div class="text-white text-lg">£ {{ total }}</div>
            </div>
            <div class="group flex items-center justify-between w-full mx-auto">
              <label class="text-white text-md transition-colors duration-300 mr-3 font-bold">Balance Left (Profit) : </label>
              <div class="text-white text-lg">£ {{ currentGameDetails.pot_value - total }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-6">
        <button class="btn mt-5 w-full" type="button" @click="goBack()">Back</button>
        <button
          class="p-3 rounded card-header text-1xl text-center text-white mt-5 w-full bg-green-700 focus:outline-none"
          type="button"
          :disabled="isLoading"
          @click="updateWinners()"
        >
          {{ isLoading ? '... Processing' : 'Confirm' }}
        </button>
      </div>
    </template>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import swal from 'sweetalert2'
export default {
  name: 'ReviewGameForm',
  data: () => ({
    preview: false,
    isLoading: false,
    commanClasses:
      'bg-brand text-white px-2 h-8 mt-4 mb-4 flex-grow border-white transition-colors duration-300 border-b bg-transparent group-focus:border-red focus:outline-none focus:border-red',
    gameData: {
      gameId: 0,
      adminPercentage: 0,
      players: [0, 0, 0, 0, 0],
      numberOfPlayersToPayAfter5: 0,
      amountToShareBetweenRemainingPlayers: 0,
    },
  }),
  computed: {
    ...mapGetters('AdminGameStore', ['currentGameDetails']),
    total() {
      const total =
        this.gameData.players.reduce((a, b) => a + b, 0) +
        Math.floor((this.gameData.adminPercentage * this.currentGameDetails.pot_value) / 100) +
        this.gameData.amountToShareBetweenRemainingPlayers
      return total
    },
  },
  beforeMount() {
    if (this.currentGameDetails) {
      this.gameData.gameId = this.currentGameDetails.id
    }
  },
  methods: {
    ...mapActions('AdminGameStore', ['updateGameWinners']),
    isDisabled(playerNo) {
      return playerNo > this.currentGameDetails.players.length
    },
    goBack() {
      this.preview = false
    },
    validateForm() {
      const text = this.$t('required_fields_error')
      swal.fire({
        text,
        type: 'error',
        title: 'Fill all Fields',
        confirmButtonText: 'Ok',
        cancelButtonText: 'Cancel',
      })
      return false
    },

    previewMode() {
      if (
        this.gameData.adminPercentage === '' ||
        this.gameData.players[0] === '' ||
        this.gameData.players[1] === '' ||
        this.gameData.players[2] === '' ||
        this.gameData.players[3] === '' ||
        this.gameData.players[4] === '' ||
        this.gameData.numberOfPlayersToPayAfter5 === '' ||
        this.gameData.amountToShareBetweenRemainingPlayers === ''
      ) {
        this.validateForm()
        return false
      }

      if (this.currentGameDetails.players.length > 5 && this.gameData.numberOfPlayersToPayAfter5 > this.currentGameDetails.players.length - 5) {
        swal.fire({
          text: 'Number of players should be less than total players',
          type: 'error',
          title: 'Error',
          confirmButtonText: 'Ok',
          cancelButtonText: 'Cancel',
        })
        return
      }

      if (this.total > this.currentGameDetails.pot_value) {
        swal.fire({
          text: 'Money to be paid out is greater than total pot value',
          type: 'error',
          title: 'Error',
          confirmButtonText: 'Ok',
          cancelButtonText: 'Cancel',
        })
        return
      }

      const players = [...this.gameData.players]
      players.push(this.gameData.amountToShareBetweenRemainingPlayers)

      // console.log(players)
      for (const [index, val] of players.entries()) {
        if (index > 0 && players[index] > players[index - 1]) {
          swal.fire({
            text: 'Next prize should be less than previous one',
            type: 'error',
            title: 'Error',
            confirmButtonText: 'Ok',
            cancelButtonText: 'Cancel',
          })
          return
        }
      }
      this.preview = true
    },

    async updateWinners() {
      this.isLoading = true
      try {
        const payload = this.gameData

        const res = {
          gameId: this.currentGameDetails.id,
          details: payload,
        }
        await this.updateGameWinners(res)
        this.isLoading = false
        this.$emit('review-game')
      } catch (e) {
        const errors = e.response.data.message
        this.isLoading = false
        swal.fire({
          text: errors,
          type: 'error',
          title: 'Error',
          confirmButtonText: 'Ok',
          cancelButtonText: 'Cancel',
        })
      }
    },
  },
}
</script>

<style></style>
