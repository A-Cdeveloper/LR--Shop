import { useState } from 'react';
import { useResetPassword } from '../hooks/useResetPassword';
import { resetPassSchema, type ResetPassCredentials } from '../schemas/resetPassSchema';
import { getZodFieldErrors } from '@/lib/formErrors';
import FormWrapper from './FormWrapper';
import InputCustum from '@/components/layout/common/InputGroup';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-react';
import { Link, useSearchParams } from 'react-router-dom';
import { getApiError, getApiErrorMessages } from '@/lib/apiError';

const ResetPasswordForm = () => {
  const [searchParams] = useSearchParams();
  const token = searchParams.get('token');
  const email = searchParams.get('email');

  const { resetPass, isPending, isSuccess, error } = useResetPassword();
  const [fieldErrors, setFieldErrors] = useState<
    Partial<Record<keyof ResetPassCredentials, string>>
  >({});
  const [successMessage, setSuccessMessage] = useState('');

  const apiError = getApiError(error);
  const apiErrorMessages = getApiErrorMessages(apiError);
  const isInvalidToken =
    apiError !== null && 'code' in apiError && apiError.code === 'reset_token_invalid';

  const handleSubmit = (e: React.SyntheticEvent<HTMLFormElement>) => {
    e.preventDefault();
    const formData = new FormData(e.currentTarget);
    const result = resetPassSchema.safeParse(Object.fromEntries(formData));
    if (!result.success) {
      setFieldErrors(getZodFieldErrors(result.error));
      return;
    }
    setSuccessMessage('');
    resetPass(result.data, {
      onSuccess: (data) => {
        setSuccessMessage(data.message);
      },
    });
  };
  return (
    <form onSubmit={handleSubmit} noValidate>
      <FormWrapper title={!isSuccess && !isInvalidToken ? 'Reset your password?' : undefined}>
        {!isSuccess && !isInvalidToken && (
          <>
            {email && <input type="hidden" name="email" value={email} />}
            <InputCustum
              type="password"
              label="Password"
              name="password"
              placeholder="Password"
              disabled={isPending}
              error={fieldErrors.password}
            />
            <InputCustum
              type="password"
              label="Confirm Password"
              name="password_confirmation"
              placeholder="Confirm Password"
              disabled={isPending}
              error={fieldErrors.password_confirmation}
            />
            {token && <input type="hidden" name="token" value={token} />}
            <Button aria-disabled={isPending} type="submit" disabled={isPending} variant="custom">
              {isPending ? (
                <>
                  <Loader2 className="h-4 w-4 animate-spin" />
                  <span>Resetting password...</span>
                </>
              ) : (
                'Reset password'
              )}
            </Button>
            {apiErrorMessages.length > 0 && !isInvalidToken && (
              <div className="space-y-1 text-xs bg-destructive/10 p-3 text-destructive text-center">
                {apiErrorMessages.map((message) => (
                  <p key={message}>{message}</p>
                ))}
              </div>
            )}
          </>
        )}

        {isInvalidToken && apiErrorMessages.length > 0 && (
          <>
            <div className="space-y-1 p-0 text-center text-xs text-destructive">
              {apiErrorMessages.map((message) => (
                <h1 className="text-xl font-bold mb-4" key={message}>
                  {message}
                </h1>
              ))}
            </div>
            <Link
              to="/forgot-password"
              className="text-primary underline hover:text-primary/80 text-center"
            >
              Request new token to reset password
            </Link>
          </>
        )}

        {isSuccess && (
          <div className="space-y-1 text-xs p-0 text-green-800 text-center">
            <h1 className="text-xl font-bold mb-4">{successMessage}</h1>
            <Link to="/login" className="text-primary underline hover:text-primary/80 text-center">
              Go to login page
            </Link>
          </div>
        )}
      </FormWrapper>
    </form>
  );
};

export default ResetPasswordForm;
