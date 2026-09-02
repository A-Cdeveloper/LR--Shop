import { Loader2 } from 'lucide-react';
import { Link, useLocation, useSearchParams } from 'react-router-dom';

import { Button } from '@/components/ui/button';
import { getApiError, getApiErrorMessages } from '@/lib/apiError';
import { useResendVerification } from '@/features/auth/hooks/useResendVerification';

type VerifyEmailMessageProps = {
  title: string;
  message: string;
  link?: string;
  linkText?: string;
};

const VerifyEmailMessage = ({ title, message, link, linkText }: VerifyEmailMessageProps) => (
  <>
    <h1 className="text-2xl font-bold">{title}</h1>
    <p className="text-sm text-gray-500">{message}</p>
    {link && linkText && (
      <Link to={link} className="text-primary underline hover:text-primary/80">
        {linkText}
      </Link>
    )}
  </>
);

const VerifyEmailPage = () => {
  const [searchParams] = useSearchParams();
  const status = searchParams.get('status');
  const location = useLocation();
  const email = (location.state as { email?: string } | null)?.email ?? '';
  const { resendVerification, isPending, isSuccess, error } = useResendVerification();
  const resendErrorMessages = getApiErrorMessages(getApiError(error));

  if (status === 'success') {
    return (
      <VerifyEmailMessage
        title="Email verified successfully"
        message="You can now login to your account."
        link="/login"
        linkText="Login"
      />
    );
  }

  if (status === 'already_verified') {
    return (
      <VerifyEmailMessage
        title="Email already verified"
        message="Your email address has already been verified."
        link="/login"
        linkText="Login"
      />
    );
  }

  if (status === 'error') {
    return (
      <VerifyEmailMessage
        title="Email verification failed"
        message="Please request a new verification email."
        link="/register"
        linkText="Register"
      />
    );
  }

  return (
    <>
      <VerifyEmailMessage
        title="Check your email to verify your account"
        message="If you didn't receive the email, please check your spam folder or request a new verification email."
      />
      {email && (
        <Button onClick={() => resendVerification(email)} variant="custom" disabled={isPending}>
          {isPending ? (
            <>
              <Loader2 className="h-4 w-4 animate-spin" />
              <span>Requesting new verification email...</span>
            </>
          ) : (
            'Request new verification email'
          )}
        </Button>
      )}
      {isSuccess && (
        <p className="text-sm text-green-600">A new verification email has been sent.</p>
      )}
      {resendErrorMessages.length > 0 && (
        <div className="space-y-1 text-sm text-destructive">
          {resendErrorMessages.map((message) => (
            <p key={message}>{message}</p>
          ))}
        </div>
      )}
    </>
  );
};

export default VerifyEmailPage;
