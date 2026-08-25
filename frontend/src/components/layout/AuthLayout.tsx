import { Outlet } from 'react-router-dom';

const AuthLayout = () => {
  return (
    <div className="flex flex-col items-center justify-center h-screen gap-4">
      <Outlet />
    </div>
  );
};

export default AuthLayout;
