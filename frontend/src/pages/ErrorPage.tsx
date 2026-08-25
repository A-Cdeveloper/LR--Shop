import { isRouteErrorResponse, useNavigate, useRouteError } from 'react-router';
import { Button } from '@/components/ui/button';

const ErrorPage = () => {
  const error = useRouteError();
  const navigate = useNavigate();
  let message = 'Something went wrong';

  if (isRouteErrorResponse(error)) {
    message = error.data?.message || error.statusText || message;
  } else if (error instanceof Error) {
    message = error.message;
  }

  return (
    <div className="flex flex-col items-center justify-center h-screen gap-4">
      <h1 className="text-6xl font-bold">Error</h1>
      <p className="text-lg">{message}</p>

      <Button onClick={() => navigate('/')}>Back to Home</Button>
    </div>
  );
};

export default ErrorPage;
