import { Suspense } from 'react';
import { RouterProvider } from 'react-router';
import Loading from '@/components/common/Loading';
import { router } from './router';

const AppRouterProvider = () => {
  return (
    <Suspense fallback={<Loading title="Loading page..." />}>
      <RouterProvider router={router} />
    </Suspense>
  );
};

export default AppRouterProvider;
