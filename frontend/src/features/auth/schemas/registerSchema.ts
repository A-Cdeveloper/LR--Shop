import { z } from 'zod';

export const registerSchema = z
  .object({
    name: z.string().min(1, 'Name is required.').max(255, 'Name must be at most 255 characters.'),
    email: z
      .string()
      .min(1, 'Email is required.')
      .email('Enter a valid email address.')
      .max(255, 'Email must be at most 255 characters.'),
    password: z
      .string()
      .min(8, 'Password must be at least 8 characters.')
      .regex(/[a-z]/, 'Password must contain at least one lowercase letter.')
      .regex(/[A-Z]/, 'Password must contain at least one uppercase letter.')
      .regex(/\d/, 'Password must contain at least one number.')
      .regex(/[^\w\s]/, 'Password must contain at least one special character.'),
    password_confirmation: z.string().min(1, 'Password confirmation is required.'),
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match.',
    path: ['password_confirmation'],
  });

export type RegisterCredentials = z.infer<typeof registerSchema>;
