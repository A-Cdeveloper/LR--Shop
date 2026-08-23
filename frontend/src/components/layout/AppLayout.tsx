import { Outlet } from 'react-router';
import Header from './header/Header';
import Footer from './footer/Footer';

const AppLayout = () => {
  return (
    <div className="min-h-screen bg-muted px-8 py-10 lg:px-8 lg:py-16">
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-primary focus:px-4 focus:py-2 focus:text-primary-foreground focus:outline-none focus:ring-2 focus:ring-ring"
      >
        Skip to main content
      </a>

      <div className="mx-auto flex min-h-[calc(100vh-3rem)] w-full max-w-[1350px] flex-col overflow-hidden rounded-2xl bg-white shadow-sm md:min-h-[calc(100vh-8rem)]">
        <Header />
        <main id="main-content" className="flex-1 overflow-y-auto p-4 md:p-6">
          <Outlet />
        </main>
        <Footer />
      </div>
    </div>
  );
};

export default AppLayout;
