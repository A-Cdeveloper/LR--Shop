import type { ApiErrorResponse } from '@/lib/apiError';
import { useMutation } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import type { ResetPassCredentials } from '../schemas/resetPassSchema';
import { resetPassword } from '../api/authApi';

type ResetPasswordError = AxiosError<ApiErrorResponse>;

export const useResetPassword = () => {
  const {
    mutate: resetPass,
    isPending,
    isSuccess,
    error,
  } = useMutation<{ message: string }, ResetPasswordError, ResetPassCredentials>({
    mutationFn: (credentials) => resetPassword(credentials),
  });

  return { resetPass, isPending, isSuccess, error };
};
