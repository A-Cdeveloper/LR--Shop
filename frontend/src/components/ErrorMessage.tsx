import { Button } from '@/components/ui/button';
import { RotateCcwIcon } from 'lucide-react';

type ErrorMessageProps = {
  message: string;
  onRetry?: () => void;
};

const ErrorMessage = ({ message, onRetry }: ErrorMessageProps) => {
  return (
    <main
      id="main-content"
      role="alert"
      className="flex h-full min-h-0 w-full flex-1 items-center justify-center bg-background"
      data-testid="error-message"
    >
      <div className="flex flex-col items-center justify-center gap-2 px-4 text-center">
        <h2 className="text-2xl font-bold">Error</h2>
        <p className="text-sm text-muted-foreground">{message}</p>
        <Button
          variant="outline"
          onClick={() => {
            if (onRetry) {
              onRetry();
              return;
            }
            window.location.reload();
          }}
        >
          <RotateCcwIcon className="h-4 w-4" aria-hidden />
          Try again
        </Button>
      </div>
    </main>
  );
};

export default ErrorMessage;
