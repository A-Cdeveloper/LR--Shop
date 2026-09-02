import { z } from 'zod';
import { emailSchema, passwordConfirmationSchema, strongPasswordSchema } from './commonSchema';

export const resetPassSchema = z
  .object({
    email: emailSchema,
    password: strongPasswordSchema,
    password_confirmation: passwordConfirmationSchema,
    token: z.string().min(1, 'Reset token is required.'),
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match.',
    path: ['password_confirmation'],
  });

export type ResetPassCredentials = z.infer<typeof resetPassSchema>;
