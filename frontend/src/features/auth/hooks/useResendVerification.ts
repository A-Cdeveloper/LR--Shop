import { useMutation } from '@tanstack/react-query';
import { resendVerificationEmail } from '../api/authApi';

export const useResendVerification = (email: string) => {
  const {
    mutate: resendVerification,
    isPending,
    isSuccess,
    error,
  } = useMutation({
    mutationFn: () => resendVerificationEmail(email),
  });

  return { resendVerification, isPending, isSuccess, error };
};
