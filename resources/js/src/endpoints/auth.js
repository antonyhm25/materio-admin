import axios from '@/plugins/axios'

export const login = async ({ email, password, deviceName }) => {
  try {
    const payload = { email, password, deviceName };

    const { data } = await axios.post('/api/auth/admin/login', payload);

    return data;
  } catch (error) {
    throw error;
  }
}

export const userAuth = async () => {
  try {
    const { data } = await axios.get('/api/auth/me');

    return data;
  } catch (error) {
    throw error;
  }
}

export const logout =  async () => {
  try {
    await axios.post('/api/auth/logout');
  } catch (error) {
    throw error;
  }
}
