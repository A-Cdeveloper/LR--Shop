import { Link } from 'react-router-dom';
import { useCategories } from '../hooks/useCategories';
import CategoryListItem from './CategoryListItem';

const CategoriesList = () => {
  const { categories, isLoading, error } = useCategories();

  if (isLoading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error.message}</div>;
  }
  return (
    <div className="flex flex-col gap-2">
      <>
        <Link to="/categories" className="text-sm text-muted-foreground hover:text-foreground">
          All Categories
        </Link>
        {categories?.map((category) => (
          <CategoryListItem key={category.id} category={category} />
        ))}
      </>
    </div>
  );
};

export default CategoriesList;
