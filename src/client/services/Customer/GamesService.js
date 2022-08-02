import AxiosService from '@/services/AxiosService'

const basePath = '/users/games'
export default {
  /**
   * Method to get all games for a customer
   */
  getAllGames() {
    return AxiosService.get(`${basePath}`)
  },
  getCurrentGames() {
    return AxiosService.get(`${basePath}`)
  },
}
