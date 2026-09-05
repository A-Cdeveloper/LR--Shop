import { getToken } from '@/lib/token';
import { Navigate, Outlet, useLocation } from 'react-router';

const ProtectedRoute = () => {
  const location = useLocation();
  const token = getToken();

  if (!token) {
    return <Navigate to="/login" replace state={{ from: location }} />;
  }

  return <Outlet />;
};

export default ProtectedRoute;
