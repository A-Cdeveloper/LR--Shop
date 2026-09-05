import Loading from '@/components/common/Loading';
import { useCategories } from '../hooks/useCategories';
import CategoryListItem from './CategoryListItem';
import ErrorMessages from '@/components/common/ErrorMessages';

const CategoriesList = () => {
  const { categories, isLoading, error } = useCategories();

  if (isLoading) {
    return <Loading className="w-6 h-6" title="Loading categories..." />;
  }

  if (error) {
    return <ErrorMessages error={error} />;
  }
  return (
    <div className="flex flex-col gap-2">
      {categories?.map((category) => (
        <CategoryListItem key={category.id} category={category} />
      ))}
    </div>
  );
};

export default CategoriesList;
