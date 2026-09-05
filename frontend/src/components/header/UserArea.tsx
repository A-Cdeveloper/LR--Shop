import { Button } from '@shop/ui';
import { useLogout } from '@/features/auth/hooks/useLogout';
import { getToken } from '@/lib/token';
import { Loader2, LogIn, LogOut, User } from 'lucide-react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router';
import UserCart from './UserCart';

// TODO: replace with cart item count
const cartCount = 0;

const iconButtonClassName =
  'flex size-12 items-center justify-center rounded-full bg-muted text-foreground transition-colors hover:bg-muted/80';

const UserArea = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(() => !!getToken());
  const navigate = useNavigate();
  const { logoutHandler, isPending } = useLogout();

  const logoutUser = () => {
    logoutHandler(undefined, {
      onSettled: () => {
        setIsLoggedIn(false);
        navigate('/');
      },
    });
  };

  return (
    <div className="flex shrink-0 items-center gap-3">
      <UserCart cartCount={cartCount} />

      {isLoggedIn ? (
        <>
          <Link to="/account" aria-label="Account" className={iconButtonClassName}>
            <User className="size-5" strokeWidth={1.65} />
          </Link>
          <Button
            aria-label="Logout"
            type="button"
            className={iconButtonClassName}
            onClick={logoutUser}
            disabled={isPending}
          >
            {isPending ? (
              <Loader2 className="size-5 animate-spin" strokeWidth={1.65} />
            ) : (
              <LogOut className="size-5" strokeWidth={1.65} />
            )}
          </Button>
        </>
      ) : (
        <Link to="/login" aria-label="Log in" className={iconButtonClassName}>
          <LogIn className="size-5" strokeWidth={1.65} />
        </Link>
      )}
    </div>
  );
};

export default UserArea;
