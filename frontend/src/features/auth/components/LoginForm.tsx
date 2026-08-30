import { Loader2 } from 'lucide-react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

import InputCustum from '@/components/layout/common/InputGroup';
import { Button } from '@/components/ui/button';
import { getApiError, getApiErrorMessages } from '@/lib/apiError';

import { useLogin } from '../hooks/useLogin';
import type { LoginCredentials } from '../types/auth';
import { loginSchema } from '../schemas/loginSchema';

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
      const nextFieldErrors: Partial<Record<keyof LoginCredentials, string>> = {};

      for (const issue of result.error.issues) {
        const field = issue.path[0] as keyof LoginCredentials;
        if (!nextFieldErrors[field]) {
          nextFieldErrors[field] = issue.message;
        }
      }

      setFieldErrors(nextFieldErrors);
      return;
    }

    setFieldErrors({});
    loginMutation(result.data, {
      onSuccess: () => navigate('/'),
    });
  };

  return (
    <form
      onSubmit={handleSubmit}
      noValidate
      className="flex w-full max-w-md flex-col gap-4 rounded-md border border-default bg-muted-foreground/5 p-8 shadow-xs"
    >
      <h1 className="text-2xl font-bold">Login to your account</h1>
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

      <p className="text-muted-foreground mt-3">
        Don&apos;t have an account?{' '}
        <Link to="/register" className="text-primary underline hover:text-primary/80">
          Register
        </Link>
      </p>
    </form>
  );
};

export default LoginForm;
