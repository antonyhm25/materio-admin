import axios from 'axios'
import store from '@/store'
import { useRouter } from '@/utils'

const domain = ""

const http = axios.create({
  domain,
  headers: {
    Accept: 'application/json'
  }
  // You can add your headers here
})


// Request interceptor
http.interceptors.request.use(request => {
  const token = store.getters['auth/token']
  if (token) {
    request.headers.common['Authorization'] = `Bearer ${token}`
  }

  request.headers.common['Accept'] = 'application/json'

  return request
})

// Response interceptor
http.interceptors.response.use(response => response, error => {
  const { status, data } = error.response

  if (process.env.NODE_ENV === 'development') {
    console.error(error)
  }

  if (status >= 500 || status == 422) {
    return Promise.reject(data)
  }

  if (status === 400) {
    return Promise.reject(data)
  }

  if (status === 403) {
    return Promise.reject(data)
  }

  if (status === 401 && store.getters['auth/check']) {
    store.commit('auth/LOGOUT')
    useRouter().push({ name: 'pages-login' })
  }

  return Promise.reject(error)
})

export default http;
