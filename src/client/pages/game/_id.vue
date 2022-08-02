<template>
  <div v-if="loaded" id="game">
    <v-idle
      class="idle hidden"
      :events="['mousemove', 'touchmove', 'click', 'touchstart', 'touchend', 'touchcancel', 'touchmove', 'scroll', 'gesturechange']"
      :loop="true"
      :wait="0"
      :duration="130"
      @idle="onIdle"
    />
    <div class="section loader">
      <div class="loader__progress-bar">
        <div class="loader__progress-bar-fill"></div>
      </div>
    </div>
    <div class="app-container">
      <div class="section playing">
        <div class="playing__body">
          <div class="app-canvas">
            <canvas id="main-canvas"></canvas>
          </div>
          <div class="shooting-ended hidden">
            <div class="shooting-ended__container">
              <div class="shooting-ended__footer">
                <div class="button shooting-ended__continue" @click="start = true">
                  <div class="shooting-ended__continue-text">START GO</div>
                </div>
              </div>
            </div>
          </div>
          <div class="end-game hidden">
            <div class="end-game__container">
              <div class="end-game__header">SCORE</div>
              <div class="end-game__body">
                <div class="end-game__best-shot">Best shot: 0</div>
                <div class="end-game__timer">
                  <div class="end-game__timer-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18.667" viewbox="0 0 16 18.667">
                      <path
                        d="M15.167,1.5H9.833V3.278h5.333ZM11.611,13.056h1.778V7.722H11.611ZM18.749,7.18l1.262-1.262a9.821,9.821,0,0,0-1.253-1.253L17.5,5.927A8,8,0,1,0,18.749,7.18ZM12.5,18.389a6.222,6.222,0,1,1,6.222-6.222A6.218,6.218,0,0,1,12.5,18.389Z"
                        transform="translate(-4.5 -1.5)"
                        fill="#fff"
                      ></path>
                    </svg>
                  </div>
                  <div class="end-game__timer-time">0</div>
                </div>
                <div class="end-game__score">Score: 0</div>
              </div>
              <div class="end-game__footer">
                <div class="button end-game__home-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30.575" height="33.75" viewbox="0 0 30.575 33.75">
                    <g transform="translate(-3.5 -2)">
                      <path
                        d="M4.5,14.112,18.787,3,33.075,14.112V31.575A3.175,3.175,0,0,1,29.9,34.75H7.675A3.175,3.175,0,0,1,4.5,31.575Z"
                        transform="translate(0 0)"
                        fill="none"
                        stroke="#101114"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                      ></path>
                      <path
                        d="M13.5,33.875V18h9.525V33.875"
                        transform="translate(0.525 0.875)"
                        fill="none"
                        stroke="#101114"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                      ></path>
                    </g>
                  </svg>
                </div>
                <div class="button end-game__next">
                  <div class="end-game__next-text">Play again - {{ entryFee }} credit</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="playing__footer">
          <div class="playing__footer-left">
            <div class="playing__ball-icon"></div>
            <div class="playing__remaining-attempts">0</div>
          </div>
          <div class="playing__footer-center">
            <div class="button playing__speed-up">
              <svg xmlns="http://www.w3.org/2000/svg" width="35" height="30" viewbox="0 0 35 30">
                <path
                  d="M19.75,4.5a17.494,17.494,0,0,0-13.1,29.094c.086.094.164.188.25.273a1.968,1.968,0,0,0,2.9-.008,13.526,13.526,0,0,1,19.906,0,1.968,1.968,0,0,0,2.9.008l.25-.273A17.494,17.494,0,0,0,19.75,4.5ZM18.656,8.086a1.094,1.094,0,0,1,2.188,0V10.9a1.094,1.094,0,0,1-2.188,0Zm-10,15H5.844a1.094,1.094,0,1,1,0-2.187H8.656a1.094,1.094,0,0,1,0,2.188Zm4.023-8.164h0a1.1,1.1,0,0,1-1.547,0L9.141,12.938a1.1,1.1,0,0,1,0-1.547h0a1.1,1.1,0,0,1,1.547,0l1.992,1.992A1.094,1.094,0,0,1,12.68,14.922Zm12.547,2.7-3.711,5.9a2.422,2.422,0,0,1-.547.547,2.352,2.352,0,1,1-2.734-3.828l5.9-3.711a.8.8,0,0,1,.914,0A.786.786,0,0,1,25.227,17.617Zm3.141-2.7a1.1,1.1,0,0,1-1.547,0h0a1.1,1.1,0,0,1,0-1.547l1.992-1.992a1.1,1.1,0,0,1,1.547,0h0a1.1,1.1,0,0,1,0,1.547Zm5.289,8.164H30.844a1.094,1.094,0,0,1,0-2.187h2.812a1.094,1.094,0,0,1,0,2.188Z"
                  transform="translate(-2.25 -4.5)"
                  fill="#fff"
                ></path>
              </svg>
            </div>
          </div>
          <div class="playing__footer-right"></div>
        </div>
      </div>
    </div>
    <div class="section home hidden"></div>
    <div class="reusable-elements">
      <div class="playing__shoot">
        <div class="playing__shoot-number">Shoot 0:</div>
        <div class="playing__shoot-score">0</div>
        <div class="playing__shoot-timer">
          <div class="playing__shoot-timer-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18.667" viewbox="0 0 16 18.667">
              <path
                d="M15.167,1.5H9.833V3.278h5.333ZM11.611,13.056h1.778V7.722H11.611ZM18.749,7.18l1.262-1.262a9.821,9.821,0,0,0-1.253-1.253L17.5,5.927A8,8,0,1,0,18.749,7.18ZM12.5,18.389a6.222,6.222,0,1,1,6.222-6.222A6.218,6.218,0,0,1,12.5,18.389Z"
                transform="translate(-4.5 -1.5)"
                fill="#fff"
              ></path>
            </svg>
          </div>
          <div class="playing__shoot-timer-time">0</div>
        </div>
      </div>
    </div>
    <div v-if="showLowPower" class="low-power-mode-banner b-grey relative">
      <span class="close" @click="closeLowPowerNotification()">X</span>
      <h1>Your iPhone is on low power mode. this means you will have degradded performance. Please charge your phone for the best experience.</h1>
    </div>
    <base-modal :showing="invitationLinksModalVisibility" @close="invitationLinksModalVisibility = false">
      <template slot="title">
        <h1 class="text-white text-2xl font-bold mt-4">Invitation Link</h1>
        <IconLogo />
        <h2 class="text-2xl text-center font-bold mt-2 mb-4">Share your invite a friend link below and get 3 free credits when your friend plays.</h2>
      </template>
      <template slot="content">
        <div class="max-w-xl flex flex-col w-full">
          <div class="mb-4 text-center">
            <h2>{{ invitationCode }}</h2>
          </div>
          <button class="btn mt-5" type="button" @click="copyLink(invitationCode)">Copy Link</button>
        </div>
      </template>
    </base-modal>
  </div>
  <div v-else class="modal flex flex-col text-center">
    <p v-if="alreadyPlayingGameMessage" class="mb-8">{{ alreadyPlayingGameMessage }}</p>
    <div
      v-if="alreadyPlayingGame"
      class="block bg-gold hover:bg-brand text-brand hover:text-red font-bold py-2 px-4 rounded max-w-xs mx-auto focus:outline-none focus:shadow-outline cursor-pointer"
    >
      <button @click="back()">back</button>
    </div>
    <div
      v-if="youHaveBeenIdlingGameMessage"
      class="block bg-gold hover:bg-brand text-brand hover:text-red font-bold py-2 px-4 rounded max-w-xs mx-auto focus:outline-none focus:shadow-outline cursor-pointer"
    >
      <button @click="reload()">Reload</button>
    </div>
    <base-modal :showing="outOfCreditModal" @close="outOfCreditModal = false">
      <template slot="title">
        <h1 class="text-white text-2xl font-bold mt-4">You are out of credit</h1>
        <IconLogo />
        <h2 class="text-2xl text-center font-bold mt-2 mb-4">You have ran out of credits, would you like to:</h2>
      </template>
      <template slot="content">
        <div class="max-w-xl inline-flex">
          <!-- <nuxt-link :to="{ name: 'user-wallet' }" type="button"> -->
          <button
            class="
              card-button
              py-4
              px-8
              mb-4
              capitalize
              text-center
              bg-gold
              lg:hover:bg-red lg:hover:text-white
              transition-colors
              text-black
              focus:outline-none
              right-0
              relative
              ml-auto
              mr-5
            "
            type="button"
            @click="redirect('user-wallet')"
          >
            Buy Credits
          </button>
          <button
            class="
              card-button
              py-4
              px-8
              mb-4
              capitalize
              text-center
              bg-gold
              lg:hover:bg-red lg:hover:text-white
              transition-colors
              text-black
              focus:outline-none
              right-0
              relative
              ml-auto
            "
            type="button"
            @click="redirect('free-credits')"
          >
            Free Credits
          </button>
        </div>
      </template>
    </base-modal>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import ValidationServiceGameProxy from '../../services/ValidationServiceGameProxy'
