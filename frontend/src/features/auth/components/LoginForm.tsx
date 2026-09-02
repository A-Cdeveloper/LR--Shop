import { Loader2 } from 'lucide-react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

import InputCustum from '@/components/layout/common/InputGroup';
import { Button } from '@/components/ui/button';
import { getApiError, getApiErrorMessages } from '@/lib/apiError';

import { useLogin } from '../hooks/useLogin';
import { loginSchema, type LoginCredentials } from '../schemas/loginSchema';
import { getZodFieldErrors } from '@/lib/formErrors';
import FormWrapper from './FormWrapper';

const LoginForm = () => {
  const { loginMutation, isPending, error } = useLogin();
  const navigate = useNavigate();
  const [fieldErrors, setFieldErrors] = useState<Partial<Record<keyof LoginCredentials, string>>>(
    {},
  );

  const apiErrorMessages = getApiErrorMessages(getApiError(error));

  const handleSubmit = (event: React.SyntheticEvent<HTMLFormElement>) => {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const result = loginSchema.safeParse(Object.fromEntries(formData));

    if (!result.success) {
      setFieldErrors(getZodFieldErrors(result.error));
      return;
    }

    setFieldErrors({});
    loginMutation(result.data, {
      onSuccess: () => navigate('/'),
    });
  };

  return (
    <form onSubmit={handleSubmit} noValidate>
      <FormWrapper title="Login to your account" description="Login to your account to continue">
        <InputCustum
          label="Email"
          name="email"
          placeholder="E-Mail"
          disabled={isPending}
          error={fieldErrors.email}
        />
        <InputCustum
          label="Password"
          name="password"
          placeholder="Password"
          type="password"
          disabled={isPending}
          error={fieldErrors.password}
        />
        <Button aria-disabled={isPending} type="submit" disabled={isPending} variant="custom">
          {isPending ? (
            <>
              <Loader2 className="h-4 w-4 animate-spin" />
              <span>Logging in...</span>
            </>
          ) : (
            'Login'
          )}
        </Button>

        {apiErrorMessages.length > 0 && (
          <div className="space-y-1 text-xs bg-destructive/10 p-3 text-destructive text-center">
            {apiErrorMessages.map((message) => (
              <p key={message}>{message}</p>
            ))}
          </div>
        )}

        <div className="flex flex-col gap-2 justify-center items-center mt-3">
          <p className="text-muted-foreground">
            Don&apos;t have an account?{' '}
            <Link to="/register" className="text-primary underline hover:text-primary/80">
              Register
            </Link>
          </p>
          <p className="text-muted-foreground">
            Forgot your password?{' '}
            <Link to="/forgot-password" className="text-primary underline hover:text-primary/80">
              Reset password
            </Link>
          </p>
        </div>
      </FormWrapper>
    </form>
  );
};

export default LoginForm;
