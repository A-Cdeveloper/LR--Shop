import { useMutation } from '@tanstack/react-query';
import { resendVerificationEmail } from '../api/authApi';
import type { AxiosError } from 'axios';
import type { ApiErrorResponse } from '@/lib/apiError';

export const useResendVerification = () => {
  const {
    mutate: resendVerification,
    isPending,
    isSuccess,
    error,
  } = useMutation<void, AxiosError<ApiErrorResponse>, string>({
    mutationFn: (email) => resendVerificationEmail(email),
  });

  return { resendVerification, isPending, isSuccess, error };
};
