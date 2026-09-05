import CategoriesList from '@/features/categories/components/CategoriesList';
import { Link } from 'react-router-dom';

const Sidebar = () => {
  return (
    <aside className="hidden max-w-[180px] shrink-0 gap-4  md:block pe-5">
      <h2 className="text-lg font-bold mb-2 hover:text-primary leading-tight">
        <Link to="/categories">All Categories</Link>
      </h2>
      <CategoriesList />
    </aside>
  );
};

export default Sidebar;
