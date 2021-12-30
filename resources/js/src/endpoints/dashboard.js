import axios from '@/plugins/axios';

export const restaurantMeals = async (id) => {
  try {
    const { data } = await axios.get(`/api/dashboards/${id}/restaurants`);
    return data;
  } catch (error) {
    throw error;
  }
}
