import '@/plugins/vue-composition-api'
import '@resources/sass/styles/styles.scss'
import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import store from './store'

import '@/plugins/vee-validate'

import '@/plugins/debounce'

import dayjs from '@/plugins/dayjs'
Vue.prototype.$date = dayjs

Vue.config.productionTip = false

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount('#app')
