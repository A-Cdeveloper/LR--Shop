import { Navigate, Outlet, useLocation } from 'react-router';

const ProtectedRoute = () => {
  const location = useLocation();
  //const token = localStorage.getItem('auth_token'); // kasnije: useAuth()
  const token = false;

  if (!token) {
    return <Navigate to="/login" replace state={{ from: location }} />;
  }

  return <Outlet />;
};

export default ProtectedRoute;
