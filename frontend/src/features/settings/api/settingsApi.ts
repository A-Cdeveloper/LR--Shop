import { api } from '@/lib/api';
import type { PublicSettings } from '@shop/api-types';

export const getPublicSettings = async (): Promise<PublicSettings> => {
  const response = await api.get<PublicSettings>('/shop-settings');
  return response.data;
};
