import { Category } from '@/features/categories/components/Category';
import { useParams } from 'react-router-dom';

const CategoryPage = () => {
  const { categoryName } = useParams();
  if (!categoryName) {
    return <div>Category not found</div>;
  }
  return <Category slug={categoryName ?? ''} />;
};

export default CategoryPage;
