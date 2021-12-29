import * as mealEndpoint from '@/endpoints/meals'

const SET_MEALS = 'SET_MEALS';
const SET_MEAL = 'SET_MEAL';

const state = {
  meals: [],
  totalItems: 0,
  currentMeal: {}
};

const getters = {
  meals: state => state.meals,
  totalItems: state => state.totalItems,
  currentMeal: state => state.currentMeal,
};

const mutations = {
  [SET_MEALS] (state, { meals, total }) {
    state.meals = meals;
    state.totalItems = total;
  },

  [SET_MEAL] (state, meal) {
    state.currentMeal = meal;
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
  },

  async getMeal({ commit }, { idRestaurant, id}) {
    try {
      const data  = await mealEndpoint.find(idRestaurant, id);

      commit(SET_MEAL, data);
    } catch (error) {
      throw error;
    }
  },

  async createMeal({ dispatch }, { idRestaurant, payload }) {
    try {
      const { id } = await mealEndpoint.create(idRestaurant, payload);

      dispatch('sendNotification', {
        message: 'Platillo registrado con éxito.',
        status: 201
      }, { root: true });

      return id;
    } catch (error) {
      throw error;
    }
  },

  async updateMeal({ dispatch }, { idRestaurant, id, payload }) {
    try {
      await mealEndpoint.update(idRestaurant, id, payload);

      dispatch('sendNotification', {
        message: 'Platillo actualizado con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async deleteMeals({ dispatch }, { idRestaurant, ids }) {
    try {
      await mealEndpoint.deleteMeals(idRestaurant, ids);

      dispatch('sendNotification', {
        message: 'Platillos eliminados con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async uploadPhoto({ dispatch }, { idRestaurant, id, file }) {
    try {
      await mealEndpoint.uploadPhoto(idRestaurant, id, file);
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
