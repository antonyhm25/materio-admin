import * as mealEndpoint from '@/endpoints/meals'


const SET_MEALS = 'SET_MEALS';

const state = {
  meals: [],
  totalItems: 0
};

const getters = {
  meals: state => state.meals,
  totalItems: state => state.totalItems
};

const mutations = {
  [SET_MEALS] (state, { meals, total }) {
    state.meals = meals;
    state.totalItems = total
  }
};

const actions = {
  async getMeals({ commit }, { id, query }) {
    try {
      const { data, total } = await mealEndpoint.paginate(id, query);

      commit(SET_MEALS, {
        meals: data,
        total
      })
    } catch (error) {
      throw error;
    }
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
