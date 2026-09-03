import { api } from '@/lib/api';
import type { PublicSettings } from '../types/publicSettings';

export const getPublicSettings = async (): Promise<PublicSettings> => {
  const response = await api.get<PublicSettings>('/shop-settings');
  return response.data;
};
