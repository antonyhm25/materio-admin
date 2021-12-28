import axios from '@/plugins/axios'

export const permissionsByRole = async (role) => {
  try {
    const { data } = await axios.get(`/api/roles/${role}/permissions`);
    return data;
  } catch (errror) {
    throw errror
  }
}
