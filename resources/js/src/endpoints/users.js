import axios from '@/plugins/axios'

export const paginate = async (query) => {
  try {
    const { data } = await axios.get(`/api/users${query}`);
    return data;
  } catch (error) {
    throw error;
  }
}

export const find = async (id) => {
  try {
    const { data: { data } } = await axios.get(`/api/users/${id}`);
    return data;
  } catch (error) {
    throw error;
  }
}

export const create = async (payload) => {
  try {
    const { data: { data } } = await axios.post('/api/users', payload);

    return data;
  } catch (error) {
    throw error;
  }
}

export const update = async (id, payload) => {
  try {
    const { data } = await axios.put(`/api/users/${id}`, payload);

    return data;
  } catch (error) {
    throw error;
  }
}

export const deleteUsers = async (ids) => {
  try {
    await axios.post('/api/users/delete-many', { ids });
  } catch (error) {
    throw error;
  }
}

export const passwordReset = async (id, payload) => {
  try {
    await axios.put(`/api/users/${id}/password-reset`, payload);
  } catch (error) {
    throw error;
  }
}

export const passwordChange = async (id, payload) => {
  try {
    await axios.put(`/api/users/${id}/password-change`, payload);
  } catch (error) {
    throw error;
  }
}
