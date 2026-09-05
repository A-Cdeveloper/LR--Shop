import { Outlet } from 'react-router';
import Header from './header/Header';
import Footer from './footer/Footer';
import Sidebar from './sidebar/Sidebar';

const AppLayout = () => {
  return (
    <div className="min-h-screen bg-gradient-to-tr from-background via-muted to-primary/10 px-4 py-4 md:px-8 md:py-10 lg:px-8 lg:py-16">
      <a
        href="#main-content"
        className="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-primary focus:px-4 focus:py-2 focus:text-primary-foreground focus:outline-none focus:ring-2 focus:ring-ring"
      >
        Skip to main content
      </a>

      <div className="mx-auto px-6 md:px-12 flex w-full max-w-[1200px] flex-col overflow-hidden rounded-2xl bg-white shadow-sm">
        <Header />
        <div className="flex flex-1 py-8 ">
          <Sidebar />
          <main id="main-content" className="min-w-0 flex-1 px-4">
            <Outlet />
          </main>
        </div>
        <Footer />
      </div>
    </div>
  );
};

export default AppLayout;
