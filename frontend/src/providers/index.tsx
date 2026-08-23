import ErrorBoundary from './errorboundary/ErrorBoundary';
import AppRouterProvider from './router/AppRouterProvider';
import TanstackProvider from './tanstackquery/TanstackProvider';

const AppProviders = () => {
  return (
    <ErrorBoundary>
      <TanstackProvider>
        <AppRouterProvider />
      </TanstackProvider>
    </ErrorBoundary>
  );
};

export default AppProviders;
