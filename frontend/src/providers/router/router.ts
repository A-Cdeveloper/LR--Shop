import AppLayout from '@/components/AppLayout';
import HomePage from '@/pages/HomePage';
import ErrorPage from '@/pages/ErrorPage';
import NotFoundPage from '@/pages/NotFoundPage';
import CartPage from '@/pages/CartPage';
import CategoriesPage from '@/pages/CategoriesPage';
import CategoryPage from '@/pages/CategoryPage';
import ProductsPage from '@/pages/ProductsPage';
import SingleProductPage from '@/pages/SingleProductPage';
import ContactPage from '@/pages/ContactPage';
import TermsPage from '@/pages/TermsPage';
import AccountPage from '@/pages/AccountPage';
import LoginPage from '@/pages/LoginPage';
import RegisterPage from '@/pages/RegisterPage';
import OrdersPage from '@/pages/OrdersPage';
import OrderPage from '@/pages/OrderPage';
import CheckoutPage from '@/pages/CheckoutPage';
import ForgotPasswordPage from '@/pages/ForgotPasswordPage';
import ResetPasswordPage from '@/pages/ResetPasswordPage';
import VerifyEmailPage from '@/pages/VerifyEmailPage';
import { createBrowserRouter } from 'react-router';
import ProtectedRoute from '@/components/ProtectedRoute';
import AuthLayout from '@/components/AuthLayout';

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
