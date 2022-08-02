import * as CONST from './mutations/Customer'
import GamesService from '@/services/Customer/GamesService'
// state
export const state = () => ({
  customerGames: [],
  customerCurrentGames: [],
  currentGameId: '',
})

// getters
export const getters = {
  customerAllGames: (state) => state.customerGames,
  customerAllCurrentGames: (state) => state.customerCurrentGames,
  customerCurrentGameScore: () => [],
}

// mutations
export const mutations = {
  [CONST.GET_CUSTOMER_GAMES]: (state) => {},
  [CONST.SET_CUSTOMER_GAMES]: (state, payload) => {
    state.customerGames = payload
  },
  [CONST.ERROR_CUSTOMER_GAMES]: (state) => {},
  // Customer Current Games Mutations
  [CONST.GET_CUSTOMER_CURRENT_GAMES]: (state) => {},
  [CONST.SET_CUSTOMER_CURRENT_GAMES]: (state, payload) => {
    state.customerCurrentGames = payload
  },
  [CONST.ERROR_CUSTOMER_CURRENT_GAMES]: (state) => {},
}

// actions
export const actions = {
  getCustomerGames({ commit }) {
    commit(CONST.GET_CUSTOMER_GAMES)
    try {
      return new Promise((resolve, reject) => {
        GamesService.getAllGames()
          .then((response) => {
            commit(CONST.SET_CUSTOMER_GAMES, response.data.data)
            resolve(response.data.data)
          })
          .catch((error) => {
            commit(CONST.ERROR_CUSTOMER_GAMES, error)

            reject(error)
          })
      })
    } catch (e) {
      commit(CONST.ERROR_CUSTOMER_GAMES, e)
    }
  },
  // Customer Current Games Action
  getCustomerCurrentGames({ commit }) {
    commit(CONST.GET_CUSTOMER_CURRENT_GAMES)
    try {
      return new Promise((resolve, reject) => {
        GamesService.getCurrentGames()
          .then((response) => {
            commit(CONST.SET_CUSTOMER_CURRENT_GAMES, response.data.data)
            resolve(response.data.data)
          })
          .catch((error) => {
            commit(CONST.ERROR_CUSTOMER_CURRENT_GAMES, error)

            reject(error)
          })
      })
    } catch (e) {
      commit(CONST.ERROR_CUSTOMER_CURRENT_GAMES, e)
    }
  },
}
