import { useQuery } from '@tanstack/react-query';
import { getCategoryBySlug } from '../api/categoryApi';

export function useCategory(slug: string) {
  const {
    data: category,
    isLoading,
    error,
  } = useQuery({
    queryKey: ['category', slug],
    queryFn: () => getCategoryBySlug(slug),
  });

  return { category, isLoading, error };
}
