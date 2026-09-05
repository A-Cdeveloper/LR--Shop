import CategoriesList from '@/features/categories/components/CategoriesList';

const Sidebar = () => {
  return (
    <aside className="hidden max-w-[180px] shrink-0 gap-4  md:block pe-5">
      <CategoriesList />
    </aside>
  );
};

export default Sidebar;
