import * as CONST from './mutations/Customer'
import ProfileService from '@/services/Customer/ProfileService'
// state
export const state = () => ({})

// getters
export const getters = {}

// mutations
export const mutations = {
  [CONST.START_UPDATE_PERSONAL_INFO]: (state) => {},
  [CONST.FINISH_UPDATE_PERSONAL_INFO]: (state, payload) => {},
  [CONST.ERROR_UPDATE_PERSONAL_INFO]: (state) => {},
  //  Mutations for Customer Password Update
  [CONST.START_CUSTOMER_PASSWORD_UPDATE]: (state) => {},
  [CONST.FINISH_USTOMER_PASSWORD_UPDATE]: (state, payload) => {},
  [CONST.ERROR_CUSTOMER_PASSWORD_UPDATE]: (state) => {},
}

// actions
export const actions = {
  customerProfileUpdate({ commit, rootGetters }, payload) {
    commit(CONST.START_UPDATE_PERSONAL_INFO)
    try {
      return new Promise((resolve, reject) => {
        ProfileService.updateProfile(
          rootGetters['auth/currentUser'].id,
          payload
        )
          .then((response) => {
            const userData = response.data.data
            commit(CONST.FINISH_UPDATE_PERSONAL_INFO, userData)
            commit('auth/FETCH_USER_SUCCESS', userData, {
              root: true,
            })
            resolve(response.data.data)
          })
          .catch((error) => {
            commit(CONST.ERROR_UPDATE_PERSONAL_INFO, error)

            reject(error)
          })
      })
    } catch (e) {
      commit(CONST.ERROR_UPDATE_PERSONAL_INFO, e)
    }
  },
  customerPasswordUpdate({ commit, rootGetters }, payload) {
    commit(CONST.START_CUSTOMER_PASSWORD_UPDATE)
    try {
      return new Promise((resolve, reject) => {
        ProfileService.updatePassword(
          rootGetters['auth/currentUser'].id,
          payload
        )
          .then((response) => {
            const userData = response.data.data
            commit(CONST.FINISH_USTOMER_PASSWORD_UPDATE, userData)
            resolve(response.data.data)
          })
          .catch((error) => {
            commit(CONST.ERROR_CUSTOMER_PASSWORD_UPDATE, error)

            reject(error)
          })
      })
    } catch (e) {
      commit(CONST.ERROR_CUSTOMER_PASSWORD_UPDATE, e)
    }
  },
}
