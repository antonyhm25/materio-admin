import Cookies from 'js-cookie'

import * as authEndpoint from '@/endpoints/auth'

// state
const state = {
  user: JSON.parse(localStorage.getItem('userData')),
  token: Cookies.get('token')
}

// getters
const getters = {
  user: state => state.user,
  token: state => state.token,
  check: state => state.user !== null,
  hasPermissions: state => {
    return (permissions) => {
      if (!state.user) return false;

      return state.user.permissions.some(e => permissions.includes(e));
    }
  }
}

const mutations = {
  ['SAVE_TOKEN'] (state, { token }) {
    state.token = token

    Cookies.set('token', token)
  },

  ['FETCH_USER_SUCCESS'] (state, { user }) {
    state.user = user

    localStorage.setItem('userData', JSON.stringify(state.user))
  },

  ['FETCH_USER_FAILURE'] (state) {
    state.token = null
    Cookies.remove('token')
  },

  ['LOGOUT'] (state) {
    state.user = null
    state.token = null

    Cookies.remove('token')
    localStorage.removeItem('userData')
    localStorage.removeItem('expirationToken')
  },

  ['UPDATE_USER'] (state, { user }) {
    state.user = user
  },

  ['UPDATE_PHOTO_RESTAURANT'](state, photo) {
    if (state.user && state.user.restaurant) {
      state.user.restaurant.photo = photo;

      const localUser = JSON.parse(localStorage.getItem('userData'));
      localUser.restaurant.photo = photo;
      localStorage.setItem('userData', JSON.stringify(localUser));
    }
  }
}

// actions
const actions = {
  async login({ commit }, { email, password, deviceName }) {
    try {
      const token = await authEndpoint.login({ email, password, deviceName });
      if (token) {
        commit('SAVE_TOKEN', { token });

        const userInfo = await authEndpoint.userAuth();

        commit('FETCH_USER_SUCCESS', { user: userInfo });
      }
    } catch (error) {
      commit('FETCH_USER_FAILURE');
      throw error;
    }
  },

  saveToken ({ commit }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit }) {
    try {
      await authEndpoint.logout();
      commit('LOGOUT');
      commit('user/SET_USER', null);
    } catch (error) {
      throw error;
    }
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
}
