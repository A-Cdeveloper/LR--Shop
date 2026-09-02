import InputCustum from '@/components/layout/common/InputGroup';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-react';
import { useForgotPassword } from '../hooks/useForgotPassword';
import { forgotPassSchema, type ForgotPassCredentials } from '../schemas/forgotPassSchema';
import { useState } from 'react';
import { getZodFieldErrors } from '@/lib/formErrors';
import { getApiError, getApiErrorMessages } from '@/lib/apiError';
import FormWrapper from './FormWrapper';

const ForgotPasswordForm = () => {
  const { forgotPass, isPending, error, isSuccess } = useForgotPassword();
  const [fieldErrors, setFieldErrors] = useState<
    Partial<Record<keyof ForgotPassCredentials, string>>
  >({});
  const [successMessage, setSuccessMessage] = useState<string>('');

  const apiErrorMessages = getApiErrorMessages(getApiError(error));

  const handleSubmit = (e: React.SyntheticEvent<HTMLFormElement>) => {
    e.preventDefault();
    const formData = new FormData(e.currentTarget);
    const result = forgotPassSchema.safeParse(Object.fromEntries(formData));
    if (!result.success) {
      setFieldErrors(getZodFieldErrors(result.error));
      return;
    }
    setSuccessMessage('');
    forgotPass(result.data, {
      onSuccess: (data) => {
        setSuccessMessage(data.message);
      },
    });
  };
  return (
    <form onSubmit={handleSubmit} noValidate>
      <FormWrapper
        title={!isSuccess ? 'Forgot your password?' : undefined}
        description={
          !isSuccess ? 'Forgot your password? Enter your email to reset your password' : undefined
        }
      >
        {!isSuccess && (
          <>
            <InputCustum
              label="Email"
              name="email"
              placeholder="E-Mail"
              disabled={isPending}
              error={fieldErrors.email}
            />
            <Button aria-disabled={isPending} type="submit" disabled={isPending} variant="custom">
              {isPending ? (
                <>
                  <Loader2 className="h-4 w-4 animate-spin" />
                  <span>Requesting new password...</span>
                </>
              ) : (
                'Request new password'
              )}
            </Button>
            {apiErrorMessages.length > 0 && (
              <div className="space-y-1 text-xs bg-destructive/10 p-3 text-destructive text-center">
                {apiErrorMessages.map((message) => (
                  <p key={message}>{message}</p>
                ))}
              </div>
            )}
          </>
        )}
        {isSuccess && (
          <div className="space-y-1 text-xs bg-green-100 p-3 text-green-800 text-center">
            <p>{successMessage}</p>
          </div>
        )}
      </FormWrapper>
    </form>
  );
};

export default ForgotPasswordForm;
