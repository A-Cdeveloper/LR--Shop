import { useQuery } from '@tanstack/react-query';
import { getPublicSettings } from '../api/settingsApi';

export const usePublicSettings = () => {
  const { data, isLoading, error } = useQuery({
    queryKey: ['public-settings'],
    queryFn: getPublicSettings,
  });
  return { data, isLoading, error };
};
