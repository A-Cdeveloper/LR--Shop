import { Link } from 'react-router-dom';
import { ShoppingBag } from 'lucide-react';
import { Badge } from '@/components/ui/badge';

const iconButtonClassName =
  'flex size-12 items-center justify-center rounded-full bg-muted text-foreground transition-colors hover:bg-muted/80';

const UserCart = ({ cartCount }: { cartCount: number }) => {
  return (
    <Link
      to="/cart"
      aria-label={`Cart, ${cartCount} items`}
      className={`relative ${iconButtonClassName}`}
    >
      <ShoppingBag className="size-5" strokeWidth={1.65} />
      <Badge
        variant="default"
        className="absolute -top-0.5 -right-0.5 flex size-5 min-w-5 items-center justify-center rounded-full px-0 text-[10px] leading-none"
      >
        {cartCount}
      </Badge>
    </Link>
  );
};

export default UserCart;
