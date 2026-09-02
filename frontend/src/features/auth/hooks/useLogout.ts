import { useMutation } from '@tanstack/react-query';
import { logout } from '../api/authApi';
import type { AxiosError } from 'axios';
import type { ApiErrorResponse } from '@/lib/apiError';

export function useLogout() {
  const { mutate: logoutHandler, isPending } = useMutation<
    void,
    AxiosError<ApiErrorResponse>,
    void
  >({
    mutationFn: logout,
  });

  return { logoutHandler, isPending };
}
