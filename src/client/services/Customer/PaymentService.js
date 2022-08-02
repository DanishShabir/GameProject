import AxiosService from '@/services/AxiosService'

const basePath = '/users'
export default {
  getCredit() {
    return AxiosService.get(`${basePath}/deposits`)
  },
}
