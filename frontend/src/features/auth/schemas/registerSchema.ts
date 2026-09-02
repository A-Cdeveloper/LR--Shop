import { z } from 'zod';

import { emailSchema, passwordConfirmationSchema, strongPasswordSchema } from './commonSchema';

export const registerSchema = z
  .object({
    name: z.string().min(1, 'Name is required.').max(255, 'Name must be at most 255 characters.'),
    email: emailSchema,
    password: strongPasswordSchema,
    password_confirmation: passwordConfirmationSchema,
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match.',
    path: ['password_confirmation'],
  });

export type RegisterCredentials = z.infer<typeof registerSchema>;
