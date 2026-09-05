import { getApiError, getApiErrorMessages } from '@/lib/apiError';
import { useCategory } from '../hooks/useCategory';

export const Category = ({ slug }: { slug: string }) => {
  const { category, isLoading, error } = useCategory(slug);

  const apiErrorMessages = getApiErrorMessages(getApiError(error));

  if (isLoading) return <div>Loading...</div>;
  if (error) return <h1 className="text-2xl font-bold">{apiErrorMessages}</h1>;

  return (
    <div>
      <h1 className="text-2xl font-bold">{category?.name}</h1>
      <p className="text-sm text-gray-500">{category?.description}</p>
    </div>
  );
};
