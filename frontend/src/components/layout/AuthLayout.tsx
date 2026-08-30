import { getToken } from '@/lib/token';
import { Navigate, Outlet } from 'react-router-dom';

const AuthLayout = () => {
  const token = getToken();

  if (token) {
    return <Navigate to="/" replace />;
  }

  return (
    <div className="flex flex-col items-center justify-center h-screen gap-4">
      <Outlet />
    </div>
  );
};

export default AuthLayout;
