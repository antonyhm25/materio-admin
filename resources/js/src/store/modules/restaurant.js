import * as restaurantEndpoint from '@/endpoints/restaurants';

const state = {};

const getters = {};

const mutations = {};

const actions = {
  async createRestaurant({ dispatch }, payload) {
    try {
      const { id } = await restaurantEndpoint.create(payload);

      dispatch('sendNotification', {
        message: 'Restaurante registrado con éxito.',
        status: 201
      }, { root: true });

      return id;
    } catch (error) {
      throw error;
    }
  },

  async updateRestaurant({ dispatch }, { id, payload }) {
    try {
      await restaurantEndpoint.update(id, payload);

      dispatch('sendNotification', {
        message: 'Restaurante actualizado con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async uploadPhoto({ dispatch }, { id, file }) {
    try {
      const data = await restaurantEndpoint.uploadPhoto(id, file);
      return data;
    } catch (error) {
      throw error;
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
