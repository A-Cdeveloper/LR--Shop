import { useQuery } from '@tanstack/react-query';
import { getCategories } from '../api/categoryApi';

export function useCategories() {
  const { data, isLoading, error } = useQuery({
    queryKey: ['categories'],
    queryFn: getCategories,
  });

  const categories = data?.data;

  return { categories, isLoading, error };
}
