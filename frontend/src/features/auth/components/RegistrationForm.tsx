import { Loader2 } from 'lucide-react';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

import InputCustum from '@/components/layout/common/InputGroup';
import { Button } from '@/components/ui/button';
import { getApiError, getApiErrorMessages } from '@/lib/apiError';

import { useRegister } from '../hooks/useRegister';
import { registerSchema } from '../schemas/registerSchema';
import { getZodFieldErrors } from '@/lib/formErrors';
import type { RegisterCredentials } from '../schemas/registerSchema';

const RegistrationForm = () => {
  const { registerMutation, isPending, error } = useRegister();
  const navigate = useNavigate();
  const [fieldErrors, setFieldErrors] = useState<
    Partial<Record<keyof RegisterCredentials, string>>
  >({});

  const apiErrorMessages = getApiErrorMessages(getApiError(error));

  const handleSubmit = (event: React.SyntheticEvent<HTMLFormElement>) => {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const result = registerSchema.safeParse(Object.fromEntries(formData));

    if (!result.success) {
      setFieldErrors(getZodFieldErrors(result.error));
      return;
    }

    setFieldErrors({});
    registerMutation(result.data, {
      onSuccess: () => navigate('/verify-email', { state: { email: result.data.email } }),
    });
  };

  return (
    <form
      onSubmit={handleSubmit}
      noValidate
      className="flex w-full max-w-md flex-col gap-4 rounded-md border border-default bg-muted-foreground/5 p-8 shadow-xs"
    >
      <h1 className="text-2xl font-bold">Register a new account</h1>
      <InputCustum
        label="Name"
        name="name"
        placeholder="Name"
        disabled={isPending}
        error={fieldErrors.name}
      />
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
      <InputCustum
        label="Password Confirmation"
        name="password_confirmation"
        placeholder="Password Confirmation"
        type="password"
        disabled={isPending}
        error={fieldErrors.password_confirmation}
      />
      <Button aria-disabled={isPending} type="submit" disabled={isPending} variant="custom">
        {isPending ? (
          <>
            <Loader2 className="h-4 w-4 animate-spin" />
            <span>Registering...</span>
          </>
        ) : (
          'Register'
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
        <Link to="/login" className="text-primary underline hover:text-primary/80">
          Login
        </Link>
      </p>
    </form>
  );
};

export default RegistrationForm;
