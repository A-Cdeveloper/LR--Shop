import { lazy } from 'react';
import AppLayout from '@/components/AppLayout';
import AuthLayout from '@/components/AuthLayout';
import ProtectedRoute from '@/components/ProtectedRoute';
import { createBrowserRouter } from 'react-router';

import ErrorPage from '@/pages/ErrorPage';
import NotFoundPage from '@/pages/NotFoundPage';

const HomePage = lazy(() => import('@/pages/HomePage'));
const CartPage = lazy(() => import('@/pages/CartPage'));
const CategoriesPage = lazy(() => import('@/pages/CategoriesPage'));
const CategoryPage = lazy(() => import('@/pages/CategoryPage'));
const ProductsPage = lazy(() => import('@/pages/ProductsPage'));
const SingleProductPage = lazy(() => import('@/pages/SingleProductPage'));
const ContactPage = lazy(() => import('@/pages/ContactPage'));
const TermsPage = lazy(() => import('@/pages/TermsPage'));
const AccountPage = lazy(() => import('@/pages/AccountPage'));
const OrdersPage = lazy(() => import('@/pages/OrdersPage'));
const OrderPage = lazy(() => import('@/pages/OrderPage'));
const CheckoutPage = lazy(() => import('@/pages/CheckoutPage'));
const LoginPage = lazy(() => import('@/pages/LoginPage'));
const RegisterPage = lazy(() => import('@/pages/RegisterPage'));
const ForgotPasswordPage = lazy(() => import('@/pages/ForgotPasswordPage'));
const ResetPasswordPage = lazy(() => import('@/pages/ResetPasswordPage'));
const VerifyEmailPage = lazy(() => import('@/pages/VerifyEmailPage'));

export const router = createBrowserRouter([
  {
    path: '/',
    Component: AppLayout,
    ErrorBoundary: ErrorPage,
    children: [
      { path: '/', Component: HomePage },
      { path: '/cart', Component: CartPage },

      {
        path: '/categories',
        children: [
          { index: true, Component: CategoriesPage },
          { path: ':categoryName', Component: CategoryPage },
        ],
      },

      {
        path: '/products',
        children: [
          { index: true, Component: ProductsPage },
          { path: ':productName', Component: SingleProductPage },
        ],
      },
      { path: '/contact', Component: ContactPage },
      { path: '/terms', Component: TermsPage },
      {
        path: '/account',
        Component: ProtectedRoute,
        children: [
          { index: true, Component: AccountPage }, // /account
          { path: 'orders', Component: OrdersPage }, // /account/orders
          { path: 'orders/:orderId', Component: OrderPage },
        ],
      },

      {
        path: '/checkout',
        Component: ProtectedRoute,
        children: [{ index: true, Component: CheckoutPage }],
      },
    ],
  },

  {
    Component: AuthLayout,
    ErrorBoundary: ErrorPage,
    children: [
      { path: '/login', Component: LoginPage },
      { path: '/register', Component: RegisterPage },
      { path: '/forgot-password', Component: ForgotPasswordPage },
      { path: '/reset-password', Component: ResetPasswordPage },
      { path: '/verify-email', Component: VerifyEmailPage },
    ],
  },
  { path: '*', Component: NotFoundPage },
]);
