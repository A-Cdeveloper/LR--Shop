import AppLayout from '@/components/layout/AppLayout';
import HomePage from '@/pages/HomePage';
import ErrorPage from '@/pages/ErrorPage';
import { createBrowserRouter } from 'react-router';
import NotFoundPage from '@/pages/NotFoundPage';

export const router = createBrowserRouter([
  {
    path: '/',
    Component: AppLayout,
    ErrorBoundary: ErrorPage,

    children: [
      {
        path: '/',
        Component: HomePage,
      },
    ],
  },
  { path: '*', Component: NotFoundPage },
]);