import swal from 'sweetalert2'
import BaseModal from '~/components/Shared/BaseModal.vue'
import IconLogo from '~/components/svg/IconLogo'

export default {
  middleware: 'auth',
  components: { BaseModal, IconLogo },
  async asyncData({ store }) {
    await store.dispatch('auth/fetchUser')
  },
  data: () => ({
    showLowPower: false,
    title: process.env.appName,
    gveUrl: process.env.gveUrl,
    webSocketUrl: process.env.webSocketUrl,
    apiUrl: process.env.apiUrl,
    sessionToken: '',
    loaded: false,
    game: null,
    start: false,
    youHaveBeenIdlingGameMessage: false,
    alreadyPlayingGame: false,
    alreadyPlayingGameMessage: '',
    invitationLinksModalVisibility: false,
    invitationCode: '',
    ws: '',
    pingTimeout: '',
    outOfCreditModal: false,
    entryFee: 1,
  }),

  computed: {
    ...mapGetters('UserGameStore', ['gameInvitationLink']),
    ...mapGetters({
      user: 'auth/currentUser',
    }),
    getInvitationLink() {
      if (!this.gameInvitationLink) {
        return ''
      } else {
        return this.gameInvitationLink.invitation_link
      }
    },
  },
  beforeRouteLeave(to, from, next) {
    if (to && to.params.lostConnection) return this.gveDied(to, from, next)
    if (to && to.params.isBack) return this.handleRouteLeave(next, to)
    next()
  },
  created() {
    if (process.browser) {
      this.addListener()
      this.playGameById()
    }
    this.getInvitaionsLink()
  },
  beforeDestroy() {
    console.log('beforeDestroy...');
    if (this.game && this.game.dispose) {
      this.game.dispose()
      this.ws.close()
    }
    if (process.browser) {
      window.removeEventListener('beforeunload', this.beforeWindowUnload)
    }
  },
  methods: {
    ...mapActions('UserGameStore', ['getGameInvitationLink']),
    handleRouteLeave(next) {
      if (this.ws) {
        this.ws.close()
      }
      next()
    },
    reload() {
      window.location.reload()
    },
    back() {
      this.$router.push({ name: 'live-games', params: { isBack: true } })
    },
    async websockets(gameData) {
      const that = this
      console.log('called websockets()')
      this.ws = await connectToServer()

      this.ws.onmessage = (webSocketMessage) => {
        const messageBody = JSON.parse(webSocketMessage.data)
        console.log('MESSAGE RECIEVED:', messageBody)
        if (messageBody === 'true') {
          console.log('WebSocket message received:', webSocketMessage.data)
          this.loaded = false
          this.alreadyPlayingGame = true
          this.alreadyPlayingGameMessage = 'You are already playing this game.'
        } else if (messageBody === 'Connection Set in Websocket') {
          console.log('WebSocket message received:', JSON.parse(webSocketMessage.data))
          that.initGame(gameData)
          this.loaded = true
        }
        if (messageBody === 'ping') {
          console.log('pinged...')
          heartbeat()
        }
      }

      function redirectUser (){
        clearTimeout(that.pingTimeout)
        that.$router.push({ name: 'live-games', params: { lostConnection: true } })
      }

      function heartbeat() {
        console.log('that.pingTimeout:', that.pingTimeout)
        clearTimeout(that.pingTimeout)
        that.pingTimeout = setTimeout(() => {
          console.log('WEB SOCKET CONNECTION LOST...');
          redirectUser ()
        }, 3000 + 1200)
      }
      function connectToServer() {
        that.ws = new WebSocket(`${that.webSocketUrl}/GetSession`)
        that.ws.onopen = function () {
          console.log('Connection opened...')
        }
        that.ws.onclose = function clear() {
          console.log('Connection closed...');
        }
        return new Promise((resolve, reject) => {
          const timer = setInterval(() => {
            if (that.ws.readyState === 1) {
              console.log(that.ws.readyState)
              that.ws.send(JSON.stringify(gameData))
              clearInterval(timer)
              resolve(that.ws)
            } else {
              console.log('WEB SOCKET LOST')
            }
          }, 3000 + 1000)
        })
      }
    },
    addListener() {
      document.body.addEventListener('keydown', (e) => {
        if ((e.metaKey && e.key === '=') || e.key === '-') {
          e.preventDefault()
        }
      })
    },
    onIdle() {
      this.alreadyPlayingGameMessage = false
      this.youHaveBeenIdlingGameMessage = true
      this.ws.close()
      this.loaded = false
      this.alreadyPlayingGameMessage = 'You have been idling please go refresh..'
      this.game.dispose()
    },
    gveDied(to, from, next) {
      return new Promise((resolve, reject) => {
        if (process.browser) {
          return swal
            .fire({
              text: 'Your connection to the game engine was lost. Please go back and retry. If this persists please contact info@red6six.com',
              type: 'error',
              title: 'WARNING!',
              confirmButtonText: 'ok',
              showCancelButton: false,
              cancelButtonText: 'No Stay',
            })
            .then((result) => {
              resolve(result)
              if (result.value) {
                this.ws.close()
                next()
              } else {
                return false
              }
            })
        }
      })
    },
    /* eslint-disable */
    playGameById() {
      if (process.browser) {
        this.$store
          .dispatch('UserGameStore/playGameById', this.$route.params.id)
          .then((res) => {
            this.loaded = true
            return res
          })
          .then((res) => {
            this.sessionToken = res.game_player_token
            this.entryFee = res.map_data.data.entry_fee
            // this.initWebSocket({ gameId: this.$route.params.id, playerToken: res.game_player_token, userId: this.user.id })
            this.websockets({ gameId: this.$route.params.id, playerToken: res.game_player_token, userId: this.user.id })
          })
          .catch((e) => {
            if (e.response.status === 402) {
              this.outOfCreditModal = true
            } else {
              this.alreadyPlayingGame = true
              this.alreadyPlayingGameMessage = e.response.data.message
            }
          })
      }
    },
    initGame(gameData) {
      console.log('Attempting to initialize Game with Game Engine...', this.gveUrl)
      if (process.browser) {
        console.log(this.gveUrl)
        const ApiService = new ValidationServiceGameProxy(gameData.playerToken, this.gveUrl)
        const Game = require('red6six-game-ui').GameUI
        console.log('GAME UI REVISION', require('red6six-game-ui').REVISION)
        this.game = Game

        const gameEventListener = {
          on_leaderboard_button_click: () => {
            clearTimeout(this.pingTimeout)
            this.$router.push({ name: 'leaderboard', params: { isBack: true } })
          },
          on_play_again_button_click: () => {
            window.location.reload()
          },
        }
        Game.init(gameEventListener)

        const loader = document.querySelector('.loader')
        const progressBar = document.querySelector('.loader__progress-bar-fill')
        const checkResourceLoading = (batch, onResourcesLoaded) => {
          progressBar.style.width = `${batch.get_progress() * 100}%`

          if (batch.loading_finished) {
            if (batch.has_errors) {
              batch.print_errors()
            } else {
              onResourcesLoaded()
            }
          } else {
            setTimeout(function () {
              checkResourceLoading(batch, onResourcesLoaded)
            }, 200)
          }
        }
        const batch = Game.create_resource_batch()
        batch.add_texture('ball', '../game_assets/textures/main_ball.png')
        batch.add_texture('tracer_ball', '../game_assets/textures/tracer_ball.png')
        batch.add_texture('blue', '../game_assets/textures/blue.png')
        batch.add_texture('green', '../game_assets/textures/green.png')
        batch.add_texture('purple', '../game_assets/textures/purple.png')
        batch.add_texture('red', '../game_assets/textures/red.png')
        batch.add_texture('yellow', '../game_assets/textures/yellow.png')
        batch.add_texture('blue_noise', '../game_assets/textures/blue_noise.png')
        batch.add_texture('wall_frame', '../game_assets/textures/wall_frame.png')
        batch.add_texture('font_atlas', '../game_assets/sdf_fonts/atlas.png')
        batch.add_json('font_layout', '../game_assets/sdf_fonts/layout.json')
        batch.load(Game.resource_container)

        const onResourceReady = () => {
          loader.classList.add('hidden')
          Game.resource_loading_completed()
          Game.start()
          Game.set_online_level(ApiService)
        }
        checkResourceLoading(batch, onResourceReady)
      }
    },
    async getInvitaionsLink() {
      await this.getGameInvitationLink(this.$route.params.id)
      this.invitationCode = this.getInvitationLink
      if (this.invitationCode && this.gameInvitationLink.noOfTimesPlayed === 5) this.invitationLinksModalVisibility = true
    },
    copyLink(link) {
      event.currentTarget.innerText = 'Link Copied!'
      this.$copyText(link)
    },
    redirect(route) {
      this.$router.push({ name: route })
    },
  },
  head() {
    return {
      bodyAttrs: {
        class: 'game-page',
      },
      link: [
        {
          rel: 'stylesheet',
          href: '../game_assets/application.css',
        },
      ],
    }
  },
}
</script>

<style scoped lang="scss">
.idle {
  position: absolute;
  top: 0;
  z-index: 100;
}
.game-page {
  overflow: hidden;
  position: fixed;
  width: 100%;
  height: 100%;
}

.modal {
  width: 100%;
  height: 100%;
  position: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
}

.game {
  height: 100vh;
  padding-top: 12px;
}

.myVideo {
  display: none;
  visibility: hidden;
  z-index: -1;
  position: absolute;
  left: -99999px;
}

.low-power-mode-banner {
  background-color: #404040;
  padding: 1rem;
  z-index: 100;
  position: absolute;
  padding-top: 2rem;
  right: 0;
  left: 0;
  bottom: 0;
  transition: bottom 350ms ease-in-out;

  .close {
    position: absolute;
    right: 0.75rem;
    top: 0.5rem;
  }
}
</style>
