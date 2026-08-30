import { useMutation } from '@tanstack/react-query';
import { login } from '../api/authApi';
import type { AxiosError } from 'axios';
import type { ApiErrorResponse } from '@/lib/apiError';
import type { LoginCredentials } from '../schemas/loginSchema';
import type { AuthUser } from '../types/auth';

type LoginError = AxiosError<ApiErrorResponse>;

export function useLogin() {
  const {
    mutate: loginMutation,
    isPending,
    isSuccess,
    error,
  } = useMutation<AuthUser, LoginError, LoginCredentials>({
    mutationFn: (credentials) => login(credentials),
  });

  return {
    loginMutation,
    isPending,
    isSuccess,
    error,
  };
}
