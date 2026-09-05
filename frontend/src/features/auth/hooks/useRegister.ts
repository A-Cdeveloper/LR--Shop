import { useMutation } from '@tanstack/react-query';
import { register } from '../api/authApi';
import type { AxiosError } from 'axios';
import type { ApiErrorResponse } from '@/lib/apiError';
import type { RegisterSuccessResponse } from '@shop/api-types';
import type { RegisterCredentials } from '../schemas/registerSchema';

type RegisterError = AxiosError<ApiErrorResponse>;

export function useRegister() {
  const {
    mutate: registerMutation,
    isPending,
    isSuccess,
    error,
  } = useMutation<RegisterSuccessResponse, RegisterError, RegisterCredentials>({
    mutationFn: (credentials) => register(credentials),
  });

  return {
    registerMutation,
    isPending,
    isSuccess,
    error,
  };
}
