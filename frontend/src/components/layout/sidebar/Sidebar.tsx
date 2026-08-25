import { Link } from 'react-router';

const Sidebar = () => {
  return (
    <aside className="hidden min-w-[180px] shrink-0 gap-4  md:block">
      <h2 className="text-2xl font-bold mb-4 font-sans">Categories</h2>

      <div className="flex flex-col gap-2">
        <Link to="/categories/1" className="text-sm text-muted-foreground hover:text-foreground">
          Category 1
        </Link>
        <Link to="/categories/2" className="text-sm text-muted-foreground hover:text-foreground">
          Category 2
        </Link>
        <Link to="/categories/3" className="text-sm text-muted-foreground hover:text-foreground">
          Category 3
        </Link>
      </div>
    </aside>
  );
};

export default Sidebar;
