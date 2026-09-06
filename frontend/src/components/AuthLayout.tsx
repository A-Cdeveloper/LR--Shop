import { Suspense } from 'react';
import { getToken } from '@/lib/token';
import { Navigate, Outlet } from 'react-router-dom';
import Loading from '@/components/common/Loading';

const AuthLayout = () => {
  const token = getToken();

  if (token) {
    return <Navigate to="/" replace />;
  }

  return (
    <div className="flex flex-col items-center justify-center h-screen gap-4">
      <Suspense fallback={<Loading className="py-12" title="Loading..." />}>
        <Outlet />
      </Suspense>
    </div>
  );
};

export default AuthLayout;
