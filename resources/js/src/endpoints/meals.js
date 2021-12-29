import axios from '@/plugins/axios';

export const paginate = async (id, query) => {
  try {
    const { data } = await axios.get(`/api/restaurants/${id}/meals${query}`);
    return data;
  } catch (error) {
    throw error;
  }
}

export const find = async (idRestaurant, id) => {
  try {
    const { data: { data } } = await axios.get(`/api/restaurants/${idRestaurant}/meals/${id}`);
    return data;
  } catch (error) {
    throw error;
  }
}

export const create = async (id, payload) => {
  try {
    const { data: { data } } = await axios.post(`/api/restaurants/${id}/meals`, payload);
    return data;
  } catch (error) {
    throw error;
  }
}

export const update = async (idRestaurant, id, payload) => {
  try {
    await axios.put(`/api/restaurants/${idRestaurant}/meals/${id}`, payload);
  } catch (error) {
    throw error;
  }
}

export const deleteMeals = async (idRestaurant, ids) => {
  try {
    await axios.post(`/api/restaurants/${idRestaurant}/meals/destroy-many`, { ids });
  } catch (error) {
    throw error;
  }
}

export const uploadPhoto = async (idRestaurant, id, file) => {;
  try {
    const { data } = await axios.post(`/api/restaurants/${idRestaurant}/meals/${id}/photo`, file, {
      headers: { 'content-type': 'multipart/form-data' }
    });

    return data;
  } catch (error) {
    throw error
  }
}
