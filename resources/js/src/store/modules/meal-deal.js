import * as mealDealEndpoint from '@/endpoints/meal-deals'

const SET_MEAL_DEALS = 'SET_MEAL_DEALS';

const state = {
  mealDeals: [],
  totalItems: 0,
};

const getters = {
  mealDeals: state => state.mealDeals,
  totalItems: state => state.totalItems,
};

const mutations = {
  [SET_MEAL_DEALS] (state, { items, total }) {
    state.mealDeals = items;
    state.totalItems = total;
  }
};

const actions = {
  async getMealDeals({ commit }, {id, query}) {
    try {
      const { data, meta: { total } } = await mealDealEndpoint.paginate(id, query);

      commit(SET_MEAL_DEALS, {
        items: data,
        total
      })
    } catch (error) {
      throw error;
    }
  },

  async createMealDeal({ dispatch },{ id, payload }) {
    try {
      await mealDealEndpoint.create(id, payload);

      dispatch('sendNotification', {
        message: 'Oferta registrada con éxito.',
        status: 201
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async updateMealDeal({ commit, dispatch }, { idRestaurant, id, payload }) {
    try {
      await mealDealEndpoint.update(idRestaurant, id, payload);

      dispatch('sendNotification', {
        message: 'Oferta actualizada con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async deleteMealDeal({ commit, dispatch }, {idRestaurant, id}) {
    try {
      await mealDealEndpoint.destroy(idRestaurant, id);

      dispatch('sendNotification', {
        message: 'Oferta eliminada con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async delivery({ commit, dispatch }, { idRestaurant, id, payload }) {
    try {
      await mealDealEndpoint.update(idRestaurant, id, payload);

      dispatch('sendNotification', {
        message: 'Oferta de platillo entregado.',
        status: 204
      }, { root: true });
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
