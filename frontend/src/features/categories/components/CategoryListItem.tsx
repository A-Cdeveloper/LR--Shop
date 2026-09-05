import type { Category } from '@shop/api-types';
import { NavLink } from 'react-router-dom';

type CategoryListItemProps = {
  category: Category;
};

const normalItemClasses = 'text-sm text-muted-foreground hover:text-primary';
const activeItemClasses = 'text-sm hover:text-primary text-primary';
const pendingItemClasses = 'text-sm text-muted-foreground hover:text-primary';

const CategoryListItem = ({ category }: CategoryListItemProps) => {
  return (
    <NavLink
      to={`/categories/${category.slug}`}
      className={({ isActive, isPending }) =>
        `border-t border-primary/20 pt-2 font-semibold ${isActive ? activeItemClasses : isPending ? pendingItemClasses : normalItemClasses}`
      }
    >
      {category.name} ({category.products_count})
    </NavLink>
  );
};

export default CategoryListItem;
