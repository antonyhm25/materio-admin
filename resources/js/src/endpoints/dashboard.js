import axios from '@/plugins/axios';

export const restaurantMeals = async (id) => {
  try {
    const { data } = await axios.get(`/api/dashboards/${id}/meals`);
    return data;
  } catch (error) {
    throw error;
  }
}

export const meals = async (id) => {
  try {
    const { data } = await axios.get(`/api/dashboards/meals`);
    return data;
  } catch (error) {
    throw error;
  }
}
