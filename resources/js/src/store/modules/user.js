import * as userEndpoint from '@/endpoints/users';

const SET_USERS = 'SET_USERS';
const SET_USER = 'SET_USER';

const state = {
  users: [],
  totalItems: 0,
  currentUser: { permissions: [] }
};

const getters = {
  users: state => state.users,
  totalItems: state => state.totalItems,
  currentUser: state => state.currentUser
};

const mutations = {
  [SET_USERS] (state, { users, total }) {
    state.users = users;
    state.totalItems = total;
  },

  [SET_USER] (state, user) {
    state.currentUser = user;
  }
};

const actions = {
  async getUsers({ commit }, query) {
    try {
      const { data, total } = await userEndpoint.paginate(query);

      commit(SET_USERS, {
        users: data,
        total
      })
    } catch (error) {
      throw error;
    }
  },

  async getUser({ commit }, id) {
    try {
      const data  = await userEndpoint.find(id);

      commit(SET_USER, data);
    } catch (error) {
      throw error;
    }
  },

  async createUser({ dispatch }, payload) {
    try {
      const { id } = await userEndpoint.create(payload);

      dispatch('sendNotification', {
        message: 'Usuario registrado con éxito.',
        status: 201
      }, { root: true });

      return id;
    } catch (error) {
      throw error;
    }
  },

  async updateUser({ commit, dispatch }, { id, payload }) {
    try {
      await userEndpoint.update(id, payload);

      dispatch('sendNotification', {
        message: 'Usuario actualizado con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async deleteUsers({ dispatch }, ids) {
    try {
      await userEndpoint.deleteUsers(ids);

      dispatch('sendNotification', {
        message: 'Usuarios eliminados con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async passwordReset({ dispatch }, { id, payload }) {
    try {
      await userEndpoint.passwordReset(id, payload);

      dispatch('sendNotification', {
        message: 'La contraseña se reestableció con éxito.',
        status: 204
      }, { root: true });
    } catch (error) {
      throw error;
    }
  },

  async passwordChange({ dispatch }, { id, payload }) {
    try {
      await userEndpoint.passwordChange(id, payload);

      dispatch('sendNotification', {
        message: 'La contraseña se cambió con éxito.',
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
