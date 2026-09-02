import { z } from 'zod';

export const emailSchema = z
  .string()
  .min(1, 'Email is required.')
  .email('Enter a valid email address.')
  .max(255, 'Email must be at most 255 characters.');

export const basicPasswordSchema = z
  .string()
  .min(1, 'Password is required.')
  .min(8, 'Password must be at least 8 characters.');

export const strongPasswordSchema = z
  .string()
  .min(8, 'Password must be at least 8 characters.')
  .regex(/[a-z]/, 'Password must contain at least one lowercase letter.')
  .regex(/[A-Z]/, 'Password must contain at least one uppercase letter.')
  .regex(/\d/, 'Password must contain at least one number.')
  .regex(/[^\w\s]/, 'Password must contain at least one special character.');

export const passwordConfirmationSchema = z.string().min(1, 'Password confirmation is required.');
