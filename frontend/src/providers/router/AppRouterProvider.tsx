import { RouterProvider } from 'react-router';
import { router } from './router';

const AppRouterProvider = () => {
  return <RouterProvider router={router} />;
};

export default AppRouterProvider;
