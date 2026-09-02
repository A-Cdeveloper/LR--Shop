import { useMutation } from '@tanstack/react-query';
import { forgotPassword } from '../api/authApi';
import { AxiosError } from 'axios';
import type { ApiErrorResponse } from '@/lib/apiError';
import type { ForgotPassCredentials } from '../schemas/forgotPassSchema';

type ForgotPassError = AxiosError<ApiErrorResponse>;

export const useForgotPassword = () => {
  const {
    mutate: forgotPass,
    isPending,
    isSuccess,
    error,
  } = useMutation<{ message: string }, ForgotPassError, ForgotPassCredentials>({
    mutationFn: (credentials) => forgotPassword(credentials),
  });

  return { forgotPass, isPending, isSuccess, error };
};
