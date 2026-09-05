import { Link } from 'react-router-dom';
import { useCategories } from '../hooks/useCategories';

const CategoriesGrid = () => {
  const { categories, isLoading, error } = useCategories();

  if (isLoading) {
    return <div>Loading...</div>;
  }

  if (error) {
    return <div>Error: {error.message}</div>;
  }

  if (categories?.length === 0) {
    return <div>No categories found</div>;
  }

  return (
    <div className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 w-full">
      {categories?.map((category) => (
        <Link
          to={`/categories/${category.slug ?? ''}`}
          key={category.id}
          className="bg-white rounded-lg shadow-sm flex flex-col gap-2 relative overflow-hidden"
        >
          <div className="absolute left-0 bottom-0 right-0 bg-black/60  z-10 text-center py-2">
            <h2 className="text-[14px] text-white">{category.name}</h2>
          </div>
          <div className="w-full h-64 overflow-hidden">
            <img
              src={category.image ?? ''}
              alt={category.name ?? ''}
              className="w-full h-64 object-cover rounded-lg z-0 object-center hover:scale-110 transition-all duration-300"
            />
          </div>
        </Link>
      ))}
    </div>
  );
};

export default CategoriesGrid;
