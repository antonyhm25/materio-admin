import axios from '@/plugins/axios';

export const paginate = async (id, query) => {
  try {
    const { data } = await axios.get(`/api/restaurants/${id}/meals${query}`);
    return data;
  } catch (error) {
    throw error;
  }
}
