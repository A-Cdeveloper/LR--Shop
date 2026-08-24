import { Link } from 'react-router';
import { LogIn, User } from 'lucide-react';
import UserCart from './UserCart';

// TODO: replace with real auth state
const isLoggedIn = false;

// TODO: replace with cart item count
const cartCount = 0;

const iconButtonClassName =
  'flex size-12 items-center justify-center rounded-full bg-muted text-foreground transition-colors hover:bg-muted/80';

const UserArea = () => {
  return (
    <div className="flex shrink-0 items-center gap-3">
      <UserCart cartCount={cartCount} />

      {isLoggedIn ? (
        <Link to="/account" aria-label="Account" className={iconButtonClassName}>
          <User className="size-5" strokeWidth={1.65} />
        </Link>
      ) : (
        <Link to="/login" aria-label="Log in" className={iconButtonClassName}>
          <LogIn className="size-5" strokeWidth={1.65} />
        </Link>
      )}
    </div>
  );
};

export default UserArea;
