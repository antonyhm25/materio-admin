import axios from '@/plugins/axios'

export const create = async (payload) => {
  try {
    const { data: { data } } = await axios.post('/api/restaurants', payload);

    return data;
  } catch (error) {
    throw error
  }
}

export const update = async (id, payload) => {
  try {
    await axios.put(`/api/restaurants/${id}`, payload);
  } catch (error) {
    throw error
  }
}

export const uploadPhoto = async (id, file) => {;
  try {
    const { data } = await axios.post(`/api/restaurants/${id}/photo`, file, {
      headers: { 'content-type': 'multipart/form-data' }
    });

    return data;
  } catch (error) {
    throw error
  }
}
