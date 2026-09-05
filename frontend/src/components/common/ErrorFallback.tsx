import { Button } from '@shop/ui';

type ErrorFallbackProps = {
  message: string;
  onRetry: () => void;
};

const ErrorFallback = ({ message, onRetry }: ErrorFallbackProps) => {
  return (
    <div className="flex flex-col items-center justify-center h-screen gap-4">
      <h1 className="text-6xl font-bold">Error</h1>
      <p className="text-lg">{message}</p>
      <Button onClick={onRetry}>Retry</Button>
    </div>
  );
};

export default ErrorFallback;
