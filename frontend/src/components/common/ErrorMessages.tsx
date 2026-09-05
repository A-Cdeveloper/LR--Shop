import { getApiError, getApiErrorMessages } from '@/lib/apiError';
import { cn } from '@/lib/utils';

type ErrorMessagesProps = {
  error: unknown;
  className?: string;
};

const ErrorMessages = ({ error, className }: ErrorMessagesProps) => {
  const apiErrorMessages = getApiErrorMessages(getApiError(error));

  return (
    <div className={cn('space-y-1 text-xs  p-3 text-destructive text-center', className)}>
      {apiErrorMessages.map((message, index) => (
        <p key={`${message}-${index}`}>{message}</p>
      ))}
    </div>
  );
};

export default ErrorMessages;
