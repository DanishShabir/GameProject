export default ({ store, route, redirect }) => {
  if (store.getters['auth/isAuthenticated']) {
    return redirect('/')
  }
}
