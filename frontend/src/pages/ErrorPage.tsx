// import ErrorMessage from '@/components/layout/ErrorMessage';
import { isRouteErrorResponse, useRouteError } from 'react-router';

const ErrorPage = () => {
  const error = useRouteError();

  let message = 'Something went wrong';

  if (isRouteErrorResponse(error)) {
    message = error.data?.message || error.statusText || message;
  } else if (error instanceof Error) {
    message = error.message;
  }

  // return <ErrorMessage message={message} />;
  return <div>{message}</div>;
};

export default ErrorPage;
