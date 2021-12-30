import axios from '@/plugins/axios'

export const paginate = async (id, query) => {
  try {
    const { data } = await axios.get(`/api/restaurants/${id}/meal-deals${query}`);
    return data;
  } catch (error) {
    throw error;
  }
}

export const create = async (id, payload) => {
  try {
    const { data: { data } } = await axios.post(`/api/restaurants/${id}/meal-deals`, payload);

    return data;
  } catch (error) {
    throw error;
  }
}

export const update = async (idRestaurant, id, payload) => {
  try {
    await axios.put(`/api/restaurants/${idRestaurant}/meal-deals/${id}`, payload);
  } catch (error) {
    throw error;
  }
}

export const destroy = async (idRestaurant, id) => {
  try {
    await axios.delete(`/api/restaurants/${idRestaurant}/meal-deals/${id}`);
  } catch (error) {
    throw error;
  }
}
