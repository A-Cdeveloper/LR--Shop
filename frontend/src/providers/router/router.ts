import AppLayout from '@/components/layout/AppLayout';
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

export const router = createBrowserRouter([
  {
    path: '/',
    Component: AppLayout,
    ErrorBoundary: ErrorPage,
    children: [
      { path: '/', Component: HomePage },
      { path: '/cart', Component: CartPage },
      { path: '/checkout', Component: CheckoutPage },
      { path: '/categories', Component: CategoriesPage },
      { path: '/categories/:categoryName', Component: CategoryPage },
      { path: '/products', Component: ProductsPage },
      { path: '/products/:productName', Component: SingleProductPage },
      { path: '/contact', Component: ContactPage },
      { path: '/terms', Component: TermsPage },
      { path: '/login', Component: LoginPage },
      { path: '/register', Component: RegisterPage },
      { path: '/forgot-password', Component: ForgotPasswordPage },
      { path: '/reset-password', Component: ResetPasswordPage },
      { path: '/verify-email', Component: VerifyEmailPage },
      { path: '/account', Component: AccountPage },
      { path: '/account/orders', Component: OrdersPage },
      { path: '/account/orders/:orderId', Component: OrderPage },
    ],
  },
  { path: '*', Component: NotFoundPage },
]);
