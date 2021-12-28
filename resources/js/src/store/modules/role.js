import * as rolesEndpoint from '@/endpoints/roles'

const SET_PERMISSIONS = 'SET_PERMISSIONS';

const state = {
  permissions: []
};

const mutations = {
  [SET_PERMISSIONS] (state, permissions) {
    state.permissions = permissions;
  }
};

const actions = {
  async getPermissionsByRole({ commit }, role) {
    try {
      const permission = await rolesEndpoint.permissionsByRole(role);

      commit(SET_PERMISSIONS, permission.data);
    } catch (error) {
      commit('SEND_NOTIFY', {
        message: error,
        color: '#E53935' // red darken-1
      }, { root: true });
    }
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
