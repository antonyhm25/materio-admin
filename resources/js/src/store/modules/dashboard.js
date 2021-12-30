import * as dashboardsEndpoint from '@/endpoints/dashboard'

const SET_RESTAURANT_MEALS = 'SET_RESTAURANT_MEALS';

const state = {
  restaurantMeals: {
    meals: 0,
    types: [
      { status: 'available', total: 0 },
      { status: 'reserved', total: 0 },
      { status: 'delivered', total: 0 },
    ]
  }
};

const getters = {
  restaurantMeals: state => state.restaurantMeals
};

const mutations = {
  [SET_RESTAURANT_MEALS] (state, payload) {
    state.restaurantMeals.meals = payload.meals;

    payload.types.forEach(e => {
      let type = state.restaurantMeals.types.find(l => l.status === e.status);
      if (type) {
        type.total = e.total;
      }
    });
  }
};

const actions = {
  async getRestaurantMeals({ commit }, id) {
    commit(SET_RESTAURANT_MEALS, {
      meals: 0,
      types: [
        { status: 'available', total: 0 },
        { status: 'reserved', total: 0 },
        { status: 'delivered', total: 0 },
      ]
    });

    try {
      const data = await dashboardsEndpoint.restaurantMeals(id);

      commit(SET_RESTAURANT_MEALS, data);
    } catch (error) {
      throw error;
    }
  },

  async getMeals({ commit }) {
    commit(SET_RESTAURANT_MEALS, {
      meals: 0,
      types: [
        { status: 'available', total: 0 },
        { status: 'reserved', total: 0 },
        { status: 'delivered', total: 0 },
      ]
    });

    try {
      const data = await dashboardsEndpoint.meals();

      commit(SET_RESTAURANT_MEALS, data);
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
