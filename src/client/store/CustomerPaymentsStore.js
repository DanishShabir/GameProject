import * as CONST from './mutations/Customer'
import PaymentService from '@/services/Customer/PaymentService'
// state
export const state = () => ({
  customerCredit: [],
})

// getters
export const getters = {
  allCustomerCredits: (state) => state.customerCredit,
}

// mutations
export const mutations = {
  // Customer Credit History Mutations
  [CONST.GET_CUSTOMER_CREDIT]: (state) => {},
  [CONST.SET_CUSTOMER_CREDIT]: (state, payload) => {
    state.customerCredit = payload
  },
  [CONST.ERROR_CUSTOMER_CREDIT]: (state) => {},
}

// actions
export const actions = {
  getCustomerCredit({ commit }) {
    commit(CONST.GET_CUSTOMER_CREDIT)
    try {
      return new Promise((resolve, reject) => {
        PaymentService.getCredit()
          .then((response) => {
            commit(CONST.SET_CUSTOMER_CREDIT, response.data.data)
            resolve(response.data.data)
          })
          .catch((error) => {
            commit(CONST.ERROR_CUSTOMER_CREDIT, error)

            reject(error)
          })
      })
    } catch (e) {
      commit(CONST.ERROR_CUSTOMER_CREDIT, e)
    }
  },
}
