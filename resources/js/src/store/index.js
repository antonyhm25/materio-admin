import Vue from 'vue'
import Vuex from 'vuex'

import AuthModule from './modules/auth'
import RoleModule from './modules/role'
import UserModule from './modules/user'
import RestaurantModule from './modules/restaurant'
import MealModule from './modules/meal'
import MealDealModule from './modules/meal-deal'

Vue.use(Vuex)

const colorState = (status) => {
  let color = '#E53935'; // red
  switch(status) {
    case 500:
    case 400:
    case 422:
      color = '#E53935';
      break;
    case 403:
      color = '#FFEA00'
      break;
    case 201:
    case 204:
      color = '#43A047';
      break;
  }

  return color;
}

const SEND_NOTIFICATION = 'SEND_NOTIFICATION';
const CLOSE_NOTIFICATION = 'CLOSE_NOTIFICATION';

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
    [SEND_NOTIFICATION] (state, { message, status }) {
      state.notifyText = message;
      state.notifyColor = colorState(status);
      state.notifyIsVisible = true;
    },

    [CLOSE_NOTIFICATION] (state) {
      state.notifyIsVisible = false
    }
  },
  actions: {
    sendNotification({ commit }, { message, status }) {
      commit(SEND_NOTIFICATION, { message, status });

      setTimeout(() => commit(CLOSE_NOTIFICATION), 6000);
    }
  },
  modules: {
    auth: AuthModule,
    role: RoleModule,
    user: UserModule,
    restaurant: RestaurantModule,
    meal: MealModule,
    mealDeal: MealDealModule
  },
})
