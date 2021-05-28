import { clientUserToken } from '@/constants/AppTokens'

export default function ({ route, store, redirect }) {
  if (!route.matched.length) {
    return redirect(404, '/error')
  }
  // If user is not authenticated
  if (!store.getters['auth/auth']) {
    store.commit('auth/purgeAuth')
    return redirect('/auth')
  } else if (route.path === '/auth') {
    redirect('/')
  } else {
    redirect(route.query.next)
  }
  const userData = JSON.parse(localStorage.getItem(clientUserToken))
  // if user switched to different account
  if (store.getters['auth/userData'] && userData && store.getters['auth/userData'].id !== userData.id) {
    store.commit('auth/purgeAuth')
    return redirect('/')
  }
}
