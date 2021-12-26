import Vue from 'vue'
import Vuex from 'vuex'

import AuthModule from './modules/auth'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    notifyText: '',
    notifyIsVisible: false,
    notifyColor: 'dark'
  },
  getters: {
    notifyText: state => state.notifyText,
    notifyIsVisible: state => state.notifyIsVisible,
    notifyColor: state => state.notifyColor,
  },
  mutations: {
    ['SEND_NOTIFY'] (state, { message, color }) {
      state.notifyText = message;
      state.notifyColor = color;
      state.notifyIsVisible = true;
    },

    ['CLOSE_NOTIFY'] (state) {
      state.notifyIsVisible = false
    }
  },
  actions: {},
  modules: {
    auth: AuthModule
  },
})
