import { useCategory } from '../hooks/useCategory';
import Loading from '@/components/common/Loading';
import ErrorMessages from '@/components/common/ErrorMessages';

export const Category = ({ slug }: { slug: string }) => {
  const { category, isLoading, error } = useCategory(slug);

  if (isLoading) return <Loading className="w-12 h-12" title="Loading category..." />;
  if (error) return <ErrorMessages error={error} />;

  return (
    <div>
      <h1 className="text-2xl font-bold">{category?.name}</h1>
      <p className="text-sm text-gray-500">{category?.description}</p>
    </div>
  );
};
